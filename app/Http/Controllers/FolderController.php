<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Folder;
use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

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

    public function listJsonMedia($galleryId, $folderId)
    {
        $eloquent = Media::where('gallery_id', $galleryId)->where('folder_id', $folderId);

        return DataTables::eloquent($eloquent)
        ->addColumn('thumbnail', function ($model) {
            $url = Storage::url($model->path);
            return '<div class="thumbnail-holder"><img class="img-fluid" src="'.$url.'" width="80"></div>';
        })
        ->addIndexColumn()
        ->rawColumns(['thumbnail'])
        ->make(true);
    }

    public function upload(Request $request, $galleryId, $folderId)
    {
        /**
         * Important logic:
         * We need to stop the upload if the file size exceeds the available remaining space
         * We also need to add this validation in Dropzone to prevent the upload from starting
         */

        // Validate file
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,gif,mp4,mov,avi|max:30720', // max 30MB, adjust as needed
        ]);

        // Get uploaded file
        $file = $request->file('file');

        // Define a path, e.g. galleries/{galleryId}/{folderId}/
        $path = "galleries/{$galleryId}/folders/{$folderId}/media";

        // Store file in the public disk
        $storedPath = $file->store($path, 'public');

        // Optional: Save record in DB if you have a File/Media model
        $this->save_media_record($galleryId, $folderId, $storedPath, $file);

        return response()->json([
            'success' => true,
            'path' => Storage::url($storedPath),
            'name' => $file->getClientOriginalName(),
        ]);
    }

    function save_media_record($galleryId, $folderId, $storedPath, $file){
        return Media::create([
            'gallery_id' => $galleryId,
            'folder_id' => $folderId,
            'name' => $file->getClientOriginalName(),
            'path' => $storedPath,
            'size' => $file->getSize(),
            'disk' => 'local',
            'mime_type' => $file->getMimeType(),
        ]);
    }

    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }

    public function destroy(Gallery $gallery, Folder $folder)
    {
        // Ensure folder belongs to this gallery
        if ($folder->gallery_id !== $gallery->id) {
            return response()->json(['message' => 'Folder does not belong to this gallery'], 403);
        }
 
        // Build the folder path used for storing files
        $folderPath = "galleries/{$gallery->id}/folders/{$folder->id}";
 
        // Delete all files in that folder (including thumbnail)
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }
 
        // NEED TO DELETED FROM MEDIA TABLE
 
        // Delete DB record
        $folder->delete();

        // Delete linked media records
        Media::where('gallery_id', $gallery->id)->where('folder_id', $folder->id)->delete();
 
        return response()->json(['message' => 'Folder and its contents deleted successfully']);
    }

}
