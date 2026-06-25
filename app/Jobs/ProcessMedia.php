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

    public function __construct($originalPath, $basePath, $filename)
    {
        $this->originalPath = $originalPath;
        $this->basePath = $basePath;
        $this->filename = pathinfo($filename, PATHINFO_FILENAME);
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

        $image = $manager->read($fileContent);

        // =========================
        // MEDIUM
        // =========================
        $medium = $image->scale(width: 2000); // keeps aspect ratio

        Storage::disk('wasabi')->put(
            "{$this->basePath}/medium/{$this->filename}.{$extension}",
            (string) $medium->toWebp(80)
        );

        // =========================
        // THUMB
        // =========================
        $thumb = $image->cover(400, 400); // like fit()

        Storage::disk('wasabi')->put(
            "{$this->basePath}/thumb/{$this->filename}.{$extension}",
            (string) $thumb->toWebp(70)
        );
    }
}