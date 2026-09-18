@csrf

@php
    $sessionDateValue = old('date');
    if ($sessionDateValue === null && isset($session) && $session->date) {
        $sessionDateValue = \Illuminate\Support\Carbon::parse($session->date)->format('Y-m-d\TH:i');
    }
@endphp

<div class="session-editor">
<div class="row">
    <div class="mb-3 col-md-6">
        <label>Session Name: <span class="required_start">*</span></label>
        <input type="text" name="name" value="{{ old('name', $session->name ?? '') }}" class="input form-control" required>
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label>Client:</label>
        <select name="client_id" id="sessionClient" class="form-control select2">
            <option value=""></option>
            @foreach ($clients as $client)
                <option value="{{ $client->id }}" data-phone="{{ $client->phone ?? '' }}"
                    {{ (old('client_id', $session->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
        @error('client_id')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row">
    <div class="mb-3 col-md-6">
        <label>Phone: <span class="required_start">*</span></label>
        <input type="text" name="phone" id="sessionPhone" value="{{ old('phone', $session->phone ?? '') }}" class="input form-control" required>
        @error('phone')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label for="sessionDate">Date and time: <span class="required_start">*</span></label>
        <input type="datetime-local" name="date" id="sessionDate" value="{{ $sessionDateValue }}" class="input form-control" step="60" required>
        @error('date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row">
    <div class="mb-3 col-md-6">
        <label>Total Amount:</label>
        <input type="text" name="total_amount" id="sessionTotalAmount" value="{{ old('total_amount', $session->total_amount ?? '') }}" class="input form-control">
        @error('total_amount')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="sessionNotes">Notes:</label>
    <textarea name="notes" id="sessionNotes" class="input form-control" rows="2" maxlength="5000" placeholder="Add any notes about this session...">{{ old('notes', $session->notes ?? '') }}</textarea>
    @error('notes')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="session-section-heading">
    <div>
        <span class="session-section-heading__eyebrow">Financial overview</span>
        <h2>Session balance</h2>
    </div>
    <span class="session-section-heading__hint"><i class="fas fa-sync-alt"></i> Updates as you edit</span>
</div>

<div class="session-finance-summary" aria-live="polite">
    <div class="session-finance-summary__item session-finance-summary__item--received">
        <div class="session-finance-summary__top">
            <span class="session-finance-summary__icon"><i class="fas fa-hand-holding-usd"></i></span>
            <span class="session-finance-summary__label">Received</span>
        </div>
        <strong id="financeReceived">0.00</strong>
        <small>Payments recorded</small>
    </div>
    <div class="session-finance-summary__item session-finance-summary__item--spent">
        <div class="session-finance-summary__top">
            <span class="session-finance-summary__icon"><i class="fas fa-receipt"></i></span>
            <span class="session-finance-summary__label">Spent</span>
        </div>
        <strong id="financeSpent">0.00</strong>
        <small>Session expenses</small>
    </div>
    <div class="session-finance-summary__item session-finance-summary__item--outstanding">
        <div class="session-finance-summary__top">
            <span class="session-finance-summary__icon"><i class="fas fa-clock"></i></span>
            <span class="session-finance-summary__label">Still to collect</span>
        </div>
        <strong id="financeOutstanding">0.00</strong>
        <small>Remaining balance</small>
    </div>
    <div class="session-finance-summary__item session-finance-summary__item--net">
        <div class="session-finance-summary__top">
            <span class="session-finance-summary__icon"><i class="fas fa-chart-line"></i></span>
            <span class="session-finance-summary__label" id="financeNetLabel">Profit / Loss</span>
        </div>
        <strong id="financeNet">0.00</strong>
        <small>Current net result</small>
    </div>
</div>

<div class="session-ledger">
    <div class="session-ledger__header">
        <div>
            <span class="session-section-heading__eyebrow">Transactions</span>
            <h2>Payments &amp; expenses</h2>
            <p>Add credits you receive and debits you spend for this session.</p>
        </div>
        <span class="session-ledger__badge"><i class="fas fa-list-ul"></i> Finance activity</span>
    </div>
    <div class="table-responsive session-ledger__table">
        <table id="tblAppendGrid"></table>
        <input type="hidden" name="items" id="appendGridData">
    </div>
</div>
</div>

@section('js')
    <style>
        .session-editor label {
            margin-bottom: .45rem;
            color: #30445a;
            font-size: .79rem;
            font-weight: 700;
        }
        .session-editor .form-control {
            min-height: 42px;
            border-color: #d9e2ec;
            border-radius: .55rem;
            background-color: #fbfdff;
            color: #26384b;
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }
        .session-editor textarea.form-control { min-height: 62px; }
        .session-editor .form-control:focus {
            border-color: #6da8e8;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(49, 126, 211, .11);
        }
        .session-section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1rem;
            margin: 1.65rem 0 .85rem;
        }
        .session-section-heading__eyebrow {
            display: block;
            margin-bottom: .2rem;
            color: #5280ad;
            font-size: .66rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .session-section-heading h2,
        .session-ledger__header h2 {
            margin: 0;
            color: #18364e;
            font-size: 1.05rem;
            font-weight: 750;
        }
        .session-section-heading__hint {
            color: #8292a3;
            font-size: .73rem;
            font-weight: 600;
        }
        .session-section-heading__hint i { margin-right: .3rem; color: #4f8ccc; }
        .session-finance-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
            margin: 0 0 1.4rem;
        }
        .session-finance-summary__item {
            --summary-accent: #397fc3;
            --summary-soft: #edf5fd;
            position: relative;
            min-width: 0;
            overflow: hidden;
            padding: 1rem 1.05rem .95rem;
            border: 1px solid #e1e9f1;
            border-radius: .85rem;
            background: linear-gradient(145deg, #fff 55%, var(--summary-soft));
            box-shadow: 0 8px 22px rgba(35, 63, 86, .07);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .session-finance-summary__item::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 3px;
            background: var(--summary-accent);
            content: '';
        }
        .session-finance-summary__item:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(35, 63, 86, .11);
        }
        .session-finance-summary__top { display: flex; align-items: center; gap: .55rem; margin-bottom: .75rem; }
        .session-finance-summary__icon {
            display: inline-grid;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            place-items: center;
            border-radius: .55rem;
            background: var(--summary-soft);
            color: var(--summary-accent);
            font-size: .75rem;
        }
        .session-finance-summary__label {
            overflow: hidden;
            color: #687b8d;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .session-finance-summary__item strong {
            display: block;
            overflow: hidden;
            color: #20384d;
            font-size: 1.45rem;
            font-weight: 750;
            letter-spacing: -.025em;
            line-height: 1.15;
            text-overflow: ellipsis;
        }
        .session-finance-summary__item small { display: block; margin-top: .35rem; color: #91a0af; font-size: .69rem; }
        .session-finance-summary__item--received { --summary-accent: #159263; --summary-soft: #e9f8f1; }
        .session-finance-summary__item--spent { --summary-accent: #df5360; --summary-soft: #fff0f1; }
        .session-finance-summary__item--outstanding { --summary-accent: #db920d; --summary-soft: #fff7e7; }
        .session-finance-summary__item--net { --summary-accent: #397fc3; --summary-soft: #edf5fd; }
        .session-finance-summary__item--received strong { color: #198754; }
        .session-finance-summary__item--spent strong { color: #dc3545; }
        .session-finance-summary__item--outstanding strong { color: #d98b00; }
        .session-finance-summary__item--net.is-profit strong { color: #198754; }
        .session-finance-summary__item--net.is-loss strong { color: #dc3545; }
        .session-finance-summary__item--net.is-zero strong { color: #6c757d; }
        .session-ledger {
            overflow: hidden;
            margin-top: 1.7rem;
            border: 1px solid #e1e9f1;
            border-radius: .9rem;
            background: #fff;
            box-shadow: 0 8px 24px rgba(35, 63, 86, .055);
        }
        .session-ledger__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.15rem 1.25rem;
            border-bottom: 1px solid #e7edf3;
            background: linear-gradient(135deg, #f8fbfe, #fff);
        }
        .session-ledger__header p { margin: .3rem 0 0; color: #8292a3; font-size: .75rem; }
        .session-ledger__badge {
            padding: .42rem .7rem;
            border-radius: 999px;
            background: #eaf3fc;
            color: #3979b7;
            font-size: .68rem;
            font-weight: 750;
            white-space: nowrap;
        }
        .session-ledger__badge i { margin-right: .3rem; }
        .session-ledger__table { padding: .35rem .75rem .9rem; }
        .session-ledger__table table { margin-bottom: .5rem; }
        .session-ledger__table thead th {
            border-top: 0;
            border-bottom: 1px solid #dfe7ef;
            background: #fbfcfe;
            color: #61758a;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .045em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .session-ledger__table tbody td { border-color: #e7edf3; vertical-align: middle; }
        .session-ledger__table .form-control { background: #fff; }
        .session-ledger__table .btn { min-width: 39px; min-height: 39px; border-color: #ccd8e4; color: #5c7185; }
        .session-ledger__table .btn:hover { border-color: #79a8d6; background: #edf5fc; color: #2469a8; }
        @media (max-width: 767.98px) {
            .session-finance-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .session-ledger__header { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 479.98px) {
            .session-finance-summary { grid-template-columns: 1fr; }
            .session-section-heading { align-items: flex-start; flex-direction: column; gap: .35rem; }
            .session-finance-summary__item strong { font-size: 1.35rem; }
        }
    </style>
    <script>
        var sessionFinance = @json(old('lines', $session->finance ?? []));

        $(document).ready(function(){
            $('#sessionClient').on('change', function () {
                var selectedOption = this.options[this.selectedIndex];
                $('#sessionPhone').val(selectedOption ? (selectedOption.dataset.phone || '') : '');
            });

            var myAppendGrid = new AppendGrid({
                element: "tblAppendGrid",
                uiFramework: "bootstrap4",
                iconFramework: "fontawesome5",
                columns: [
                    {
                        name: "credit_debit",
                        display: "Credit / Debit",
                        type: "select",
                        ctrlOptions: {
                            credit: "Credit",
                            debit: "Debit"
                        },
                        ctrlCss: {
                            "min-width": "120px"
                        }
                    },
                    { 
                        name: "name", 
                        display: "Name",
                        ctrlCss: {
                            "min-width": "120px"
                        }
                    },
                    {
                        name: "description",
                        display: "Description",
                        type: "textarea",
                        ctrlAttr: {
                            rows: 1
                        },
                        ctrlCss: {
                            "min-width": "120px"
                        }
                    },                    
                    {
                        name: "date",
                        display: "Date", 
                        type: "date",
                        value: new Date().toISOString().split('T')[0],
                    },
                    {
                        name: "amount",
                        display: "Amount",
                        ctrlCss: {
                            "min-width": "120px"
                        }
                    }
                ],
                initRows: 0
            });

            // Convert existing finance records into the required array of objects
            var rows = sessionFinance.map(function (line) {
                return {
                    name: line.name || "",
                    description: line.description || "",
                    credit_debit: line.credit_debit || "",
                    amount: line.amount || "",
                    date: line.date || "",
                };
            });

            // Append all rows at once
            myAppendGrid.appendRow(rows);

            function amount(value) {
                var parsed = parseFloat(String(value || '').replace(/,/g, ''));
                return isNaN(parsed) ? 0 : parsed;
            }

            function formatAmount(value) {
                return value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function updateFinanceSummary() {
                var received = 0;
                var spent = 0;

                myAppendGrid.getAllValue().forEach(function (row) {
                    if (row.credit_debit === 'credit') {
                        received += amount(row.amount);
                    } else if (row.credit_debit === 'debit') {
                        spent += amount(row.amount);
                    }
                });

                var outstanding = Math.max(0, amount($('#sessionTotalAmount').val()) - received);
                var net = received - spent;
                var isLoss = net < 0;
                var isProfit = net > 0;
                var netIcon = isProfit ? 'fa-arrow-up' : (isLoss ? 'fa-arrow-down' : 'fa-minus');

                $('#financeReceived').text(formatAmount(received));
                $('#financeSpent').text(formatAmount(spent));
                $('#financeOutstanding').text(formatAmount(outstanding));
                $('#financeNet').html('<i class="fas ' + netIcon + ' mr-1"></i>' + formatAmount(Math.abs(net)));
                $('.session-finance-summary__item--net')
                    .toggleClass('is-profit', isProfit)
                    .toggleClass('is-loss', isLoss)
                    .toggleClass('is-zero', !isProfit && !isLoss);
            }

            updateFinanceSummary();
            $('#sessionTotalAmount').on('input change', updateFinanceSummary);
            $('#tblAppendGrid').on('input change', 'input, select, textarea', updateFinanceSummary);
            $('#tblAppendGrid').on('click', 'button, a', function () {
                setTimeout(updateFinanceSummary, 0);
            });

            // When form is submitted → collect the grid data
            $("form").on("submit", function(e) {
                // 1. Get rows
                let rows = myAppendGrid.getAllValue();
                // 2. Get the internal row order
                let order = myAppendGrid.getRowOrder();
                // 3. Save them both
                $("#appendGridData").val(JSON.stringify({
                    rows: rows,
                    order: order
                }));
            });

        })

    </script>
@stop
