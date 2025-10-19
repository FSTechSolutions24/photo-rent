<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gallery;
use Illuminate\Support\Str;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

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

                // $password = Crypt::decryptString($gallery->client_password);

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

    public function index(){
        return view('dashboard.galleries.index');
    }

    public function getData(){
    
        $photographer = Auth::user()->photographer;
        $galleries = $photographer
            ? $photographer->galleries()->with('client')->get()
            : collect();

        return DataTables::of($galleries)
        ->addColumn('actions', function ($model) {
            $editUrl = route('dashboard.galleries.edit', $model->id);
            return '<a href="'.$editUrl.'" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>';
        })
        // Add a client_name column instead of overriding client_id
        ->addColumn('client_name', function ($model) {
            return $model->client ? $model->client->name : '-';
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);

    }

    
    public function edit($id){
        $gallery = Gallery::findOrFail($id);
        $clients = Client::where('photographer_id', Auth::id())->get();
        $gallery->client_password = Crypt::decryptString($gallery->client_password);
        $gallery->guest_password = Crypt::decryptString($gallery->guest_password);
        return view('dashboard.galleries.edit', compact('gallery','clients'));   
    }

    protected function validateGallery(Request $request, $id = null){
        return $request->validate([
            'name' => 'required|string|max:255',
            'client_password' => ['required', 'string', 'min:6', 'different:guest_password'],
            'guest_password' => ['required', 'string', 'min:6', 'different:client_password'],
            'thumbnail' => 'nullable|image|max:2048',
            'client_id' => [
                'nullable',
                'integer',
                Rule::exists('clients', 'id')->where('photographer_id', Auth::id()),
            ],
        ]);
    }

    public function create()
    {
        $clients = Client::where('photographer_id', Auth::id())->get();
        return view('dashboard.galleries.create', compact('clients'));
    }

    public function update(Request $request, Gallery $gallery){
        $data = $this->validateGallery($request);

        $data = $this->prepare_gallery_data($data);

        $gallery->update($data);
        
        $this->update_gallery_thumbnail($request, $gallery);

        return redirect()->route('dashboard.galleries.index')->with('success', 'Client updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $this->validateGallery($request);

        $data['photographer_id'] = Auth::id();

        $data = $this->prepare_gallery_data($data);
        
        $gallery = Gallery::create($data);

        $this->update_gallery_thumbnail($request, $gallery);

        return redirect()->route('dashboard.galleries.index', $data['client_id'])->with('success', 'Gallery created successfully.');
    }

    public function update_gallery_thumbnail(Request $request, $gallery){
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_path')) {

            // 1. Delete old thumbnail if it exists
            if ($gallery->thumbnail_path && Storage::disk('public')->exists($gallery->thumbnail_path)) {
                Storage::disk('public')->delete($gallery->thumbnail_path);
            }

            // Store file locally under /storage/app/thumbnails/{gallery_id}
            $thumbnailPath = $request->file('thumbnail_path')->store("galleries/{$gallery->id}/thumbnail", 'public'); // 'local' can later be changed to 's3' or 'wasabi'
            // Update the gallery model
            $gallery->thumbnail_path = $thumbnailPath;
            $gallery->save();
        }
    }

    public function prepare_gallery_data(array $data){

        // Prepare other fields
        $data['slug'] = Str::slug($data['name']) ?: Str::random(8);
        $data['client_password'] = Crypt::encryptString($data['client_password']);
        $data['guest_password'] = Crypt::encryptString($data['guest_password']);

        return $data;
    }

    private function authorizeClient(Client $client)
    {
        abort_if($client->photographer_id !== Auth::id(), 403);
    }
}
