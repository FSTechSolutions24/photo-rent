<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Str;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GalleryController extends Controller
{
    //
    public function show(Request $request, $photographer_subdomain, $client_name, $gallery_slug)
    {
        // 1️⃣ Fetch photographer by subdomain
        $photographer = Photographer::where('subdomain', $photographer_subdomain)->firstOrFail();

        // 2️⃣ Find the client under this photographer
        $client = $photographer->clients()->where('name', $client_name)->firstOrFail();

        // 3️⃣ Get the gallery with folders and images
        $gallery = $client->galleries()
            ->where('slug', $gallery_slug)
            ->with(['folders.media', 'media'])
            ->firstOrFail();

        // 4️⃣ Password protection logic
        $sessionKey = 'access_granted_' . $gallery->id;

        // If access is not already granted
        if (!session($sessionKey)) {

            // Handle password form submission (POST)
            if ($request->isMethod('post')) {

                $request->validate([
                    'password' => 'required|string'
                ]);

                // Check password match (assuming $gallery->password is hashed)
                // if (!Hash::check($request->password, $gallery->password)) {
                //     return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
                // }
                if (($request->password != $gallery->password)) {
                    return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
                }

                // Password correct → grant access
                session([$sessionKey => true]);

            } else {
                // No access yet → show password page
                return view('dashboard.galleries.password', compact('gallery', 'photographer', 'client'));
            }
        }

        // 5️⃣ Access granted → show gallery
        return view('dashboard.galleries.show', compact('gallery', 'photographer', 'client'));
    }

    public function index(Client $client){
        abort_if($client->photographer_id !== Auth::id(), 403);
        return view('dashboard.galleries.index', compact('client'));
    }


    public function create(Client $client)
    {
        abort_if($client->photographer_id !== Auth::id(), 403);
        return view('dashboard.galleries.create', compact('client'));
    }

    public function store(Request $request, Client $client)
    {
        $this->authorizeClient($client);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($validated['name']) ?: Str::random(8);
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store("thumbnails/{$client->id}", 's3');
        }

        $client->galleries()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'password' => Hash::make($validated['password']),
            'thumbnail_path' => $thumbnailPath,
        ]);

        return redirect()->route('dashboard.clients.show', $client)
            ->with('success', 'Gallery created successfully.');
    }

    private function authorizeClient(Client $client)
    {
        abort_if($client->photographer_id !== Auth::id(), 403);
    }
}
