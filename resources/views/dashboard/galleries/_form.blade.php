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
    <input type="file" name="thumbnail_path" id="thumbnail_path" class="input form-control" accept=".jpg,.jpeg,.png,.webp">

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
        <label class="gallery-layout-option {{ $selectedLayout === 'masonry' ? 'is-selected' : '' }}" for="gallery-layout-masonry">
            <input id="gallery-layout-masonry" type="radio" name="gallery_layout" value="masonry" {{ $selectedLayout === 'masonry' ? 'checked' : '' }} required>
            <span class="gallery-layout-preview gallery-layout-preview--masonry" aria-hidden="true">
                <span class="gallery-layout-preview__hero">
                    <i class="preview-kicker"></i><i class="preview-title"></i><i class="preview-meta"></i>
                </span>
                <span class="gallery-layout-preview__nav"><i></i><i></i><i></i></span>
                <span class="gallery-layout-preview__photos"><i></i><i></i><i></i><i></i><i></i></span>
            </span>
            <span class="gallery-layout-option__copy">
                <strong>Dark Mosaic</strong>
                <small>Classic dark masonry gallery</small>
            </span>
            <span class="gallery-layout-option__check"><i class="fas fa-check"></i></span>
        </label>

        <label class="gallery-layout-option {{ $selectedLayout === 'editorial' ? 'is-selected' : '' }}" for="gallery-layout-editorial">
            <input id="gallery-layout-editorial" type="radio" name="gallery_layout" value="editorial" {{ $selectedLayout === 'editorial' ? 'checked' : '' }} required>
            <span class="gallery-layout-preview gallery-layout-preview--editorial" aria-hidden="true">
                <span class="gallery-layout-preview__hero">
                    <span><i class="preview-kicker"></i><i class="preview-title"></i><i class="preview-meta"></i></span><b></b>
                </span>
                <span class="gallery-layout-preview__nav"><i></i><i></i><i></i></span>
                <span class="gallery-layout-preview__photos"><i></i><i></i><i></i><i></i><i></i></span>
            </span>
            <span class="gallery-layout-option__copy">
                <strong>Light Editorial</strong>
                <small>Modern, airy photo story</small>
            </span>
            <span class="gallery-layout-option__check"><i class="fas fa-check"></i></span>
        </label>

        <label class="gallery-layout-option {{ $selectedLayout === 'luxe' ? 'is-selected' : '' }}" for="gallery-layout-luxe">
            <input id="gallery-layout-luxe" type="radio" name="gallery_layout" value="luxe" {{ $selectedLayout === 'luxe' ? 'checked' : '' }} required>
            <span class="gallery-layout-preview gallery-layout-preview--luxe" aria-hidden="true">
                <span class="gallery-layout-preview__hero">
                    <i class="preview-kicker"></i><i class="preview-title"></i><i class="preview-meta"></i>
                </span>
                <span class="gallery-layout-preview__nav"><em></em><i></i><i></i><i></i></span>
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
    <input type="file" name="background_path" id="background_path" class="input form-control" accept=".jpg,.jpeg,.png,.webp">
    <small class="form-text text-muted" id="backgroundImageHelp">For a sharp full-width hero, upload a landscape image of at least 1920 x 720 px. Recommended: 2560 x 1000 px, up to 15 MB.</small>
    <small class="form-text d-none" id="backgroundImageDimensions" aria-live="polite"></small>

    <div class="mt-3">
        <img id="backgroundPreview" src="{{ $backgroundPath }}" alt="Gallery background preview" class="img-fluid border rounded" style="width: 100%; max-width: 500px; max-height: 180px; object-fit: cover; {{ $backgroundPath ? '' : 'display:none;' }}">
    </div>

    @error('background_path')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

@php($isPublic = old('is_public', $gallery->is_public ?? 0) == 1)

