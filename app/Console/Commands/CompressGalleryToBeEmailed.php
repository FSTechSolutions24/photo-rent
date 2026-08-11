<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Gallery;
use App\Models\Folder;
use App\Models\GalleryDownload;
use App\Models\Media;

class CompressGalleryToBeEmailed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compress:gallery-to-be-emailed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compress galleries that are waiting to be emailed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // for testing
        // php artisan compress:gallery-to-be-emailed
        
        // Get galleries that need to be compressed
        $gallery_download = GalleryDownload::where('status', 'Pending')->with(['gallery'])->first();            

        if(!$gallery_download){
            return;
        }

        $this->info('Compressing gallery: ' . $gallery_download->gallery_id);

        try {

            $zipFilePath = $this->compressGallery($gallery_download);

            if ($zipFilePath) {

                $gallery_download->status = 'Completed';
                $gallery_download->url = $zipFilePath;
                $gallery_download->save();

                $this->info(
                    'Gallery ' . $gallery_download->gallery_id . ' compressed successfully.'
                );
            }

        } catch (\Exception $e) {

            $this->error(
                'Failed to compress gallery ' .
                $gallery_download->id .
                ': ' .
                $e->getMessage()
            );

            // Optional
            $gallery_download->status = 'Failed';
            $gallery_download->save();
        }
        

        return Command::SUCCESS;
    }

    /**
     * Compress a gallery into a ZIP file.
     *
     * @param Gallery $gallery
     * @return string
     */
    private function compressGallery($gallery_download)
    {
        // Get all folders belonging to the gallery
        $folders = Folder::where('gallery_id', $gallery_download->gallery_id)->get();

        // Gallery folder name inside ZIP
        $galleryFolderName = Str::slug($gallery_download->gallery->name, '_');

        // Create unique ZIP filename
        $zipFileName = $galleryFolderName . '_' . time() . '.zip';

        // Save ZIP locally
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        $zip = new \ZipArchive();

        if (
            $zip->open(
                $zipFilePath,
                \ZipArchive::CREATE | \ZipArchive::OVERWRITE
            ) !== true
        ) {
            throw new \Exception('Could not create ZIP file.');
        }

        foreach ($folders as $folder) {

            $this->info('Processing folder: ' . $folder->name);

            $mediaItems = Media::where('folder_id', $folder->id);

            if($gallery_download->user_type == 'guest'){
                $mediaItems->where('private', '!=', 1);
            }

            $mediaItems = $mediaItems->get();

            $folderName = Str::slug($folder->name, '_');

            foreach ($mediaItems as $media) {

                if (!Storage::disk('wasabi')->exists($media->path)) {

                    $this->warn(
                        'File does not exist: ' . $media->path
                    );

                    continue;
                }

                // Get file from Wasabi
                $fileContent = Storage::disk('wasabi')->get($media->path);

                // Get filename
                $fileName = basename($media->name);

                // Path inside ZIP
                $zipPath =
                    $galleryFolderName .
                    '/' .
                    $folderName .
                    '/' .
                    $fileName;

                $zip->addFromString(
                    $zipPath,
                    $fileContent
                );
            }
        }

        $zip->close();

        return $zipFilePath;
    }
}