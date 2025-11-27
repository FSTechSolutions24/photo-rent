@csrf

<div id="app">
    <div class="mb-3">
        <label class="form-label">Plan Name: <span class="required_start">*</span></label>
        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="input form-control" required autocomplete="false">
        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Plan Price: <span class="required_start">*</span></label>
        <input type="text" name="price" value="{{ old('price', $plan->price ?? '') }}" class="input form-control" required autocomplete="false">
        @error('price')
            <small class="text-danger">{{ $message }}</small>
        @enderror

        <br>
        <table id="tblAppendGrid"></table>    
        <input type="hidden" name="items" id="appendGridData">
    </div>
</div>

@section('js')
    <script>
        var planLines = @json(old('lines', $plan->lines ?? []));

        $(document).ready(function(){
            var myAppendGrid = new AppendGrid({
                element: "tblAppendGrid",
                uiFramework: "bootstrap4",
                iconFramework: "fontawesome5",
                columns: [
                    { name: "feature_name", display: "Feature Name" },
                    { name: "description", display: "Description" },
                    {
                        name: "is_included",
                        display: "Status",
                        type: "checkbox",
                        ctrlOptions: { on: "1", off: "0" }
                    }
                ],
                initRows: 0
            });

            // Convert planLines into the required array of objects
            var rows = planLines.map(function (line) {
                return {
                    feature_name: line.feature_name || "",
                    description: line.description || "",
                    is_included: line.is_included == 1 ? 1 : 0
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