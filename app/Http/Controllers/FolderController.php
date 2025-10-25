<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{

    public function store(Request $request, Gallery $gallery)
    {
        $this->authorizeGallery($gallery);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // If ID exists -> update, else create
        $folder = $gallery->folders()->updateOrCreate(
            ['id' => $request->id],
            $validated
        );
        
        $this->update_folder_thumbnail($request, $gallery, $folder);

        return response()->json(['folder' => $folder]);
    }


    function update_folder_thumbnail(Request $request, $gallery, $folder)
    {   
        // Handle thumbnail upload if exists     
        if ($request->hasFile('thumbnail_path')) {

            // 1. Delete old thumbnail if it exists
            if ($folder->thumbnail_path && Storage::disk('public')->exists($folder->thumbnail_path)) {
                Storage::disk('public')->delete($folder->thumbnail_path);
            }

            $folder->thumbnail_path = $request->file('thumbnail_path')->store("galleries/{$gallery->id}/folders/{$folder->id}/thumbnail", 'public'); // 'local' can later be changed to 's3' or 'wasabi'            
            $folder->save();
        }
    }

    public function index(Gallery $gallery)
    {
        // Pass gallery and folders to the Blade view
        return view('dashboard.folders.index', [
            'gallery' => $gallery,
        ]);
    }

    public function listJson($galleryId)
    {
        $folders = Folder::where('gallery_id', $galleryId)->get();
        return response()->json($folders);
    }

    public function upload()
    {
        dd('here');
    }


    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }
}
