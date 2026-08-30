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
                        $query->whereDate('date', $now->toDateString())->where('start_time', '>=', $now->format('H:i:s'));
                    });
            })
            ->orderBy('date')->orderBy('start_time')->get();

        $nextAppointment = $nextAppointments->first();

        return view('dashboard', compact('counts', 'summary', 'nextAppointment', 'nextAppointments'));
    }
}
