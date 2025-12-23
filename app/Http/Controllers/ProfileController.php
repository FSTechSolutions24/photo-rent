<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Photographer;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



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

        $plans = SubscriptionPlan::with('lines')->get();

        return view('dashboard.profile.create', compact('plans'));
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

    public function checksubdomain(Request $request)
    {
        $subdomain = $request->query('subdomain');
        
        if (!$subdomain) {
            return response()->json([
                'subdomain_empty' => true,
                'message' => 'Please fill your subdomain.'
            ]);
        }

        // Reject if contains slashes or spaces
        if (preg_match('/[^A-Za-z0-9-]/', $subdomain)) {
            return response()->json([
                'invalid_format' => true,
                'message' => 'Invalid subdomain format. Only letters, numbers, and hyphens are allowed. No spaces, slashes, or special characters.'
            ]);
        }

        // Check if it starts or ends with a hyphen
        if (str_starts_with($subdomain, '-') || str_ends_with($subdomain, '-')) {
            return response()->json([
                'invalid_format' => true,
                'message' => 'Subdomain cannot start or end with a hyphen.'
            ]);
        }

        // Check if subdomain already exists
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

    public function createphotographerprofile(Request $request)
    {

        $subdomain = $request->query('subdomain');
        $selectedPlan = $request->query('selectedPlan');

        $plan = SubscriptionPlan::where('id', $selectedPlan)->firstOrFail();

        $photographer = Photographer::create([
            'user_id' => Auth::user()->id,
            'plan_storage' => ($plan->storage_gb * 1024 * 1024 * 1024),
            'subdomain' => $subdomain,
        ]);

        return response()->json([
            'success' => true,
        ]);

    }

}
