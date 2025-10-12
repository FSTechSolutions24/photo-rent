<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request, Gallery $gallery)
    {
        $this->authorizeGallery($gallery);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $gallery->folders()->create($validated);

        return back()->with('success', 'Folder created successfully.');
    }

    private function authorizeGallery(Gallery $gallery)
    {
        abort_if($gallery->client->photographer_id !== Auth::id(), 403);
    }
}
