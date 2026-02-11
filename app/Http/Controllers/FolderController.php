<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Folder;
use App\Models\Gallery;
use App\Traits\HelperTrait;
use App\Models\Photographer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FolderController extends Controller
{

    use HelperTrait;
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

        $userid = Auth::user()->id;

        // Define a path, e.g. galleries/{galleryId}/{folderId}/
        $path = "users/{$userid}/galleries/{$galleryId}/folders/{$folderId}/media";

        // // Store file in the public disk
        $storedPath = $file->store($path, 's3');

        // $path = $file->store('galleries/session1', 's3');

        // Optional: Save record in DB if you have a File/Media model
        $this->save_media_record($galleryId, $folderId, $storedPath, $file);

        return response()->json([
            'success' => true,
            'path' => Storage::url($storedPath),
            'name' => $file->getClientOriginalName(),
        ]);
    }

    function save_media_record($galleryId, $folderId, $storedPath, $file){
        $media = Media::create([
            'gallery_id' => $galleryId,
            'folder_id' => $folderId,
            'name' => $file->getClientOriginalName(),
            'path' => $storedPath,
            'size' => $file->getSize(),
            'disk' => 'local',
            'mime_type' => $file->getMimeType(),
        ]);

        if($media){
            $this->deduct_from_available_storage($file->getSize());
        }
    }

    function deduct_from_available_storage($size){
        $photographer = Photographer::where('user_id', Auth::id())->first();

        if (! $photographer) {
            return false;
        }

        $photographer->available_storage = max(0, $photographer->available_storage - $size);

        $photographer->save();

        return true;
    }

    private function authorizeGallery(Gallery $gallery)
    {
        $photographer_id = Photographer::where('user_id', Auth::id())->first()->id;
        abort_if(!$photographer_id, 403);
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
        
        // Delete linked media records
        $this->delete_folder_media($gallery->id, $folder->id);
        
        // Delete DB record
        $folder->delete();
 
        return response()->json(['message' => 'Folder and its contents deleted successfully']);
    }

    public function delete_folder_media($gallery_id, $folder_id)
    {
        $photographer = Photographer::where('user_id', Auth::id())->first();

        if (! $photographer) {
            return false;
        }

        $media = Media::where('gallery_id', $gallery_id)->where('folder_id', $folder_id)->get();

        $totalSize = 0;

        foreach ($media as $single_media) {

            $mediaPath = $single_media->path;

            if (Storage::disk('public')->exists($mediaPath)) {
                Storage::disk('public')->delete($mediaPath);
            }

            $totalSize += $single_media->size;

            $single_media->delete();
        }

        $photographer->available_storage += $totalSize;
        $photographer->save();

        return true;
    }


    public function listJsonMedia($galleryId, $folderId)
    {
        $eloquent = Media::where('gallery_id', $galleryId)->where('folder_id', $folderId);

        return DataTables::eloquent($eloquent)
        ->editColumn('created_at', function ($model) {
            return date_format($model->created_at, 'd/m/Y');
        })
        ->editColumn('size', function ($model) {
            $size = $model->size; // in bytes
            if ($size >= 1073741824) { // 1 GB
                return number_format($size / 1073741824, 2) . ' GB';
            } elseif ($size >= 1048576) { // 1 MB
                return number_format($size / 1048576, 2) . ' MB';
            } elseif ($size >= 1024) { // 1 KB
                return number_format($size / 1024, 2) . ' KB';
            } else {
                return $size . ' bytes';
            }
        })
        ->addColumn('multiselect', function ($model) {           
            return '<input class="form-control" type="checkbox">';
        })
        ->addColumn('thumbnail', function ($model) {
            $url = Storage::url($model->path);
            return '<div class="thumbnail-holder"><img class="img-fluid" src="'.$url.'" width="80"></div>';
        })
        ->addColumn('delete', function($model){
            return '<button onclick="window.deleteMedia('.$model->id.')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>';
        })
        ->addIndexColumn()
        ->rawColumns(['thumbnail','multiselect','delete'])
        ->make(true);
    }

}
