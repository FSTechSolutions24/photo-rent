<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Auth::user()->photographer->clients;
        return view('dashboard.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('dashboard.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        Auth::user()->clients()->create($validated);

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
        abort_if($client->photographer_id !== Auth::id(), 403);
    }
}
