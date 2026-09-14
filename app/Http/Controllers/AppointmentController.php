<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $sessions = auth()->user()->photographer->sessions;
        return view('dashboard.calendar.create', compact('sessions'));
    }

    public function edit($id){
        $photographer = auth()->user()->photographer;
        $appointment = $photographer->appointments()->findOrFail($id);
        $sessions = $photographer->sessions;
        
        return view('dashboard.calendar.edit', compact('appointment','sessions'));   
    }

    public function store(Request $request)
    {
        $data = $this->validateAppointment($request);
        $data['photographer_id'] = auth()->user()->photographer->id;

        Appointment::create($data);

        return redirect()->route('photographer.appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function update(Request $request, Appointment $appointment)
    {
        abort_unless((int) $appointment->photographer_id === (int) auth()->user()->photographer->id, 403);
        $data = $this->validateAppointment($request, $appointment->id);
        $data['photographer_id'] = auth()->user()->photographer->id;

        $appointment->update($data);

        return redirect()->route('photographer.appointments.index')->with('Appointment', 'Plan updated successfully.');
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
                'integer',
                Rule::exists('sessions', 'id')->where('photographer_id', auth()->user()->photographer->id),
            ],
            'start_time' => [
                'nullable',
                'required_with:end_time',
                'date_format:H:i',
            ],
            'end_time' => [
                'nullable',
                'date_format:H:i',
                'after:start_time',
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

    public function getData(){
        $photographer = auth()->user()->photographer;

        $appointments = $photographer->appointments()
            ->select('id', 'name', 'date', 'start_time', 'end_time')
            ->get()
            ->map(function ($appointment) {
                $hasStartTime = ! empty($appointment->start_time);

                return [
                    'id' => 'appointment-' . $appointment->id,
                    'title' => $appointment->name,
                    'start' => $hasStartTime
                        ? $appointment->date . 'T' . $appointment->start_time
                        : $appointment->date,
                    'end' => $appointment->end_time
                        ? $appointment->date . 'T' . $appointment->end_time
                        : null,
                    'allDay' => ! $hasStartTime,
                    'backgroundColor' => '#073b74',
                    'borderColor' => '#073b74',
                    'textColor' => '#ffffff',
                    'editUrl' => route('photographer.appointments.edit', $appointment->id),
                ];
            });

        $sessions = $photographer->sessions()
            ->select('id', 'name', 'date')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => 'session-' . $session->id,
                    'title' => $session->name,
                    'start' => Carbon::parse($session->date)->format('Y-m-d\TH:i:s'),
                    'allDay' => false,
                    'backgroundColor' => '#198754',
                    'borderColor' => '#198754',
                    'textColor' => '#ffffff',
                    'editUrl' => route('dashboard.sessions.edit', $session->id),
                ];
            });

        return $appointments->concat($sessions)->values();
    }

}
