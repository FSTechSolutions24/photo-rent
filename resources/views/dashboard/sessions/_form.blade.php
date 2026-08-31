@csrf

@php
    $sessionDateValue = old('date');
    if ($sessionDateValue === null && isset($session) && $session->date) {
        $sessionDateValue = \Illuminate\Support\Carbon::parse($session->date)->format('Y-m-d\TH:i');
    }
@endphp

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

<div class="session-finance-summary" aria-live="polite">
    <div class="session-finance-summary__item session-finance-summary__item--received">
        <span class="session-finance-summary__label"><i class="fas fa-hand-holding-usd"></i> Received</span>
        <strong id="financeReceived">0.00</strong>
    </div>
    <div class="session-finance-summary__item session-finance-summary__item--spent">
        <span class="session-finance-summary__label"><i class="fas fa-receipt"></i> Spent</span>
        <strong id="financeSpent">0.00</strong>
    </div>
    <div class="session-finance-summary__item session-finance-summary__item--outstanding">
        <span class="session-finance-summary__label"><i class="fas fa-clock"></i> Still to collect</span>
        <strong id="financeOutstanding">0.00</strong>
    </div>
    <div class="session-finance-summary__item session-finance-summary__item--net">
        <span class="session-finance-summary__label" id="financeNetLabel"><i class="fas fa-chart-line"></i> Profit / Loss</span>
        <strong id="financeNet">0.00</strong>
    </div>
</div>


<div class="mt-3 table-responsive">
    <br>
    <table id="tblAppendGrid"></table>    
    <input type="hidden" name="items" id="appendGridData">
</div>

@section('js')
    <style>
        .session-finance-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin: 1.25rem 0 1rem;
            overflow: hidden;
            border: 1px solid #e6eaf0;
            border-radius: .65rem;
            background: #fff;
            box-shadow: 0 5px 18px rgba(35, 50, 70, .07);
        }
        .session-finance-summary__item { padding: .85rem 1rem; border-right: 1px solid #e6eaf0; }
        .session-finance-summary__item:last-child { border-right: 0; }
        .session-finance-summary__label { display: block; margin-bottom: .3rem; color: #6c757d; font-size: .78rem; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; }
        .session-finance-summary__label i { margin-right: .25rem; }
        .session-finance-summary__item strong { display: block; color: #273142; font-size: 1.25rem; line-height: 1.15; }
        .session-finance-summary__item--received strong { color: #198754; }
        .session-finance-summary__item--spent strong { color: #dc3545; }
        .session-finance-summary__item--outstanding strong { color: #d98b00; }
        .session-finance-summary__item--net.is-profit strong { color: #198754; }
        .session-finance-summary__item--net.is-loss strong { color: #dc3545; }
        .session-finance-summary__item--net.is-zero strong { color: #6c757d; }
        @media (max-width: 767.98px) {
            .session-finance-summary { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .session-finance-summary__item:nth-child(2) { border-right: 0; }
            .session-finance-summary__item:nth-child(-n+2) { border-bottom: 1px solid #e6eaf0; }
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
