@csrf

<div class="mb-3">
    <label>Session Name: <span class="required_start">*</span></label>
    <input type="text" name="name" value="{{ old('name', $session->name ?? '') }}" class="input form-control" required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Client:</label>
    <select name="client_id" class="form-control select2" value="{{ old('client_id', $session->client_id ?? '') }}">
        <option value=""></option>
        @foreach ($clients as $client)
            <option value="{{ $client->id }}"
                {{ (old('client_id', $session->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                {{ $client->name }}
            </option>
        @endforeach
    </select>
    @error('client_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Phone: <span class="required_start">*</span></label>
    <input type="text" name="phone"  value="{{ old('phone', $session->phone ?? '') }}" class="input form-control"  required>
    @error('phone')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Date: <span class="required_start">*</span></label>
    <input type="text" name="date"  value="{{ old('date', $session->date ?? '') }}" class="input form-control"  required>
    @error('date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Total Amount:</label>
    <input type="text" name="total_amount"  value="{{ old('total_amount', $session->total_amount ?? '') }}" class="input form-control">
    @error('total_amount')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


<div class="mt-3 table-responsive">
    <br>
    <table id="tblAppendGrid"></table>    
    <input type="hidden" name="items" id="appendGridData">
</div>

@section('js')
    <script>
        var sessionFiance = @json(old('lines', $session->finance ?? []));

        $(document).ready(function(){
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

            // Convert sessionFiance into the required array of objects
            var rows = sessionFiance.map(function (line) {
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