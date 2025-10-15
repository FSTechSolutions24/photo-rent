<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


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

    public function getData(){

        $eloquent = Client::query();

        return DataTables::eloquent($eloquent)
        ->addIndexColumn()
        ->make(true);
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
        abort_if($client->photographer_id !== Auth::id(), 403);
    }
}
