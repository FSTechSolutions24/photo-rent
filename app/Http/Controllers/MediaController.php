<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Photo;
use App\Models\Folder;
use App\Models\Gallery;
use App\Traits\HelperTrait;
use Illuminate\Support\Str;
use App\Models\Photographer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    use HelperTrait;
    public function store(Request $request, Gallery $gallery)
    {
        $this->authorizeGallery($gallery);

        $validated = $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
            'photo' => 'required|image|max:10240', // 10MB per file
        ]);

        $userid = Auth::user()->id;
        $path = $request->file('photo')->store(
            "users/{$userid}/galleries/{$gallery->id}/" . ($validated['folder_id'] ?? 'root'),
            's3'
        );

        Media::create([
            'gallery_id' => $gallery->id,
            'folder_id' => $validated['folder_id'] ?? null,
            'uploaded_by' => Auth::id(),
            'file_path' => $path,
        ]);

        return back()->with('success', 'Photo uploaded successfully.');
    }

    public function destroy(Request $request, $galleryId)
    {
        try {
            $mediaId = $request->id;

            if(!$this->unlink_media($mediaId, $galleryId)){
                return response()->json([
                    'success' => false,
                    'message' => 'Error while deleting!'
                ], 500);
            }

            $deleted = Media::where('id', $mediaId)->where('gallery_id', $galleryId)->delete();

            if ($deleted === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Media not found'
                ], 404);
            }            

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function download(Request $request, $galleryId)
    {

        $media = Media::findOrFail($request->id);

        $url = Storage::disk('wasabi')->temporaryUrl(
            $media->path,
            now()->addMinutes(5),
            [
                'ResponseContentDisposition' => 'attachment; filename="' . basename($media->path) . '"'
            ]
        );

        return response()->json([
            'success' => true,
            'url' => $url
        ]);

    }

    public function download_folder(Request $request, $galleryId)
    {        

        $folder = Folder::where('id', $request->id)->first();
        $mediaItems = Media::where('folder_id', $request->id)->get();

        // 2. Create a unique temporary path for the zip file
        $zipFileName = $folder->name . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        $zip = new \ZipArchive;

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($mediaItems as $media) {
                // Get file content (works for local or S3 via Storage facade)
                if (Storage::disk('wasabi')->exists($media->path)) {
                    $fileContent = Storage::disk('wasabi')->get($media->path);                    
                    // Get just the filename (e.g., "image.jpg")
                    $fileName = basename($media->name); 
                    
                    // Add it to the zip archive
                    $zip->addFromString($fileName, $fileContent);
                }
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Could not create zip file'], 500);
        }

        $safeDownloadName = Str::slug($folder->name, '_') . '.zip';

        // 3. Return the file as an automatic download and delete it from local server storage when done
        return response()->download($zipFilePath, $safeDownloadName)->deleteFileAfterSend(true);

        // $url = Storage::disk('wasabi')->temporaryUrl(
        //     $media->path,
        //     now()->addMinutes(5),
        //     [
        //         'ResponseContentDisposition' => 'attachment; filename="' . basename($media->path) . '"'
        //     ]
        // );

        // return response()->json([
        //     'success' => true,
        //     'url' => $url
        // ]);

    }

    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }
}
