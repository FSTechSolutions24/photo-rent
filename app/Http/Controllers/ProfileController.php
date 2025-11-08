<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class ProfileController extends Controller
{
    public function index()
    {
        
    }

    public function create()
    {
        $user = auth()->user();

        // Check if user has photographer relation
        if ($user->photographer) {
            return redirect()->route('dashboard'); // or 'dashboard.index' depending on your route name
        }

        return view('dashboard.profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'phone' => ['required','regex:/^(?:\+20|0)?1[0125][0-9]{8}$/'],
            'phone2' => ['nullable','regex:/^(?:\+20|0)?1[0125][0-9]{8}$/'],            
            'email' => 'nullable|email',
        ]);

        $validated['photographer_id'] = Auth::id();

        Client::create($validated);

        return redirect()->route('dashboard.clients.index')->with('success', 'Client added successfully.');
    }

    public function show(Client $client)
    {
        $this->authorizeClient($client);
        $galleries = $client->galleries;
        return view('dashboard.clients.show', compact('client', 'galleries'));
    }

    private function authorizeClient(Client $client)
    {
        
    }

    public function checksubdomain($subdomain = null){
        if(!$subdomain){
            return response()->json([
                'subdomain_empty' => true,
                'message' => 'Please fill your subdomain.'
            ]);
        }
        $exists = Photographer::where('subdomain', $subdomain)->exists();
         if ($exists) {
            return response()->json([
                'available' => false,
                'message' => 'Taken, please try another.'
            ]);
        } else {
            return response()->json([
                'available' => true,
                'message' => 'Available!'
            ]);
        }
    }
}
