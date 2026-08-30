<?php

namespace App\Http\Controllers;

use App\Models\SessionFinance;
use Illuminate\Http\Request;

class FinanceReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'transaction_type' => ['nullable', 'in:all,credit,debit'],
            'session_ids' => ['nullable', 'array'],
            'session_ids.*' => ['integer'],
            'client_ids' => ['nullable', 'array'],
            'client_ids.*' => ['integer'],
        ]);

        $photographer = $request->user()->photographer;
        $sessions = $photographer->sessions()->with('client')->orderByDesc('date')->get();
        $clients = $photographer->clients()->orderBy('name')->get();

        $sessionQuery = $photographer->sessions()->with('client');

        if (!empty($filters['date_from'])) {
            $sessionQuery->whereDate('date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $sessionQuery->whereDate('date', '<=', $filters['date_to']);
        }

        if (!empty($filters['session_ids'])) {
            $sessionQuery->whereIn('id', $filters['session_ids']);
        }

        if (!empty($filters['client_ids'])) {
            $sessionQuery->whereIn('client_id', $filters['client_ids']);
        }

        if (($filters['transaction_type'] ?? 'all') !== 'all') {
            $sessionQuery->whereHas('finance', function ($query) use ($filters) {
                $query->where('credit_debit', $filters['transaction_type']);
            });
        }

        $reportSessions = $sessionQuery->orderByDesc('date')->get();
        $reportSessionIds = $reportSessions->pluck('id');

        // Keep totals accurate even when the table is filtered to one transaction type.
        $allFinanceRecords = SessionFinance::whereIn('session_id', $reportSessionIds)
            ->orderByDesc('date')->orderByDesc('id')->get();

        $financeRecords = ($filters['transaction_type'] ?? 'all') === 'all'
            ? $allFinanceRecords
            : $allFinanceRecords->where('credit_debit', $filters['transaction_type']);
        $financeBySession = $financeRecords->groupBy('session_id');
        $allFinanceBySession = $allFinanceRecords->groupBy('session_id');

        $sessionTotal = $reportSessions->sum('total_amount');
        $received = $allFinanceRecords->where('credit_debit', 'credit')->sum('amount');
        $spent = $allFinanceRecords->where('credit_debit', 'debit')->sum('amount');

        $summary = [
            'received' => (float) $received,
            'spent' => (float) $spent,
            'outstanding' => max(0, (float) $sessionTotal - (float) $received),
            'net' => (float) $received - (float) $spent,
            'session_total' => (float) $sessionTotal,
        ];

        return view('dashboard.finance-report.index', compact('sessions', 'clients', 'reportSessions', 'financeBySession', 'allFinanceBySession', 'summary'));
    }
}
