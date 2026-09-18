<?php

namespace App\Jobs;

use App\Contracts\FaceAnalysisProvider;
use App\Models\Gallery;
use App\Models\GalleryFaceCluster;
use App\Models\MediaFace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ClusterGalleryFaces implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 120, 300];
    public $uniqueFor = 600;

    protected $galleryId;

    public function __construct(int $galleryId)
    {
        $this->galleryId = $galleryId;
        $this->onQueue(config('face-recognition.queue'));
    }

    public function uniqueId(): string
    {
        return $this->galleryId.':'.config('face-recognition.model_version');
    }

    public function handle(FaceAnalysisProvider $provider): void
    {
        $gallery = Gallery::find($this->galleryId);
        if (! $gallery || ! $gallery->face_processing_enabled) {
            return;
        }

        $modelVersion = config('face-recognition.model_version');
        $faces = MediaFace::whereHas('media', function ($query) use ($gallery) {
            $query->where('gallery_id', $gallery->id);
        })->where('model_version', $modelVersion)->with('cluster')->orderBy('id')->get();

        $payload = $faces->map(function (MediaFace $face) {
            return [
                'id' => (string) $face->id,
                'embedding' => $face->embedding,
                'quality' => $face->quality_score,
            ];
        })->all();

        $result = $payload
            ? $provider->cluster($payload)
            : ['model_version' => $modelVersion, 'clusters' => []];

        if ($result['model_version'] !== $modelVersion) {
            throw new RuntimeException('The clustering model version does not match Laravel configuration.');
        }

        $prepared = $this->prepareClusters($gallery, $faces, $result['clusters'], $modelVersion);
        $oldThumbnails = $gallery->faceClusters()->get(['thumbnail_disk', 'thumbnail_path']);

        try {
            DB::transaction(function () use ($gallery, $faces, $prepared) {
                MediaFace::whereHas('media', function ($query) use ($gallery) {
                    $query->where('gallery_id', $gallery->id);
                })->update(['gallery_face_cluster_id' => null]);
                GalleryFaceCluster::where('gallery_id', $gallery->id)->delete();

                foreach ($prepared as $item) {
                    $cluster = GalleryFaceCluster::create($item['cluster']);
                    MediaFace::whereIn('id', $item['face_ids'])
                        ->update(['gallery_face_cluster_id' => $cluster->id]);
                }

                $hasFailures = $gallery->media()->where('face_analysis_status', 'failed')->exists();
                $gallery->update([
                    'face_processing_status' => $hasFailures ? 'partial_failure' : 'ready',
                    'face_processing_error' => $hasFailures ? $gallery->face_processing_error : null,
                    'faces_clustered_at' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            $this->deletePreparedThumbnails($prepared);
            throw $exception;
        }

        $newPaths = array_column(array_column($prepared, 'cluster'), 'thumbnail_path');
        foreach ($oldThumbnails as $thumbnail) {
            if ($thumbnail->thumbnail_disk && $thumbnail->thumbnail_path
                && ! in_array($thumbnail->thumbnail_path, $newPaths, true)) {
                Storage::disk($thumbnail->thumbnail_disk)->delete($thumbnail->thumbnail_path);
            }
        }
    }

    public function failed(Throwable $exception): void
    {
        Gallery::whereKey($this->galleryId)->update([
            'face_processing_status' => 'unavailable',
            'face_processing_error' => mb_substr($exception->getMessage(), 0, 4000),
        ]);
    }

    private function prepareClusters(Gallery $gallery, $faces, array $clusters, string $modelVersion): array
    {
        $facesById = $faces->keyBy(function (MediaFace $face) {
            return (string) $face->id;
        });
        $targetDisk = config('face-recognition.storage_disk');
        $prepared = [];

        try {
            foreach ($clusters as $serviceCluster) {
                $representative = $facesById->get((string) $serviceCluster['representative_face_id']);
                if (! $representative) {
                    throw new RuntimeException('Representative face is outside this gallery.');
                }

                $uuid = (string) Str::uuid();
                $path = "face-data/galleries/{$gallery->id}/clusters/{$uuid}.jpg";
                $bytes = Storage::disk($representative->crop_disk)->get($representative->crop_path);
                if (! Storage::disk($targetDisk)->put($path, $bytes)) {
                    throw new RuntimeException("Unable to store cluster thumbnail [{$path}].");
                }

                $faceIds = array_map('intval', $serviceCluster['face_ids']);
                $previousStatuses = collect($faceIds)->map(function ($faceId) use ($facesById) {
                    return optional(optional($facesById->get((string) $faceId))->cluster)->status;
                })->filter();
                $status = $previousStatuses->contains(GalleryFaceCluster::STATUS_HIDDEN)
                    ? GalleryFaceCluster::STATUS_HIDDEN
                    : GalleryFaceCluster::STATUS_VISIBLE;
                $prepared[] = [
                    'face_ids' => $faceIds,
                    'cluster' => [
                        'gallery_id' => $gallery->id,
                        'uuid' => $uuid,
                        'representative_media_face_id' => $representative->id,
                        'face_count' => count($faceIds),
                        'status' => $status,
                        'model_version' => $modelVersion,
                        'thumbnail_disk' => $targetDisk,
                        'thumbnail_path' => $path,
                    ],
                ];
            }
        } catch (Throwable $exception) {
            $this->deletePreparedThumbnails($prepared);
            throw $exception;
        }

        return $prepared;
    }

    private function deletePreparedThumbnails(array $prepared): void
    {
        foreach ($prepared as $item) {
            Storage::disk($item['cluster']['thumbnail_disk'])
                ->delete($item['cluster']['thumbnail_path']);
        }
    }
}
