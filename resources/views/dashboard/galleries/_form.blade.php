@csrf

<div class="mb-3">
    <label>Gallery Name:</label>
    <input type="text" name="name" value="{{ old('name', $gallery->name ?? '') }}" class="input form-control" required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Client:</label>
    <select name="client_id" class="form-control select2" value="{{ old('client_id', $gallery->client_id ?? '') }}">
        <option value=""></option>
        @foreach ($clients as $client)
            <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
        @endforeach
    </select>
    @error('client_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Thumbnail:</label>
    <input type="file" name="thumbnail_path" class="input form-control" value="{{ old('phone', $gallery->phone ?? '') }}">
    @error('thumbnail_path')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Client Password:</label>
    <input type="text" name="client_password" value="{{ old('client_password', $gallery->client_password ?? '') }}" class="input form-control" required>
    @error('client_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Guest Password:</label>
    <input type="text" name="guest_password"  value="{{ old('guest_password', $gallery->guest_password ?? '') }}" class="input form-control"  required>
    @error('guest_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>