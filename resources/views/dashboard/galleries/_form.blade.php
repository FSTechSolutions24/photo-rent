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
            <option value="{{ $client->id }}"
                {{ (old('client_id', $gallery->client_id ?? '') == $client->id) ? 'selected' : '' }}>
                {{ $client->name }}
            </option>
        @endforeach
    </select>
    @error('client_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Thumbnail:</label>
    <input type="file" name="thumbnail_path" id="thumbnail_path" class="input form-control" value="{{ old('phone', $gallery->phone ?? '') }}">

    {{-- Preview Image --}}
    @php
        $thumbnailPath = isset($gallery) && $gallery->thumbnail_path ? asset('storage/' . $gallery->thumbnail_path) : '';
    @endphp

    <div class="mt-3">
        <img id="thumbnailPreview" width="200" src="{{ $thumbnailPath }}" alt="Thumbnail Preview" class="img-fluid border rounded" style="max-width: 250px; {{ $thumbnailPath ? '' : 'display:none;' }}">
    </div>

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


<script>
    document.getElementById('thumbnail_path').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('thumbnailPreview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>