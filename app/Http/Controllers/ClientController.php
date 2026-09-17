<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function index()
    {
        return view('dashboard.clients.index');
    }

    public function create()
    {
        return view('dashboard.clients.create');
    }

    public function getData(){
        $eloquent = Auth::user()->photographer->clients()->getQuery();

        return DataTables::eloquent($eloquent)
         ->addColumn('actions', function ($model) {
            $editUrl = route('dashboard.clients.edit', $model->id);
            return '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>';
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function edit($id){
        $client = Auth::user()->photographer->clients()->findOrFail($id);
        return view('dashboard.clients.edit', compact('client'));   
    }

    public function store(Request $request)
    {
        $data = $this->validateClient($request);

        $data['photographer_id'] = Photographer::where('user_id', Auth::id())->first()->id;
        Client::create($data);

        return redirect()->route('dashboard.clients.index')->with('success', 'Client created successfully.');
    }

    public function update(Request $request, Client $client)
    {
        $this->authorizeClient($client);
        $data = $this->validateClient($request, $client->id);

        $client->update($data);

        return redirect()->route('dashboard.clients.index')->with('success', 'Client updated successfully.');
    }
    

    protected function validateClient(Request $request, $id = null)
    {
        $photographerId = Auth::user()->photographer->id;

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'name')->where('photographer_id', $photographerId)->ignore($id, 'id'),
            ],
            'phone' => [
                'required',
                'regex:/^(?:\+20|0)?1[0125][0-9]{8}$/',
                Rule::unique('clients', 'phone')->where('photographer_id', $photographerId)->ignore($id, 'id'),
            ],
            'phone2' => [
                'nullable',
                'regex:/^(?:\+20|0)?1[0125][0-9]{8}$/',
                Rule::unique('clients', 'phone2')->where('photographer_id', $photographerId)->ignore($id, 'id'),
            ],          
            'email' => [
                'nullable',
                'email',
                'not_regex:/[\r\n]/',
                'max:255',
                Rule::unique('clients', 'email')->where('photographer_id', $photographerId)->ignore($id),
            ],
        ]);
    }

    public function show(Client $client)
    {
        $this->authorizeClient($client);
        $galleries = $client->galleries;
        return view('dashboard.clients.show', compact('client', 'galleries'));
    }

    private function authorizeClient(Client $client)
    {
        abort_unless((int) $client->photographer_id === (int) Auth::user()->photographer->id, 403);
    }
}
