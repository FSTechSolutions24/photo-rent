<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Appointment;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class AppointmentController extends Controller
{
    public function index()
    {
        return view('dashboard.calendar.preview');
    }

    public function create()
    {
        return view('dashboard.calendar.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateAppointment($request);

        Appointment::create($data);

        return redirect()->route('photographer.appointments.index')->with('success', 'Appointment created successfully.');
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

    protected function validateAppointment(Request $request, $id = null)
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ], 
            'description' => [
                'nullable',   // because textarea can be empty
                'string',     // ensures valid text input
            ],   
            'date' => [
                'required',
                'date', // ensures a valid date format
            ],
            'session_id' => [
                'nullable',
                'integer', // assuming session_id references an ID in sessions table
                Rule::exists('sessions', 'id') // checks if session_id exists in sessions table
            ],
            'start_time' => [
                'required',
                'date_format:H:i:s', // ensures time format like 14:30:00
            ],
            'end_time' => [
                'nullable',
                'date_format:H:i:s', // optional, but must be in H:i:s format if provided
                'after:start_time', // ensures end_time is after start_time if provided
            ],
        ]);
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

    public function profile_settings(){
        return view('dashboard.profile.settings');
    }

    public function calendar(){
        return view('dashboard.calendar.preview');
    }

    public function appointment_booking(){
        return view('dashboard.calendar.appointment_booking');
    }

}
