@extends('adminlte::page')

@section('title', 'Finance Report')

@section('content')
    <x-page-header title="Finance Report" description="Review income, expenses, outstanding balances, and performance over time."
        :breadcrumbs="[['label' => 'Finance Report']]" :action-url="route('dashboard.sessions.index')"
        action-label="View sessions" action-icon="fas fa-camera" />

    <div class="card card-outline card-primary">
        <div class="card-body">
            <form method="GET" action="{{ route('dashboard.finance-report.index') }}">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="dateFrom">From date</label>
                        <input id="dateFrom" type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="dateTo">To date</label>
                        <input id="dateTo" type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="transactionType">Transaction type</label>
                        <select id="transactionType" name="transaction_type" class="form-control">
                            <option value="all" {{ request('transaction_type', 'all') === 'all' ? 'selected' : '' }}>All transactions</option>
                            <option value="credit" {{ request('transaction_type') === 'credit' ? 'selected' : '' }}>Credit / received only</option>
                            <option value="debit" {{ request('transaction_type') === 'debit' ? 'selected' : '' }}>Debit / spent only</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group d-flex align-items-end">
                        <button class="btn btn-primary mr-2" type="submit"><i class="fas fa-filter"></i> Apply filters</button>
                        <a href="{{ route('dashboard.finance-report.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="sessionIds">Sessions</label>
                        <select id="sessionIds" name="session_ids[]" class="form-control select2" multiple data-placeholder="All sessions">
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" {{ in_array($session->id, request('session_ids', [])) ? 'selected' : '' }}>
                                    #{{ $session->id }} — {{ $session->name }}{{ $session->client ? ' (' . $session->client->name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="clientIds">Clients</label>
                        <select id="clientIds" name="client_ids[]" class="form-control select2" multiple data-placeholder="All clients">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ in_array($client->id, request('client_ids', [])) ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        $summaryNetClass = $summary['net'] > 0 ? 'is-profit' : ($summary['net'] < 0 ? 'is-loss' : 'is-zero');
        $summaryNetIcon = $summary['net'] > 0 ? 'fa-arrow-up' : ($summary['net'] < 0 ? 'fa-arrow-down' : 'fa-minus');
    @endphp
    <div class="finance-summary" aria-label="Finance totals">
        <div class="finance-summary__item finance-summary__item--received"><span><i class="fas fa-hand-holding-usd"></i> Received</span><strong>{{ number_format($summary['received'], 2) }}</strong></div>
        <div class="finance-summary__item finance-summary__item--spent"><span><i class="fas fa-receipt"></i> Spent</span><strong>{{ number_format($summary['spent'], 2) }}</strong></div>
        <div class="finance-summary__item finance-summary__item--outstanding"><span><i class="fas fa-clock"></i> Still to collect</span><strong>{{ number_format($summary['outstanding'], 2) }}</strong></div>
        <div class="finance-summary__item finance-summary__item--net {{ $summaryNetClass }}"><span><i class="fas fa-chart-line"></i> Profit / Loss</span><strong><i class="fas {{ $summaryNetIcon }} mr-1"></i>{{ number_format(abs($summary['net']), 2) }}</strong></div>
    </div>
    <p class="text-muted small mb-3">Session total in this report: <strong>{{ number_format($summary['session_total'], 2) }}</strong>. Use the arrow beside a session to view its finance details.</p>

    <div class="ibox-content table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th></th><th>Session</th><th>Session date</th><th>Client</th><th class="text-right">Total amount</th><th class="text-right">Received</th><th class="text-right">Spent</th><th class="text-right">Still to collect</th><th>Profit / Loss</th></tr>
            </thead>
            <tbody>
                @forelse ($reportSessions as $session)
                    @php
                        $sessionFinance = $financeBySession->get($session->id, collect());
                        $allSessionFinance = $allFinanceBySession->get($session->id, collect());
                        $received = (float) $allSessionFinance->where('credit_debit', 'credit')->sum('amount');
                        $spent = (float) $allSessionFinance->where('credit_debit', 'debit')->sum('amount');
                        $outstanding = max(0, (float) $session->total_amount - $received);
                        $net = $received - $spent;
                    @endphp
                    <tr>
                        <td><button class="btn btn-sm btn-light finance-toggle" type="button" data-toggle="collapse" data-target="#finance-details-{{ $session->id }}" aria-expanded="false" aria-controls="finance-details-{{ $session->id }}"><i class="fas fa-chevron-right"></i></button></td>
                        <td><a href="{{ route('dashboard.sessions.edit', $session->id) }}">{{ $session->name }}</a></td>
                        <td>{{ $session->date ? \Illuminate\Support\Carbon::parse($session->date)->format('d M Y') : '-' }}</td>
                        <td>{{ $session->client->name ?? '-' }}</td>
                        <td class="text-right">{{ number_format($session->total_amount, 2) }}</td>
                        <td class="text-right text-success font-weight-bold">{{ number_format($received, 2) }}</td>
                        <td class="text-right text-danger font-weight-bold">{{ number_format($spent, 2) }}</td>
                        <td class="text-right text-warning font-weight-bold">{{ number_format($outstanding, 2) }}</td>
                        <td class="font-weight-bold finance-net {{ $net > 0 ? 'finance-net--profit' : ($net < 0 ? 'finance-net--loss' : 'finance-net--zero') }}">
                            <i class="fas {{ $net > 0 ? 'fa-arrow-up' : ($net < 0 ? 'fa-arrow-down' : 'fa-minus') }} mr-1"></i>{{ number_format(abs($net), 2) }}
                        </td>
                    </tr>
                    <tr class="finance-detail-row">
                        <td colspan="9" class="p-0 border-0">
                            <div id="finance-details-{{ $session->id }}" class="collapse finance-detail-collapse">
                                <div class="finance-detail-panel">
                                    <div class="small font-weight-bold text-muted mb-2">FINANCE DETAILS</div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead><tr><th>Date</th><th>Type</th><th>Name</th><th>Description</th><th class="text-right">Amount</th></tr></thead>
                                            <tbody>
                                                @forelse ($sessionFinance as $record)
                                                    <tr><td>{{ $record->date ? \Illuminate\Support\Carbon::parse($record->date)->format('d M Y') : '-' }}</td><td><span class="badge badge-{{ $record->credit_debit === 'credit' ? 'success' : 'danger' }}">{{ ucfirst($record->credit_debit) }}</span></td><td>{{ $record->name ?: '-' }}</td><td>{{ $record->description ?: '-' }}</td><td class="text-right font-weight-bold">{{ number_format($record->amount, 2) }}</td></tr>
                                                @empty
                                                    <tr><td colspan="5" class="text-center text-muted">No finance entries match the selected transaction type.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No sessions match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@stop

