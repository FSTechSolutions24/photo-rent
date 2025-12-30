<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gallery;
use App\Models\Session;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SessionController extends Controller
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
        return view('dashboard.sessions.index');
    }

    public function getData(){
    
        $photographer = Auth::user()->photographer;
        $sessions = $photographer ? $photographer->sessions()->with('client')->get() : collect();

        return DataTables::of($sessions)
        ->addColumn('actions', function ($model) {

            $editUrl = route('dashboard.sessions.edit', $model->id);

            $buffer = '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>';
            
            return $buffer;
        })
        // Add a client_name column instead of overriding client_id
        ->editColumn('date', function ($model) {
            return Carbon::parse($model->date)->format('d/m/Y H:i');
        })
        ->addColumn('client_name', function ($model) {
            return $model->client ? $model->client->name : '-';
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);

    }
    
    public function edit($id){
        $session = Session::findOrFail($id);
        $clients = auth()->user()->photographer->clients;
        return view('dashboard.sessions.edit', compact('session','clients'));   
    }

    protected function validateSession(Request $request, $id = null){
        $photographer_id = Photographer::where('user_id', Auth::id())->first()->id;
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'regex:/^(?:\+20|0)?1[0125][0-9]{8}$/',
                Rule::unique('clients', 'phone')->where('photographer_id', Auth::id())->ignore($id, 'id'),
            ],
            'date' => ['required', 'date_format:Y-m-d H:i:s'],
            'client_id' => [
                'nullable',
                'integer',
                Rule::exists('clients', 'id')->where('photographer_id', $photographer_id),
            ],
            'total_amount' => ['nullable','numeric','regex:/^\d+(\.\d{1,2})?$/'],
        ]);
    }

    public function create()
    {
        $clients = auth()->user()->photographer->clients;
        return view('dashboard.sessions.create', compact('clients'));
    }

    public function update(Request $request, Session $session){
        $data = $this->validateSession($request);

        $session->update($data);
        
        return redirect()->route('dashboard.sessions.index')->with('success', 'Session updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $this->validateSession($request);

        $data['photographer_id'] = Photographer::where('user_id', Auth::id())->first()->id;
        
        Session::create($data);

        return redirect()->route('dashboard.sessions.index', $data['client_id'])->with('success', 'Session created successfully.');
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

}
