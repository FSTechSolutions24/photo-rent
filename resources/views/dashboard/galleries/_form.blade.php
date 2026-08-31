@php
    $thumbnailPath = isset($gallery) ? $gallery->thumbnail_url : '';
    $backgroundPath = isset($gallery) ? $gallery->background_url : '';
@endphp

@csrf

<div class="mb-3">
    <label>Gallery Name:</label>
    <input type="text" name="name" value="{{ old('name', $gallery->name ?? '') }}" class="input form-control" required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="session_id">Session:</label>
    <select name="session_id" id="session_id" class="form-control select2">
        <option value="">No session assigned</option>
        @foreach ($sessions as $session)
            <option value="{{ $session->id }}" {{ old('session_id', $gallery->session_id ?? '') == $session->id ? 'selected' : '' }}>
                {{ $session->name }} — {{ \Illuminate\Support\Carbon::parse($session->date)->format('d M Y') }}
            </option>
        @endforeach
    </select>
    @error('session_id')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label>Thumbnail:</label>
    <input type="file" name="thumbnail_path" id="thumbnail_path" class="input form-control" accept="image/*">

    <div class="mt-3">
        <img id="thumbnailPreview" width="200" src="{{ $thumbnailPath }}" alt="Thumbnail Preview" class="img-fluid border rounded" style="max-width: 250px; {{ $thumbnailPath ? '' : 'display:none;' }}">
    </div>

    @error('thumbnail_path')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    @php($selectedLayout = old('gallery_layout', $gallery->gallery_layout ?? 'masonry'))
    <label>Client Gallery Theme: <span class="required_start">*</span></label>
    <small class="form-text text-muted mb-2">Choose the experience clients see when they open this gallery.</small>

    <div class="gallery-layout-picker" role="radiogroup" aria-label="Client gallery theme">
        <label class="gallery-layout-option {{ $selectedLayout === 'masonry' ? 'is-selected' : '' }}">
            <input type="radio" name="gallery_layout" value="masonry" {{ $selectedLayout === 'masonry' ? 'checked' : '' }} required>
            <span class="gallery-layout-preview gallery-layout-preview--dark" aria-hidden="true">
                <span class="gallery-layout-preview__hero"></span>
                <span class="gallery-layout-preview__nav"></span>
                <span class="gallery-layout-preview__photos"><i></i><i></i><i></i><i></i><i></i></span>
            </span>
            <span class="gallery-layout-option__copy">
                <strong>Dark Mosaic</strong>
                <small>Classic dark masonry gallery</small>
            </span>
            <span class="gallery-layout-option__check"><i class="fas fa-check"></i></span>
        </label>

        <label class="gallery-layout-option {{ $selectedLayout === 'editorial' ? 'is-selected' : '' }}">
            <input type="radio" name="gallery_layout" value="editorial" {{ $selectedLayout === 'editorial' ? 'checked' : '' }} required>
            <span class="gallery-layout-preview gallery-layout-preview--light" aria-hidden="true">
                <span class="gallery-layout-preview__hero"></span>
                <span class="gallery-layout-preview__nav"></span>
                <span class="gallery-layout-preview__photos"><i></i><i></i><i></i><i></i><i></i></span>
            </span>
            <span class="gallery-layout-option__copy">
                <strong>Light Editorial</strong>
                <small>Modern, airy photo story</small>
            </span>
            <span class="gallery-layout-option__check"><i class="fas fa-check"></i></span>
        </label>

        <label class="gallery-layout-option {{ $selectedLayout === 'luxe' ? 'is-selected' : '' }}">
            <input type="radio" name="gallery_layout" value="luxe" {{ $selectedLayout === 'luxe' ? 'checked' : '' }} required>
            <span class="gallery-layout-preview gallery-layout-preview--luxe" aria-hidden="true">
                <span class="gallery-layout-preview__hero"></span>
                <span class="gallery-layout-preview__nav"></span>
                <span class="gallery-layout-preview__photos"><i></i><i></i><i></i><i></i><i></i></span>
            </span>
            <span class="gallery-layout-option__copy">
                <strong>Luxe Frame</strong>
                <small>Immersive dark gallery experience</small>
            </span>
            <span class="gallery-layout-option__check"><i class="fas fa-check"></i></span>
        </label>
    </div>

    @error('gallery_layout')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="background_path">Gallery Background:</label>
    <input type="file" name="background_path" id="background_path" class="input form-control" accept="image/*">
    <small class="form-text text-muted">Used behind the title in the Editorial modern layout. A wide landscape image works best.</small>

    <div class="mt-3">
        <img id="backgroundPreview" src="{{ $backgroundPath }}" alt="Gallery background preview" class="img-fluid border rounded" style="width: 100%; max-width: 500px; max-height: 180px; object-fit: cover; {{ $backgroundPath ? '' : 'display:none;' }}">
    </div>

    @error('background_path')
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