<div class="mb-3 form-check">
    <input type="hidden" name="is_public" value="0">
    <div class="d-flex align-items-center">
        <input type="checkbox" name="is_public" value="1" id="is_public" class="form-check-input me-2" {{ $isPublic ? 'checked' : '' }}>
        <label for="is_public" class="form-check-label mt-1 ml-1">Public gallery</label>
    </div>
    <small class="form-text text-muted">Public galleries open without a password. Passwords are required for private galleries.</small>
    @error('is_public')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="client_password">Client Password: <span class="password-required-marker required_start" {{ $isPublic ? 'hidden' : '' }}>*</span></label>
    <input id="client_password" type="text" name="client_password" value="{{ old('client_password', $gallery->client_password ?? '') }}" class="input form-control" {{ $isPublic ? '' : 'required' }}>
    @error('client_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="mb-3">
    <label for="guest_password">Guest Password: <span class="password-required-marker required_start" {{ $isPublic ? 'hidden' : '' }}>*</span></label>
    <input id="guest_password" type="text" name="guest_password" value="{{ old('guest_password', $gallery->guest_password ?? '') }}" class="input form-control" {{ $isPublic ? '' : 'required' }}>
    @error('guest_password')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<style>
        .gallery-layout-picker { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; max-width: 980px; }
        .gallery-layout-option { position: relative; display: block; overflow: hidden; border: 2px solid #e1e5ea; border-radius: .7rem; background: #fff; cursor: pointer; transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease; }
        .gallery-layout-option:hover { border-color: #9aa8b8; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(34, 55, 78, .1); }
        .gallery-layout-option.is-selected, .gallery-layout-option:has(input:checked) { border-color: #2878c8; box-shadow: 0 0 0 3px rgba(40, 120, 200, .12); }
        .gallery-layout-option:focus-within { outline: 3px solid rgba(40, 120, 200, .3); outline-offset: 2px; }
        .gallery-layout-option input { position: absolute; width: 1px; height: 1px; opacity: 0; }
        .gallery-layout-preview { position: relative; display: block; height: 158px; overflow: hidden; }
        .gallery-layout-preview i, .gallery-layout-preview b, .gallery-layout-preview em { display: block; }
        .gallery-layout-preview__hero { display: flex; flex-direction: column; justify-content: flex-end; }
        .preview-kicker, .preview-title, .preview-meta { height: 3px; border-radius: 9px; }

        /* Dark Mosaic mirrors the compact dark hero and edge-to-edge masonry wall. */
        .gallery-layout-preview--masonry { padding: 10px; background: #151515; }
        .gallery-layout-preview--masonry .gallery-layout-preview__hero { height: 52px; padding: 8px; background: linear-gradient(110deg, #806548, #242424 56%, #777968); }
        .gallery-layout-preview--masonry .preview-kicker { width: 25%; margin-bottom: 5px; background: #d3b07b; }
        .gallery-layout-preview--masonry .preview-title { width: 49%; height: 7px; margin-bottom: 5px; background: #fff; }
        .gallery-layout-preview--masonry .preview-meta { width: 36%; background: #b7b7b7; }
        .gallery-layout-preview--masonry .gallery-layout-preview__nav { display: flex; gap: 5px; height: 19px; align-items: center; }
        .gallery-layout-preview--masonry .gallery-layout-preview__nav i { width: 18%; height: 4px; border-radius: 9px; background: #595959; }
        .gallery-layout-preview--masonry .gallery-layout-preview__nav i:first-child { background: #eee; }
        .gallery-layout-preview--masonry .gallery-layout-preview__photos { display: grid; height: 73px; grid-template: 31px 38px / 1.15fr .8fr 1.25fr; gap: 3px; }
        .gallery-layout-preview--masonry .gallery-layout-preview__photos i { background: linear-gradient(135deg, #796555, #c2af97); }
        .gallery-layout-preview--masonry .gallery-layout-preview__photos i:nth-child(2) { grid-row: span 2; background: linear-gradient(135deg, #46574c, #9cac9f); }
        .gallery-layout-preview--masonry .gallery-layout-preview__photos i:nth-child(3) { background: linear-gradient(135deg, #9a7258, #d0ad8f); }
        .gallery-layout-preview--masonry .gallery-layout-preview__photos i:nth-child(4) { background: linear-gradient(135deg, #52606a, #a4aebb); }
        .gallery-layout-preview--masonry .gallery-layout-preview__photos i:nth-child(5) { background: linear-gradient(135deg, #8b826b, #cfc4a6); }

        /* Light Editorial uses the airy split hero, pill navigation, and clean story columns. */
        .gallery-layout-preview--editorial { padding: 0 10px 10px; background: #f8f7f3; }
        .gallery-layout-preview--editorial .gallery-layout-preview__hero { height: 66px; margin: 0 -10px; padding: 11px 12px; flex-direction: row; align-items: flex-end; justify-content: space-between; background: linear-gradient(125deg, #e9eee2 0 68%, #c8d4b7); }
        .gallery-layout-preview--editorial .gallery-layout-preview__hero > span { width: 65%; }
        .gallery-layout-preview--editorial .preview-kicker { width: 34%; margin-bottom: 6px; background: #77816b; }
        .gallery-layout-preview--editorial .preview-title { width: 86%; height: 9px; margin-bottom: 6px; background: #30362d; }
        .gallery-layout-preview--editorial .preview-meta { width: 54%; background: #879080; }
        .gallery-layout-preview--editorial .gallery-layout-preview__hero b { width: 25px; height: 11px; border-radius: 10px; background: #34402f; }
        .gallery-layout-preview--editorial .gallery-layout-preview__nav { display: flex; gap: 4px; height: 26px; align-items: center; border-bottom: 1px solid #e0e2d9; }
        .gallery-layout-preview--editorial .gallery-layout-preview__nav i { width: 23%; height: 9px; border-radius: 10px; border: 1px solid #c3cbb9; }
        .gallery-layout-preview--editorial .gallery-layout-preview__nav i:first-child { background: #e1e8da; }
        .gallery-layout-preview--editorial .gallery-layout-preview__photos { display: grid; height: 58px; grid-template: 35px 19px / 1fr 1.2fr .75fr 1.1fr; gap: 3px; padding-top: 3px; }
        .gallery-layout-preview--editorial .gallery-layout-preview__photos i { background: linear-gradient(135deg, #d8bea5, #f0dec9); }
        .gallery-layout-preview--editorial .gallery-layout-preview__photos i:nth-child(2) { grid-row: span 2; background: linear-gradient(135deg, #9eaf95, #dbe5d1); }
        .gallery-layout-preview--editorial .gallery-layout-preview__photos i:nth-child(3) { background: linear-gradient(135deg, #c7957d, #efd0bd); }
        .gallery-layout-preview--editorial .gallery-layout-preview__photos i:nth-child(4) { grid-row: span 2; background: linear-gradient(135deg, #9db6bd, #d9e5e7); }
        .gallery-layout-preview--editorial .gallery-layout-preview__photos i:nth-child(5) { background: linear-gradient(135deg, #b29d85, #e7d7c1); }

        /* Luxe Frame follows the oversized hero, gold navigation, and individually framed prints. */
        .gallery-layout-preview--luxe { padding: 10px 13px 11px; background: #11100f; }
        .gallery-layout-preview--luxe .gallery-layout-preview__hero { height: 68px; margin: -10px -13px 0; padding: 12px 14px; background: radial-gradient(circle at 78% 20%, #795f45, #171513 62%); }
        .gallery-layout-preview--luxe .preview-kicker { width: 31%; margin-bottom: 8px; background: #c4a474; }
        .gallery-layout-preview--luxe .preview-title { width: 68%; height: 11px; margin-bottom: 7px; background: #f0e7d9; }
        .gallery-layout-preview--luxe .preview-meta { width: 43%; background: #c0aa8b; }
        .gallery-layout-preview--luxe .gallery-layout-preview__nav { display: flex; gap: 6px; height: 26px; align-items: center; border-bottom: 1px solid #3a342c; }
        .gallery-layout-preview--luxe .gallery-layout-preview__nav em { width: 18%; height: 3px; background: #ba9767; }
        .gallery-layout-preview--luxe .gallery-layout-preview__nav i { width: 15%; height: 3px; background: #777069; }
        .gallery-layout-preview--luxe .gallery-layout-preview__nav i:first-of-type { background: #e1c89e; }
        .gallery-layout-preview--luxe .gallery-layout-preview__photos { display: grid; height: 53px; grid-template-columns: 1.15fr .85fr 1fr; gap: 7px; padding-top: 7px; }
        .gallery-layout-preview--luxe .gallery-layout-preview__photos i { border: 3px solid #40382f; box-shadow: 0 4px 6px rgba(0,0,0,.35); background: linear-gradient(135deg, #795e49, #c4a27e); }
        .gallery-layout-preview--luxe .gallery-layout-preview__photos i:nth-child(2) { height: 36px; background: linear-gradient(135deg, #53574c, #a4aa93); }
        .gallery-layout-preview--luxe .gallery-layout-preview__photos i:nth-child(3) { height: 43px; background: linear-gradient(135deg, #735144, #c79170); }
        .gallery-layout-preview--luxe .gallery-layout-preview__photos i:nth-child(n+4) { display: none; }
        .gallery-layout-option__copy { display: block; padding: .8rem 2.8rem .9rem 1rem; border-top: 1px solid #edf0f2; } .gallery-layout-option__copy strong { display: block; color: #26323d; font-size: .94rem; } .gallery-layout-option__copy small { display: block; margin-top: .2rem; color: #798694; font-size: .76rem; }
        .gallery-layout-option__check { position: absolute; right: .8rem; bottom: 1.15rem; display: grid; width: 1.45rem; height: 1.45rem; place-items: center; border: 1px solid #ccd5dc; border-radius: 50%; color: transparent; font-size: .68rem; } .gallery-layout-option.is-selected .gallery-layout-option__check, .gallery-layout-option input:checked ~ .gallery-layout-option__check { border-color: #2878c8; background: #2878c8; color: #fff; }
        @media (max-width: 767.98px) { .gallery-layout-picker { grid-template-columns: 1fr; max-width: 420px; } }
 </style>

<script>

    const initGalleryForm = () => {
        if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
            window.jQuery('#session_id').select2({ width: '100%' });
        }

        const setImagePreview = (inputId, previewId) => {
            const input = document.getElementById(inputId);
            if (!input) return;

            input.addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (!file) return;

                const preview = document.getElementById(previewId);
                const objectUrl = URL.createObjectURL(file);
                preview.src = objectUrl;
                preview.style.display = 'block';

                if (inputId !== 'background_path') return;

                const dimensions = document.getElementById('backgroundImageDimensions');
                const probe = new Image();
                probe.onload = () => {
                    const isLargeEnough = probe.naturalWidth >= 1920 && probe.naturalHeight >= 720;
                    dimensions.textContent = `${probe.naturalWidth} x ${probe.naturalHeight} px - ${isLargeEnough ? 'excellent for the gallery hero' : 'too small and likely to look pixelated'}`;
                    dimensions.classList.remove('d-none', 'text-success', 'text-danger');
                    dimensions.classList.add(isLargeEnough ? 'text-success' : 'text-danger');
                    input.setCustomValidity(isLargeEnough ? '' : 'Choose an image that is at least 1920 x 720 pixels.');
                    URL.revokeObjectURL(objectUrl);
                };
                probe.onerror = () => {
                    input.setCustomValidity('Choose a valid image file.');
                    URL.revokeObjectURL(objectUrl);
                };
                probe.src = objectUrl;
            });
        };

        setImagePreview('thumbnail_path', 'thumbnailPreview');
        setImagePreview('background_path', 'backgroundPreview');

        const publicCheckbox = document.getElementById('is_public');
        const passwordInputs = [
            document.getElementById('client_password'),
            document.getElementById('guest_password'),
        ];
        const passwordMarkers = document.querySelectorAll('.password-required-marker');
        const syncPasswordRequirements = () => {
            const passwordsAreRequired = !publicCheckbox.checked;
            passwordInputs.forEach(input => {
                input.required = passwordsAreRequired;
                input.setAttribute('aria-required', passwordsAreRequired ? 'true' : 'false');
            });
            passwordMarkers.forEach(marker => marker.hidden = !passwordsAreRequired);
        };

        publicCheckbox.addEventListener('change', syncPasswordRequirements);
        syncPasswordRequirements();

        const layoutInputs = document.querySelectorAll('input[name="gallery_layout"]');
        const updateSelectedLayout = input => {
            document.querySelectorAll('.gallery-layout-option').forEach(option => option.classList.remove('is-selected'));
            input.closest('.gallery-layout-option').classList.add('is-selected');
        };

        layoutInputs.forEach(input => input.addEventListener('change', () => updateSelectedLayout(input)));
        const checkedLayout = document.querySelector('input[name="gallery_layout"]:checked');
        if (checkedLayout) updateSelectedLayout(checkedLayout);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGalleryForm, { once: true });
    } else {
        initGalleryForm();
    }
</script>
