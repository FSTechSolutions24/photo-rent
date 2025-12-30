@csrf

<div class="mb-3">
    <label>Gallery Name:</label>
    <input type="text" name="name" value="{{ old('name', $gallery->name ?? '') }}" class="input form-control" required>
    @error('name')
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

<div class="mb-3 form-check d-flex align-items-center">

    <input type="hidden" name="is_public" value="0">

    <input type="checkbox" name="is_public" value="1" id="is_public" class="form-check-input me-2" {{ old('is_public', $gallery->is_public ?? 0) == 1 ? 'checked' : '' }}>

    <label for="is_public" class="form-check-label mt-1 ml-1">Is Public</label>

    @error('is_public')
        <small class="text-danger d-block">{{ $message }}</small>
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