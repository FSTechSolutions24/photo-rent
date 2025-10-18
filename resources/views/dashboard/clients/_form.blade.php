@csrf


<div class="mb-3">
    <label class="form-label">Client Name: <span class="required_start">*</span></label>
    <input type="text" name="name" value="{{ old('name', $client->name ?? '') }}" class="input form-control" required autocomplete="false">
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Client Phone: <span class="required_start">*</span></label>
    <input type="text" name="phone" value="{{ old('phone', $client->phone ?? '') }}" class="input form-control" required autocomplete="false">
    @error('phone')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Client Phone2:</label>
    <input type="text" name="phone2" value="{{ old('phone2', $client->phone2 ?? '') }}" class="input form-control" autocomplete="false">
    @error('phone2')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Client Email:</label>
    <input type="text" name="email" value="{{ old('email', $client->email ?? '') }}" class="input form-control" autocomplete="false">
    @error('email')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
    