<style>
        .gallery-layout-picker { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; max-width: 760px; }
        .gallery-layout-option { position: relative; display: block; overflow: hidden; border: 2px solid #e1e5ea; border-radius: .7rem; background: #fff; cursor: pointer; transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease; }
        .gallery-layout-option:hover { border-color: #9aa8b8; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(34, 55, 78, .1); }
        .gallery-layout-option.is-selected { border-color: #2878c8; box-shadow: 0 0 0 3px rgba(40, 120, 200, .12); }
        .gallery-layout-option input { position: absolute; opacity: 0; pointer-events: none; }
        .gallery-layout-preview { position: relative; display: block; height: 126px; padding: 11px; overflow: hidden; }
        .gallery-layout-preview--dark { background: #141414; } .gallery-layout-preview--light { background: #f6f5f0; } .gallery-layout-preview--luxe { background: #11100f; }
        .gallery-layout-preview__hero { display: block; height: 34px; border-radius: 2px; }
        .gallery-layout-preview--dark .gallery-layout-preview__hero { background: linear-gradient(105deg, #725b42, #252525 55%, #92917b); } .gallery-layout-preview--light .gallery-layout-preview__hero { background: linear-gradient(105deg, #c2cfb0, #e7e2d4 55%, #aabda3); } .gallery-layout-preview--luxe .gallery-layout-preview__hero { background: linear-gradient(105deg, #392d21, #ae9472 50%, #191817); }
        .gallery-layout-preview__nav { display: block; width: 55%; height: 7px; margin: 8px 0; border-radius: 99px; } .gallery-layout-preview--dark .gallery-layout-preview__nav { background: #4b4b4b; } .gallery-layout-preview--light .gallery-layout-preview__nav { background: #d9dfd2; } .gallery-layout-preview--luxe .gallery-layout-preview__nav { background: #a88a62; }
        .gallery-layout-preview__photos { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; height: 55px; } .gallery-layout-preview__photos i { display: block; border-radius: 2px; background: linear-gradient(135deg, #796555, #c2af97); } .gallery-layout-preview__photos i:nth-child(2) { height: 42px; background: linear-gradient(135deg, #8f9f92, #455449); } .gallery-layout-preview__photos i:nth-child(3) { height: 55px; background: linear-gradient(135deg, #b49070, #634d3e); } .gallery-layout-preview__photos i:nth-child(4) { height: 45px; background: linear-gradient(135deg, #9291a0, #4f5060); } .gallery-layout-preview__photos i:nth-child(5) { display: none; }
        .gallery-layout-preview--light .gallery-layout-preview__photos i { background: linear-gradient(135deg, #ddc5ac, #f3e7d7); } .gallery-layout-preview--light .gallery-layout-preview__photos i:nth-child(2) { background: linear-gradient(135deg, #bdccae, #eff0df); } .gallery-layout-preview--light .gallery-layout-preview__photos i:nth-child(3) { background: linear-gradient(135deg, #d6a891, #f1d9c9); } .gallery-layout-preview--light .gallery-layout-preview__photos i:nth-child(4) { background: linear-gradient(135deg, #b9cbd0, #e5eeee); } .gallery-layout-preview--luxe .gallery-layout-preview__photos i { border: 2px solid #ad936c; background: linear-gradient(135deg, #806852, #d4bb98); } .gallery-layout-preview--luxe .gallery-layout-preview__photos i:nth-child(2) { background: linear-gradient(135deg, #5a594a, #aaa88d); } .gallery-layout-preview--luxe .gallery-layout-preview__photos i:nth-child(3) { background: linear-gradient(135deg, #835f49, #d6ae8d); } .gallery-layout-preview--luxe .gallery-layout-preview__photos i:nth-child(4) { background: linear-gradient(135deg, #4d5356, #aab2ad); }
        .gallery-layout-option__copy { display: block; padding: .8rem 2.8rem .9rem 1rem; border-top: 1px solid #edf0f2; } .gallery-layout-option__copy strong { display: block; color: #26323d; font-size: .94rem; } .gallery-layout-option__copy small { display: block; margin-top: .2rem; color: #798694; font-size: .76rem; }
        .gallery-layout-option__check { position: absolute; right: .8rem; bottom: 1.15rem; display: grid; width: 1.45rem; height: 1.45rem; place-items: center; border: 1px solid #ccd5dc; border-radius: 50%; color: transparent; font-size: .68rem; } .gallery-layout-option.is-selected .gallery-layout-option__check { border-color: #2878c8; background: #2878c8; color: #fff; }
        @media (max-width: 575.98px) { .gallery-layout-picker { grid-template-columns: 1fr; } }
 </style>

<script>

    $(function () { $('#session_id').select2({ width: '100%' }); });
    const setImagePreview = (inputId, previewId) => {
        document.getElementById(inputId).addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById(previewId);
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    };

    setImagePreview('thumbnail_path', 'thumbnailPreview');
    setImagePreview('background_path', 'backgroundPreview');

    document.querySelectorAll('input[name="gallery_layout"]').forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.gallery-layout-option').forEach(option => option.classList.remove('is-selected'));
            input.closest('.gallery-layout-option').classList.add('is-selected');
        });
    });
</script>