@section('css')
    <style>
        .finance-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); margin:1.25rem 0 .5rem; overflow:hidden; border:1px solid #e6eaf0; border-radius:.65rem; background:#fff; box-shadow:0 5px 18px rgba(35,50,70,.07); }
        .finance-summary__item { padding:1rem 1.1rem; border-right:1px solid #e6eaf0; }
        .finance-summary__item:last-child { border-right:0; }
        .finance-summary__item span { display:block; margin-bottom:.35rem; color:#6c757d; font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.03em; }
        .finance-summary__item strong { font-size:1.35rem; color:#273142; }
        .finance-summary__item--received strong,.finance-summary__item--net.is-profit strong { color:#198754; }.finance-summary__item--spent strong,.finance-summary__item--net.is-loss strong { color:#dc3545; }.finance-summary__item--outstanding strong { color:#d98b00; }.finance-summary__item--net.is-zero strong { color:#6c757d; }
        .table td.finance-net { font-weight:700 !important; }
        .table td.finance-net--profit { color:#198754 !important; }
        .table td.finance-net--loss { color:#dc3545 !important; }
        .table td.finance-net--zero { color:#6c757d !important; }
        .finance-toggle { width:2rem; color:#54606e; }.finance-toggle .fa-chevron-right { transition:transform .2s ease; }.finance-toggle[aria-expanded="true"] .fa-chevron-right { transform:rotate(90deg); }
        .finance-detail-panel { margin:0 .75rem .75rem; padding:1rem; border:1px solid #dfe6ed; border-top:3px solid #4b8df8; border-radius:.35rem; background:#f8fafc; }
        @media (max-width:767.98px) { .finance-summary { grid-template-columns:repeat(2,minmax(0,1fr)); }.finance-summary__item:nth-child(2) { border-right:0; }.finance-summary__item:nth-child(-n+2) { border-bottom:1px solid #e6eaf0; } }
    </style>
@stop

@section('js')
    <script>$(function () { $('.select2').select2({ width: '100%' }); });</script>
@stop
