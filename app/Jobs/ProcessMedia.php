<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessMedia implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $originalPath;
    protected $basePath;
    protected $filename;
    protected $mediaId;

    public function __construct($originalPath, $basePath, $filename, $mediaId = null)
    {
        $this->originalPath = $originalPath;
        $this->basePath = $basePath;
        $this->filename = pathinfo($filename, PATHINFO_FILENAME);
        $this->mediaId = $mediaId;
    }

    public function handle()
    {
        $extension = strtolower(pathinfo($this->originalPath, PATHINFO_EXTENSION));

        // Skip non-images
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return;
        }

        $manager = new ImageManager(new Driver());

        $fileContent = Storage::disk('wasabi')->get($this->originalPath);

        // =========================
        // MEDIUM
        // =========================
        $medium = $manager->read($fileContent)->orient();
        if ($medium->width() >= $medium->height()) {
            $medium->scaleDown(width: 2000);
        } else {
            $medium->scaleDown(height: 2000);
        }

        Storage::disk('wasabi')->put(
            "{$this->basePath}/medium/{$this->filename}.{$extension}",
            $this->encodeForExtension($medium, $extension, 80)
        );

        // =========================
        // THUMB
        // =========================
        $thumb = $manager->read($fileContent)->orient()->cover(400, 400); // like fit()

        Storage::disk('wasabi')->put(
            "{$this->basePath}/thumb/{$this->filename}.{$extension}",
            $this->encodeForExtension($thumb, $extension, 70)
        );

        if ($this->mediaId && config('face-recognition.enabled')) {
            AnalyzeMediaFaces::dispatch((int) $this->mediaId);
        }
    }

    private function encodeForExtension($image, string $extension, int $quality): string
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return (string) $image->toJpeg($quality);
            case 'png':
                return (string) $image->toPng();
            case 'gif':
                return (string) $image->toGif();
            case 'webp':
                return (string) $image->toWebp($quality);
        }

        throw new \InvalidArgumentException("Unsupported image extension [{$extension}].");
    }
}
