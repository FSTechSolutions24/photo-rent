<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gallery;
use Illuminate\Support\Str;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    //
    public function show(Request $request, $photographer_subdomain, $gallery_slug)
    {
        // 1️⃣ Fetch photographer by subdomain
        $photographer = Photographer::where('subdomain', $photographer_subdomain)->firstOrFail();

        // 2️⃣ Find the client under this photographer

        // 3️⃣ Get the gallery with folders and images
        $gallery = $photographer->galleries()
            ->where('slug', $gallery_slug)
            ->with(['folders.media'])
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

                $storedClientPassword = Crypt::decryptString($gallery->client_password);
                $storedGuestPassword = Crypt::decryptString($gallery->guest_password);

                $guest = $client = false;
                if ($request->password == $storedGuestPassword) {
                    $guest = true;
                    session(['visitor_type' => 'guest']);
                }
                if ($request->password == $storedClientPassword) {
                    $client = true;
                    session(['visitor_type' => 'client']);
                }

                if(!$guest && !$client) {
                    return back()
                        ->withErrors(['password' => 'Incorrect password.'])
                        ->withInput();
                }

                // Password correct → grant access
                session([$sessionKey => true]);

            } else {
                // No access yet → show password page
                return view('dashboard.galleries.password', compact('gallery', 'photographer'));
            }
        }

        // 5️⃣ Access granted → show gallery
        return view('dashboard.galleries.show', compact('gallery', 'photographer'));
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
        ->addColumn('is_public', function ($model) {
            return $model->is_public == '1' ? 'Yes' : 'No';
        })
        ->addColumn('actions', function ($model) {

            $editUrl = route('dashboard.galleries.edit', $model->id);

            $buffer = '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>';

            $folderUrl = route('dashboard.galleries.folders.index', [
                'gallery' => $model->id,
            ]);

            $buffer .= '<a href="'.$folderUrl.'" class="btn btn-sm btn-outline-success" style="margin-left: 10px;">
                <i class="fas fa-cogs"></i>
            </a>';
            
            return $buffer;
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);

    }
    
    public function edit($id){
        $gallery = Gallery::findOrFail($id);
        $clients = auth()->user()->photographer->clients;
        $gallery->client_password = Crypt::decryptString($gallery->client_password);
        $gallery->guest_password = Crypt::decryptString($gallery->guest_password);
        return view('dashboard.galleries.edit', compact('gallery','clients'));   
    }

    protected function validateGallery(Request $request, $gallery = null)
    {
        // Determine the correct photographer_id
        $photographerId = $gallery ? $gallery->photographer_id : Photographer::where('user_id', Auth::id())->first()->id;

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('galleries')
                    ->where(fn ($query) => $query->where('photographer_id', $photographerId))
                    ->when($gallery, fn($rule) => $rule->ignore($gallery->id)),
            ],
            'client_password' => ['required', 'string', 'min:6', 'different:guest_password'],
            'guest_password' => ['required', 'string', 'min:6', 'different:client_password'],
            'thumbnail' => 'nullable|image|max:2048',
            'is_public' => ['nullable', 'in:0,1'],
        ]);
    }

    public function create()
    {
        $clients = auth()->user()->photographer->clients;
        return view('dashboard.galleries.create', compact('clients'));
    }

    public function update(Request $request, Gallery $gallery){
        $data = $this->validateGallery($request, $gallery);

        $data = $this->prepare_gallery_data($data);

        $gallery->update($data);
        
        $this->update_gallery_thumbnail($request, $gallery);

        return redirect()->route('dashboard.galleries.index')->with('success', 'Client updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $this->validateGallery($request);

        $data['photographer_id'] = Photographer::where('user_id', Auth::id())->first()->id;

        $data = $this->prepare_gallery_data($data);
        
        $gallery = Gallery::create($data);

        $this->update_gallery_thumbnail($request, $gallery);

        return redirect()->route('dashboard.galleries.index')->with('success', 'Gallery created successfully.');
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
