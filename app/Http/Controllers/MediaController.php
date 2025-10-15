<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Folder;
use App\Models\Media;
use App\Models\Photo;
use Illuminate\Http\Request;
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

    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }
}
