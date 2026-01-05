<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Photo;
use App\Models\Folder;
use App\Models\Gallery;
use App\Traits\HelperTrait;
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

    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }
}
