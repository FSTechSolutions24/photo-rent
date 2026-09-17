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
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MediaController extends Controller
{

    use HelperTrait;
    public function store(Request $request, Gallery $gallery)
    {
        $this->authorizeGallery($gallery);

        $validated = $request->validate([
            'folder_id' => [
                'nullable',
                'integer',
                Rule::exists('folders', 'id')->where('gallery_id', $gallery->id),
            ],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $photo = $request->file('photo');
        $photographer = Auth::user()->photographer;
        if ((int) $photographer->available_storage < (int) $photo->getSize()) {
            throw ValidationException::withMessages([
                'photo' => 'This upload exceeds your remaining storage allowance.',
            ]);
        }

        $userid = Auth::id();
        $path = $photo->store(
            "users/{$userid}/galleries/{$gallery->id}/" . ($validated['folder_id'] ?? 'root'),
            's3'
        );

        Media::create([
            'gallery_id' => $gallery->id,
            'folder_id' => $validated['folder_id'] ?? null,
            'path' => $path,
            'name' => $photo->getClientOriginalName(),
            'disk' => 's3',
            'size' => $photo->getSize(),
        ]);

        $photographer->decrement('available_storage', $photo->getSize());

        return back()->with('success', 'Photo uploaded successfully.');
    }

    public function destroy(Request $request, $galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        $this->authorizeGallery($gallery);
        $data = $request->validate(['id' => ['required', 'integer']]);
        $media = $gallery->media()->findOrFail($data['id']);

        if (! $this->unlink_media($media->id, $gallery->id)) {
            return response()->json([
                'message' => 'The media could not be deleted.',
            ], 500);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully',
        ]);
    }

    public function download(Request $request, $galleryId)
    {

        $gallery = Gallery::findOrFail($galleryId);
        $this->authorizeGallery($gallery);
        $data = $request->validate(['id' => ['required', 'integer']]);
        $media = $gallery->media()->findOrFail($data['id']);

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

        $gallery = Gallery::findOrFail($galleryId);
        $this->authorizeGallery($gallery);
        $data = $request->validate(['id' => ['required', 'integer']]);
        $folder = $gallery->folders()->findOrFail($data['id']);
        $mediaItems = $folder->media()->where('gallery_id', $gallery->id)->get();

        // 2. Create a unique temporary path for the zip file
        $zipFileName = (string) Str::uuid() . '.zip';
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
        $photographer = Auth::user()->photographer;
        abort_unless($photographer && (int) $gallery->photographer_id === (int) $photographer->id, 403);
    }

    public function updatePrivacy(Request $request, $galleryId)
    {
        $gallery = Gallery::findOrFail($galleryId);
        $this->authorizeGallery($gallery);

        $data = $request->validate([
            'id' => ['required', 'integer'],
            'private' => ['required', 'boolean'],
        ]);

        $media = $gallery->media()->findOrFail($data['id']);
        $media->update(['private' => $data['private']]);

        return response()->json([
            'success' => true,
            'private' => $media->private,
            'message' => $media->private
                ? 'Media is now visible only to the client and photographer.'
                : 'Media is now visible to guests as well.',
        ]);
    }
}
