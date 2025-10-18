<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Gallery;
use Illuminate\Support\Str;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

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

    public function index(){
        return view('dashboard.galleries.index');
    }

    public function getData(){

        $photographer = Auth::user()->photographer;
        $clients = $photographer ? $photographer->clients()->with('galleries')->get() : collect();
        $eloquent = $clients->pluck('galleries')->flatten();        
    
        return DataTables::of($eloquent)
         ->addColumn('actions', function ($model) {
            $editUrl = route('dashboard.galleries.edit', $model->id);
            return '<a href="'.$editUrl.'" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>';
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);

    }

    
    public function edit($id){
        $gallery = Gallery::findOrFail($id);
        $clients = Client::where('photographer_id', Auth::id())->get();
        // dd('here');
        return view('dashboard.galleries.edit', compact('gallery','clients'));   
    }

    public function create()
    {
        $clients = Client::where('photographer_id', Auth::id())->get();
        return view('dashboard.galleries.create', compact('clients'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
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


        $slug = Str::slug($validated['name']) ?: Str::random(8);
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail') ->store("thumbnails/{$validated['client']}", 'local'); //change local later to wasabi or s3
        }

        Gallery::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'client_password' => Hash::make($validated['client_password']),
            'guest_password' => Hash::make($validated['guest_password']),
            'thumbnail_path' => $thumbnailPath,
            'client_id'=>$validated['client_id'],
        ]);

        return redirect()->route('dashboard.galleries.index', $validated['client_id'])
            ->with('success', 'Gallery created successfully.');
    }

    private function authorizeClient(Client $client)
    {
        abort_if($client->photographer_id !== Auth::id(), 403);
    }
}
