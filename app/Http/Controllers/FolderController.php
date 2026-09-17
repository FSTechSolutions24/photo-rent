<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\Folder;
use App\Models\Gallery;
use App\Jobs\ProcessMedia;
use App\Traits\HelperTrait;
use Illuminate\Support\Str;
use App\Models\Photographer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GalleriesToBeEmailed;
use App\Models\GalleryDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class FolderController extends Controller
{

    use HelperTrait;
    public function store(Request $request, Gallery $gallery)
    {
        $this->authorizeGallery($gallery);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id' => [
                'nullable',
                'integer',
                Rule::exists('folders', 'id')->where('gallery_id', $gallery->id),
            ],
            'thumbnail_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        unset($validated['id'], $validated['thumbnail_path']);
        
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
        $this->authorizeGallery($gallery);
        // Pass gallery and folders to the Blade view
        return view('dashboard.folders.index', [
            'gallery' => $gallery,
        ]);
    }

    public function listJson(Gallery $gallery)
    {
        $this->authorizeGallery($gallery);
        $folders = $gallery->folders()->get();
        return response()->json($folders);
    }

    public function download(Request $request)
    {
        $request->validate([
            'id' => ['required'],
        ]);

        // The Vue modal sends the complete folder object; also accept a raw ID.
        $folderId = is_array($request->id) ? ($request->id['id'] ?? null) : $request->id;
        $folder = Folder::with('gallery')->findOrFail($folderId);

        $this->authorizeGallery($folder->gallery);

        $email = Auth::user()->email;
        $data['folder_id'] = $folder->id;
        $data['gallery_id'] = $folder->gallery_id;
        $data['requested_by_email'] = $email;
        $exist_download = $this->search_folder_file_exist($data);

        if($exist_download){
            $this->modify_folder_expiration_date($exist_download);
            $download = $exist_download;
        }
        else {
            $download = $this->add_new_folder_media_download_request($data);
        }

        // Every request, including a newly created one, needs an email waiting for the ZIP.
        $this->send_url_to_email_asked_for_download($download, $email);

        return response()->json([
            'message' => 'Your download is being prepared and will be emailed to ' . $email . '.',
        ]);
    }

    public function modify_folder_expiration_date($exist_download){
        if($exist_download->status != 'Completed'){
            return;
        }
        $exist_download->expires_at = Carbon::now()->addDays(3);
        $exist_download->save();
    }

    public function send_url_to_email_asked_for_download($download, $email){

        if(Auth::check()){
            $email = Auth::user()->email;
        }

        GalleriesToBeEmailed::create([
            'gallery_downloads_id' => $download->id,
            'send_to' => $email,
            'status' => 'Pending',
        ]);

        // we will need a script to be running every 1 minute to run the queue to send the emails
        // the script should get the records from GalleriesToBeEmailed where the status is Pending
        // if the email sent seccessfully then update the GalleriesToBeEmailed with the sent at and status completed
    
    }

    public function add_new_folder_media_download_request($data){

        $user_type = $this->get_current_user_type();
    
        return GalleryDownload::create([
            'gallery_id' => $data['gallery_id'],
            'folder_id' => $data['folder_id'],
            'user_type' => $user_type,
            'requested_by_email' => $data['requested_by_email'],
            'full_gallery' => $data['folder_id'] ? 0 : 1,
            'status' => 'Pending',
        ]);
    }

    public function search_folder_file_exist($data){

        // we need to check if the gallery exist and with the same permission of the current request
        // what is the current user permission? guest or client >> based on that we will do the user_type condition

        $current_user_type = $this->get_current_user_type();

        return GalleryDownload::where('user_type', $current_user_type)
        ->where('gallery_id', $data['gallery_id'])
        ->where('folder_id', $data['folder_id'])
        ->first();

    }

    
    public function get_current_user_type(){

        if(Auth::check()){
            return 'admin';
        }

        if(session('visitor_type') == 'client') {
            return 'admin';
        }

        else {
            return 'guest';
        }
        
    }

    public function upload(Request $request, Gallery $gallery, Folder $folder)
    {
        /**
         * Important logic:
         * We need to stop the upload if the file size exceeds the available remaining space
         * We also need to add this validation in Dropzone to prevent the upload from starting
         */

        $this->authorizeGallery($gallery);
        abort_unless((int) $folder->gallery_id === (int) $gallery->id, 404);

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo',
                'max:30720',
            ],
        ]);

        // Get uploaded file
        $file = $request->file('file');
        $userId = Auth::user()->id;

        $photographer = Auth::user()->photographer;
        if ((int) $photographer->available_storage < (int) $file->getSize()) {
            throw ValidationException::withMessages([
                'file' => 'This upload exceeds your remaining storage allowance.',
            ]);
        }

        $extension = $this->safeMediaExtension($file->getMimeType());
        $filename = Str::uuid() . '.' . $extension;
        $basePath = "users/{$userId}/galleries/{$gallery->id}/folders/{$folder->id}/media";

        // ✅ Store ORIGINAL only
        $originalPath = "{$basePath}/original/{$filename}";
        Storage::disk('wasabi')->put($originalPath, file_get_contents($file));
        // ✅ Save DB record (store only original for now)
        $media = $this->save_media_record($gallery->id, $folder->id, $originalPath, $file);

        // dd([
        //     config('queue.default'),
        //     config('queue.connections.database.table'),
        // ]);


        // dd(
        //     ProcessMedia::class,
        //     get_class(ProcessMedia::dispatch('a', 'b', 'c'))
        // );
        // ✅ Dispatch background job for processing
        ProcessMedia::dispatch($originalPath, $basePath, $filename, $media->id);

        return response()->json([
            'success' => true,
            'path' => Storage::url($originalPath),
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
            'disk' => 'wasabi',
            'mime_type' => $file->getMimeType(),
        ]);

        if($media){
            $this->deduct_from_available_storage($file->getSize());
        }

        return $media;
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
        $photographer = Photographer::where('user_id', Auth::id())->first();
        abort_unless($photographer && (int) $gallery->photographer_id === (int) $photographer->id, 403);
    }

    public function destroy(Gallery $gallery, Folder $folder)
    {
        $this->authorizeGallery($gallery);
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

            $disk = $single_media->disk ?: 'wasabi';
            Storage::disk($disk)->delete(array_unique([
                $single_media->path,
                str_replace('/original/', '/medium/', $single_media->path),
                str_replace('/original/', '/thumb/', $single_media->path),
            ]));

            $totalSize += $single_media->size;

            $single_media->delete();
        }

        $photographer->available_storage += $totalSize;
        $photographer->save();

        return true;
    }


    public function listJsonMedia(Gallery $gallery, Folder $folder)
    {
        $this->authorizeGallery($gallery);
        abort_unless((int) $folder->gallery_id === (int) $gallery->id, 404);
        $eloquent = Media::where('gallery_id', $gallery->id)->where('folder_id', $folder->id);

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
            $url = $this->get_pre_signed_url($model->path, 'thumb');            
            return '<div class="thumbnail-holder"><img class="img-fluid" src="'.$url.'" width="80"></div>';
        })
        ->addColumn('private_toggle', function ($model) {
            $checked = $model->private ? ' checked' : '';

            return '<div class="custom-control custom-switch">'
                .'<input type="checkbox" class="custom-control-input" id="media-private-'.$model->id.'" '
                .'onchange="window.toggleMediaPrivate('.$model->id.', this)"'.$checked.'>'
                .'<label class="custom-control-label" for="media-private-'.$model->id.'">Private</label>'
                .'</div>';
        })
        ->addColumn('actions', function($model){
            $buffer  = '<button onclick="window.deleteMedia('.$model->id.')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>';
            $buffer .= '<button onclick="window.downloadMedia('.$model->id.')" class="btn btn-sm btn-outline-success" style="margin-left: 4px;"><i class="fas fa-download"></i></button>';
            return $buffer;
        })
        ->addIndexColumn()
        ->rawColumns(['thumbnail','multiselect','private_toggle','actions'])
        ->make(true);
    }

    private function safeMediaExtension(string $mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/x-msvideo' => 'avi',
        ];

        if (! isset($extensions[$mimeType])) {
            throw ValidationException::withMessages(['file' => 'The uploaded file type is not allowed.']);
        }

        return $extensions[$mimeType];
    }

}
