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