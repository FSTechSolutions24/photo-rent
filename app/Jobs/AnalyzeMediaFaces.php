<?php

namespace App\Jobs;

use App\Contracts\FaceAnalysisProvider;
use App\Models\Media;
use App\Models\MediaFace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

class AnalyzeMediaFaces implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 120, 300];
    public $uniqueFor = 600;

    protected $mediaId;

    public function __construct(int $mediaId)
    {
        $this->mediaId = $mediaId;
        $this->onQueue(config('face-recognition.queue'));
    }

    public function uniqueId(): string
    {
        return $this->mediaId.':'.config('face-recognition.model_version');
    }

    public function handle(FaceAnalysisProvider $provider): void
    {
        $media = Media::with('gallery')->find($this->mediaId);

        if (! $media || ! $media->gallery || ! $media->gallery->face_processing_enabled) {
            return;
        }

        $extension = strtolower(pathinfo($media->path, PATHINFO_EXTENSION));
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            return;
        }

        // Older uploads were physically written to Wasabi but recorded as
        // local. Their existing users/... object keys remain analyzable.
        $sourceDisk = str_starts_with($media->path, 'users/')
            ? 'wasabi'
            : ($media->disk ?: 'wasabi');
        $source = Storage::disk($sourceDisk)->get($media->path);
        $jpeg = $this->makeAnalysisJpeg($source);
        $checksum = hash('sha256', $jpeg);
        $configuredModel = config('face-recognition.model_version');

        if ($media->face_analysis_status === 'completed'
            && hash_equals((string) $media->face_analysis_checksum, $checksum)
            && $media->face_analysis_model_version === $configuredModel) {
            return;
        }

        $media->update([
            'face_analysis_status' => 'processing',
            'face_analysis_error' => null,
        ]);
        $media->gallery->update([
            'face_processing_status' => 'processing',
            'face_processing_error' => null,
        ]);

        $result = $provider->analyze($jpeg, 'media-'.$media->id.'.jpg');
        $modelVersion = $result['model_version'];
        if ($modelVersion !== $configuredModel) {
            throw new \RuntimeException(
                "Face service model [{$modelVersion}] does not match configured model [{$configuredModel}]."
            );
        }
        $newCrops = $this->storeFaceCrops($media, $jpeg, $checksum, $result['faces']);
        $oldCropFiles = $media->faces()->get(['crop_disk', 'crop_path']);

        DB::transaction(function () use ($media, $result, $newCrops, $checksum, $modelVersion) {
            $media->faces()->delete();

            foreach ($result['faces'] as $index => $face) {
                $box = $this->normalizedBox($face['bbox']);
                MediaFace::create([
                    'media_id' => $media->id,
                    'face_index' => $index,
                    'box_x' => $box[0],
                    'box_y' => $box[1],
                    'box_width' => $box[2],
                    'box_height' => $box[3],
                    'detection_confidence' => $face['confidence'],
                    'quality_score' => $face['quality'] ?? null,
                    'embedding' => array_map('floatval', $face['embedding']),
                    'crop_disk' => config('face-recognition.storage_disk'),
                    'crop_path' => $newCrops[$index],
                    'model_version' => $modelVersion,
                ]);
            }

            $media->update([
                'face_analysis_status' => 'completed',
                'face_analysis_checksum' => $checksum,
                'face_analysis_model_version' => $modelVersion,
                'face_analysis_error' => null,
                'face_analyzed_at' => now(),
            ]);
        });

        foreach ($oldCropFiles as $oldCrop) {
            if (! in_array($oldCrop->crop_path, $newCrops, true)) {
                Storage::disk($oldCrop->crop_disk)->delete($oldCrop->crop_path);
            }
        }
    }

    public function failed(Throwable $exception): void
    {
        $media = Media::with('gallery')->find($this->mediaId);
        if (! $media) {
            return;
        }

        $message = mb_substr($exception->getMessage(), 0, 4000);
        $media->update([
            'face_analysis_status' => 'failed',
            'face_analysis_error' => $message,
        ]);
        if ($media->gallery) {
            $media->gallery->update([
                'face_processing_status' => 'partial_failure',
                'face_processing_error' => $message,
            ]);
        }
    }

    private function makeAnalysisJpeg(string $source): string
    {
        $image = (new ImageManager(new Driver()))->read($source)->orient();
        $max = config('face-recognition.analysis_max_dimension');

        if ($image->width() >= $image->height()) {
            $image->scaleDown(width: $max);
        } else {
            $image->scaleDown(height: $max);
        }

        return (string) $image->toJpeg(config('face-recognition.analysis_jpeg_quality'));
    }

    private function storeFaceCrops(Media $media, string $jpeg, string $checksum, array $faces): array
    {
        $manager = new ImageManager(new Driver());
        $disk = config('face-recognition.storage_disk');
        $paths = [];

        try {
            foreach ($faces as $index => $face) {
                $image = $manager->read($jpeg);
                [$x, $y, $width, $height] = $this->pixelCrop($face['bbox'], $image->width(), $image->height());
                $crop = $image->crop($width, $height, $x, $y)->cover(
                    config('face-recognition.crop_size'),
                    config('face-recognition.crop_size')
                );
                $path = "face-data/galleries/{$media->gallery_id}/media/{$media->id}/{$checksum}-{$index}.jpg";
                if (! Storage::disk($disk)->put($path, (string) $crop->toJpeg(85))) {
                    throw new \RuntimeException("Unable to store private face crop [{$path}].");
                }
                $paths[$index] = $path;
            }
        } catch (Throwable $exception) {
            foreach ($paths as $path) {
                Storage::disk($disk)->delete($path);
            }
            throw $exception;
        }

        return $paths;
    }

    private function normalizedBox(array $box): array
    {
        $x = max(0.0, min(1.0, (float) $box[0]));
        $y = max(0.0, min(1.0, (float) $box[1]));
        $width = max(0.0, min(1.0 - $x, (float) $box[2]));
        $height = max(0.0, min(1.0 - $y, (float) $box[3]));

        return [$x, $y, $width, $height];
    }

    private function pixelCrop(array $box, int $imageWidth, int $imageHeight): array
    {
        [$x, $y, $width, $height] = $this->normalizedBox($box);
        $centerX = ($x + ($width / 2)) * $imageWidth;
        $centerY = ($y + ($height / 2)) * $imageHeight;
        $side = max($width * $imageWidth, $height * $imageHeight);
        $side *= 1 + (2 * config('face-recognition.crop_padding'));
        $side = max(1, min($side, $imageWidth, $imageHeight));
        $left = max(0, min($imageWidth - $side, $centerX - ($side / 2)));
        $top = max(0, min($imageHeight - $side, $centerY - ($side / 2)));

        return [(int) round($left), (int) round($top), (int) round($side), (int) round($side)];
    }
}
