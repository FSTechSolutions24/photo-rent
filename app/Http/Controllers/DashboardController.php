<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\SessionFinance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $photographer = $request->user()->photographer;
        $now = Carbon::now();

        $counts = [
            'clients' => $photographer->clients()->count(),
            'sessions' => $photographer->sessions()->count(),
            'galleries' => $photographer->galleries()->count(),
        ];

        $finance = SessionFinance::whereHas('session', function ($query) use ($photographer) {
            $query->where('photographer_id', $photographer->id);
        })->selectRaw("COALESCE(SUM(CASE WHEN credit_debit = 'credit' THEN amount ELSE 0 END), 0) as received")
            ->selectRaw("COALESCE(SUM(CASE WHEN credit_debit = 'debit' THEN amount ELSE 0 END), 0) as spent")
            ->first();

        $totalAmount = (float) $photographer->sessions()->sum('total_amount');
        $received = (float) $finance->received;
        $spent = (float) $finance->spent;
        $summary = [
            'total_amount' => $totalAmount,
            'received' => $received,
            'spent' => $spent,
            'outstanding' => max(0, $totalAmount - $received),
            'net' => $received - $spent,
        ];

        $nextAppointments = Appointment::with('session.client')
            ->where(function ($query) use ($photographer) {
                $query->where('photographer_id', $photographer->id)
                    ->orWhereHas('session', function ($query) use ($photographer) {
                        $query->where('photographer_id', $photographer->id);
                    });
            })
            ->where(function ($query) use ($now) {
                $query->whereDate('date', '>', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query->whereDate('date', $now->toDateString())
                            ->where(function ($query) use ($now) {
                                $query->whereNull('start_time')
                                    ->orWhere('start_time', '>=', $now->format('H:i:s'));
                            });
                    });
            })
            ->get()
            ->map(function ($appointment) {
                $date = Carbon::parse($appointment->date)->format('Y-m-d');
                $startsAt = $appointment->start_time
                    ? Carbon::parse($date . ' ' . $appointment->start_time)
                    : Carbon::parse($date)->startOfDay();

                return [
                    'name' => $appointment->name,
                    'starts_at' => $startsAt,
                    'time_label' => $appointment->start_time
                        ? $appointment->start_time . ($appointment->end_time ? ' - ' . $appointment->end_time : '')
                        : 'All day',
                    'subtitle' => $appointment->session->name ?? 'No session assigned',
                    'edit_url' => route('photographer.appointments.edit', $appointment->id),
                    'type' => 'appointment',
                ];
            });

        $nextSessions = $photographer->sessions()
            ->with('client')
            ->where('date', '>=', $now->format('Y-m-d H:i:s'))
            ->get()
            ->map(function ($session) {
                $startsAt = Carbon::parse($session->date);

                return [
                    'name' => $session->name,
                    'starts_at' => $startsAt,
                    'time_label' => $startsAt->format('H:i'),
                    'subtitle' => $session->client->name ?? 'Session',
                    'edit_url' => route('dashboard.sessions.edit', $session->id),
                    'type' => 'session',
                ];
            });

        $upcomingMeetings = $nextAppointments
            ->concat($nextSessions)
            ->sortBy(function ($meeting) {
                return $meeting['starts_at']->getTimestamp();
            })
            ->values();
        $nextMeeting = $upcomingMeetings->first();

        return view('dashboard', compact('counts', 'summary', 'nextMeeting', 'upcomingMeetings'));
    }
}
