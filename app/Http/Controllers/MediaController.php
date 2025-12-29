<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Photo;
use App\Models\Folder;
use App\Models\Gallery;
use App\Models\Photographer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function store(Request $request, Gallery $gallery)
    {
        $this->authorizeGallery($gallery);

        $validated = $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
            'photo' => 'required|image|max:10240', // 10MB per file
        ]);

        $path = $request->file('photo')->store(
            "galleries/{$gallery->id}/" . ($validated['folder_id'] ?? 'root'),
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
    
    public function unlink_media($mediaId, $galleryId)
    {
        // we need to check if this is my gallery or not from the media gallery_id check
        // delete media from storage
        // if successfully deleted then update storage then return true
        // else return false


        $photographer = Photographer::where('user_id', Auth::id())->first();

        if (! $photographer) {
            return false;
        }

        $media = Media::find($mediaId);

        if (!$media) {
            return false;
        }

        $size = $media->size;

        $mediaPath = $media->path;

        if (Storage::disk('public')->exists($mediaPath)) {
            Storage::disk('public')->delete($mediaPath);
        }

        $photographer->available_storage +=  $size;

        $photographer->save();

        return true;
    }

    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }
}
