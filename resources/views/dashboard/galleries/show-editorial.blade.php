<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

@php
    $galleryBackgroundUrl = $gallery->background_url;
@endphp

<main class="editorial-gallery">
    <header class="editorial-hero {{ $galleryBackgroundUrl ? 'editorial-hero--with-image' : '' }}">
        @if ($galleryBackgroundUrl)
            <div class="editorial-hero__image" style="background-image: url('{{ $galleryBackgroundUrl }}')"></div>
        @endif
        <div class="editorial-hero__glow"></div>
        <div class="editorial-hero__content">
            <p class="editorial-hero__eyebrow">PRIVATE PHOTO COLLECTION</p>
            <h1>{{ $gallery->name }}</h1>
            <p class="editorial-hero__meta">{{ $gallery->folders->count() }} {{ Str::plural('collection', $gallery->folders->count()) }} · {{ $gallery->folders->sum(fn ($folder) => $folder->media->count()) }} photographs</p>
        </div>
        <button class="editorial-download" id="open-download-drawer" type="button" aria-controls="download-drawer" aria-expanded="false">
            <i class="fas fa-arrow-down"></i><span>Download photos</span>
        </button>
    </header>

    <nav class="editorial-filter" aria-label="Gallery collections">
        <div class="editorial-filter__inner">
            <button class="editorial-filter__button is-active" type="button" data-folder="all">All photos</button>
            @foreach ($gallery->folders as $folder)
                <button class="editorial-filter__button" type="button" data-folder="folder-{{ $folder->id }}">{{ $folder->name }}</button>
            @endforeach
        </div>
    </nav>

    @if (session('download_requested'))
        <div class="editorial-toast" role="status">
            <i class="fas fa-check-circle"></i>{{ session('download_requested') }}
            <button type="button" aria-label="Dismiss message"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <section class="editorial-grid" aria-label="{{ $gallery->name }} photos">
        @foreach ($gallery->folders as $folder)
            @foreach ($folder->media as $media)
                <button class="editorial-photo" type="button" data-folder="folder-{{ $folder->id }}" data-full="{{ $media->path }}" aria-label="View photo from {{ $folder->name }}">
                    <img src="{{ $media->path }}" alt="" loading="lazy">
                    <span class="editorial-photo__view"><i class="fas fa-expand"></i></span>
                </button>
            @endforeach
        @endforeach
    </section>
</main>

<div class="editorial-lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Photo preview" aria-hidden="true">
    <button class="editorial-lightbox__close" type="button" aria-label="Close preview"><i class="fas fa-times"></i></button>
    <img src="" alt="">
</div>

<div class="editorial-drawer-backdrop" id="download-drawer-backdrop"></div>
<aside class="editorial-drawer" id="download-drawer" aria-hidden="true" aria-labelledby="download-drawer-title">
    <div class="editorial-drawer__header">
        <div><p>YOUR GALLERY</p><h2 id="download-drawer-title">Download photos</h2></div>
        <button class="editorial-drawer__close" type="button" aria-label="Close download panel"><i class="fas fa-times"></i></button>
    </div>
    <form class="editorial-download-form" method="POST" action="{{ route('gallery.download', ['photographer_subdomain' => $photographer->subdomain, 'gallery_slug' => $gallery->slug]) }}">
        @csrf
        <div class="editorial-drawer__body">
            <p>Choose the collections you would like to receive. A private link will be sent to your email address.</p>
            <label for="download-email">Email address <em>*</em></label>
            <input id="download-email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
            @error('email')<small class="editorial-error">{{ $message }}</small>@enderror
            <div class="editorial-select-all">
                <span>SELECT COLLECTIONS</span>
                <label><input type="checkbox" name="download_all" value="1" id="download-all" {{ old('download_all') ? 'checked' : '' }}> All photos</label>
            </div>
            <div class="editorial-folder-list">
                @forelse ($gallery->folders as $folder)
                    <label class="editorial-folder-option">
                        <input class="download-folder-checkbox" type="checkbox" name="folder_ids[]" value="{{ $folder->id }}" {{ in_array($folder->id, old('folder_ids', [])) ? 'checked' : '' }}>
                        <span><i class="far fa-folder"></i> {{ $folder->name }}</span>
                        <small>{{ $folder->media->count() }} {{ Str::plural('photo', $folder->media->count()) }}</small>
                    </label>
                @empty
                    <p>No collections are available for download yet.</p>
                @endforelse
            </div>
            @error('folder_ids')<small class="editorial-error">{{ $message }}</small>@enderror
        </div>
        <div class="editorial-drawer__footer"><button type="submit"><i class="fas fa-arrow-down"></i> Request download link</button></div>
    </form>
</aside>

<script>
(() => {
    const body = document.body;
    const filters = document.querySelectorAll('.editorial-filter__button');
    const photos = document.querySelectorAll('.editorial-photo');
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = lightbox.querySelector('img');
    const closeLightbox = () => { lightbox.classList.remove('is-open'); lightbox.setAttribute('aria-hidden', 'true'); lightboxImage.src = ''; body.classList.remove('editorial-no-scroll'); };
    filters.forEach(button => button.addEventListener('click', () => {
        filters.forEach(item => item.classList.remove('is-active'));
        button.classList.add('is-active');
        photos.forEach(photo => photo.hidden = button.dataset.folder !== 'all' && photo.dataset.folder !== button.dataset.folder);
    }));
    photos.forEach(photo => photo.addEventListener('click', () => { lightboxImage.src = photo.dataset.full; lightbox.classList.add('is-open'); lightbox.setAttribute('aria-hidden', 'false'); body.classList.add('editorial-no-scroll'); }));
    lightbox.addEventListener('click', event => { if (event.target === lightbox) closeLightbox(); });
    lightbox.querySelector('.editorial-lightbox__close').addEventListener('click', closeLightbox);

    const drawer = document.getElementById('download-drawer');
    const backdrop = document.getElementById('download-drawer-backdrop');
    const openDrawerButton = document.getElementById('open-download-drawer');
    const closeDrawer = () => { drawer.classList.remove('is-open'); backdrop.classList.remove('is-visible'); drawer.setAttribute('aria-hidden', 'true'); openDrawerButton.setAttribute('aria-expanded', 'false'); body.classList.remove('editorial-no-scroll'); };
    const openDrawer = () => { drawer.classList.add('is-open'); backdrop.classList.add('is-visible'); drawer.setAttribute('aria-hidden', 'false'); openDrawerButton.setAttribute('aria-expanded', 'true'); body.classList.add('editorial-no-scroll'); setTimeout(() => document.getElementById('download-email').focus(), 250); };
    openDrawerButton.addEventListener('click', openDrawer);
    drawer.querySelector('.editorial-drawer__close').addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);
    const selectAll = document.getElementById('download-all');
    const checkboxes = [...document.querySelectorAll('.download-folder-checkbox')];
    selectAll.addEventListener('change', () => checkboxes.forEach(box => box.checked = selectAll.checked));
    checkboxes.forEach(box => box.addEventListener('change', () => selectAll.checked = checkboxes.length > 0 && checkboxes.every(item => item.checked)));
    document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeLightbox(); closeDrawer(); } });
    document.querySelector('.editorial-toast button')?.addEventListener('click', event => event.currentTarget.closest('.editorial-toast').remove());
    @if ($errors->has('email') || $errors->has('folder_ids')) openDrawer(); @endif
})();
</script>

<style>
    :root { color-scheme: light; } * { box-sizing: border-box; } body { margin: 0; background: #f8f7f3; color: #252923; font-family: Inter, ui-sans-serif, system-ui, sans-serif; } .editorial-no-scroll { overflow: hidden; }
    .editorial-gallery { min-height: 100vh; padding-bottom: 4rem; } .editorial-hero { position: relative; display: flex; align-items: end; justify-content: space-between; gap: 2rem; min-height: 300px; padding: clamp(2.5rem, 7vw, 6.5rem) clamp(1.25rem, 7vw, 8rem) 3.7rem; overflow: hidden; background: #e9eee2; border-bottom: 1px solid #d7ddcf; } .editorial-hero::after { position: absolute; right: clamp(1.25rem, 7vw, 8rem); bottom: 2rem; z-index: 1; width: min(21vw, 230px); height: 1px; background: #879178; content: ''; } .editorial-hero__image { position: absolute; inset: 0; background-position: center; background-size: cover; } .editorial-hero--with-image::before { position: absolute; z-index: 1; inset: 0; background: linear-gradient(90deg, rgba(255,255,252,.86) 0%, rgba(255,255,252,.58) 57%, rgba(255,255,252,.28) 100%); content: ''; } .editorial-hero--with-image::after { background: #53604b; } .editorial-hero__glow { position: absolute; width: 46rem; height: 46rem; right: -13rem; top: -34rem; border-radius: 50%; background: #c6d3b2; opacity: .52; } .editorial-hero--with-image .editorial-hero__glow { display: none; } .editorial-hero__content, .editorial-download { position: relative; z-index: 2; } .editorial-hero__eyebrow, .editorial-drawer__header p { margin: 0 0 .8rem; color: #657156; font-size: .7rem; font-weight: 800; letter-spacing: .18em; } .editorial-hero h1 { max-width: 850px; margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: clamp(2.7rem, 7vw, 6.5rem); font-weight: 400; letter-spacing: -.06em; line-height: .9; } .editorial-hero__meta { margin: 1.35rem 0 0; color: #65705f; font-size: .88rem; } .editorial-download, .editorial-drawer__footer button { border: 1px solid #34402f; border-radius: 999px; padding: .88rem 1.15rem; background: #34402f; color: #fff; font: inherit; font-weight: 750; cursor: pointer; white-space: nowrap; transition: transform .2s, background .2s, box-shadow .2s; box-shadow: 0 7px 18px rgba(52,64,47,.14); } .editorial-download:hover, .editorial-drawer__footer button:hover { transform: translateY(-2px); background: #1f291d; box-shadow: 0 10px 24px rgba(52,64,47,.22); } .editorial-download i, .editorial-drawer__footer i { margin-right: .55rem; }
    .editorial-filter { position: sticky; top: 0; z-index: 3; padding: 1rem clamp(1.25rem, 7vw, 8rem); border-bottom: 1px solid #e1e2da; background: rgba(248,247,243,.88); backdrop-filter: blur(14px); } .editorial-filter__inner { display: flex; gap: .45rem; overflow-x: auto; } .editorial-filter__button { border: 1px solid transparent; border-radius: 999px; padding: .58rem .9rem; background: transparent; color: #697065; font: inherit; font-size: .84rem; cursor: pointer; white-space: nowrap; transition: .2s ease; } .editorial-filter__button:hover, .editorial-filter__button.is-active { border-color: #b6c0aa; background: #e5eadf; color: #2f3b2b; }
    /* Columns keep every photograph at its original aspect ratio; no fixed height or crop is applied. */
    .editorial-grid { column-count: 4; column-gap: clamp(.55rem, 1vw, 1rem); padding: clamp(1.25rem, 4vw, 4rem) clamp(1.25rem, 7vw, 8rem); } .editorial-photo { position: relative; display: block; width: 100%; margin: 0 0 clamp(.55rem, 1vw, 1rem); padding: 0; break-inside: avoid; overflow: hidden; border: 0; background: #e6e6df; cursor: zoom-in; } .editorial-photo[hidden] { display: none; } .editorial-photo img { width: 100%; height: auto; display: block; transition: transform .65s cubic-bezier(.2,.8,.2,1), filter .4s; } .editorial-photo:hover img { transform: scale(1.025); filter: brightness(.94); } .editorial-photo__view { position: absolute; right: 1rem; bottom: 1rem; display: grid; width: 2.3rem; height: 2.3rem; place-items: center; border-radius: 50%; opacity: 0; background: #fff; color: #34402f; box-shadow: 0 4px 15px rgba(29,35,28,.18); transition: opacity .25s, transform .25s; transform: translateY(6px); } .editorial-photo:hover .editorial-photo__view { opacity: 1; transform: translateY(0); }
    .editorial-lightbox, .editorial-drawer-backdrop { position: fixed; inset: 0; opacity: 0; pointer-events: none; transition: opacity .25s ease; } .editorial-lightbox { z-index: 20; display: grid; place-items: center; padding: 3rem; background: rgba(5,5,4,.96); } .editorial-lightbox.is-open, .editorial-drawer-backdrop.is-visible { opacity: 1; pointer-events: auto; } .editorial-lightbox img { max-width: 100%; max-height: 100%; object-fit: contain; } .editorial-lightbox__close { position: absolute; top: 1.5rem; right: 1.5rem; display: grid; width: 2.75rem; height: 2.75rem; place-items: center; border: 1px solid #4b4c45; border-radius: 50%; background: transparent; color: #fff; cursor: pointer; }
    .editorial-drawer-backdrop { z-index: 29; background: rgba(35,40,32,.24); backdrop-filter: blur(4px); } .editorial-drawer { position: fixed; z-index: 30; top: 0; right: 0; display: flex; flex-direction: column; width: min(460px, 100%); height: 100dvh; transform: translateX(100%); background: #fffefa; color: #252923; transition: transform .35s cubic-bezier(.2,.8,.2,1); } .editorial-drawer.is-open { transform: translateX(0); } .editorial-drawer__header { display: flex; justify-content: space-between; padding: 2rem; border-bottom: 1px solid #dde1d5; } .editorial-drawer__header p { color: #697255; } .editorial-drawer h2 { margin: 0; font-family: Georgia, serif; font-size: 2rem; font-weight: 400; } .editorial-drawer__close { width: 2.4rem; height: 2.4rem; border: 1px solid #cfd5c8; border-radius: 50%; background: transparent; color: #384235; cursor: pointer; } .editorial-download-form { display: flex; min-height: 0; flex: 1; flex-direction: column; } .editorial-drawer__body { flex: 1; padding: 2rem; overflow: auto; } .editorial-drawer__body > p { margin-top: 0; color: #656b60; line-height: 1.55; } .editorial-drawer label[for="download-email"] { display: block; margin: 1.5rem 0 .5rem; font-size: .85rem; font-weight: 700; } .editorial-drawer em { color: #a04141; } .editorial-drawer input[type="email"] { width: 100%; border: 1px solid #cfd5c8; border-radius: .45rem; padding: .85rem; background: #fff; color: #222; font: inherit; } .editorial-select-all { display: flex; justify-content: space-between; align-items: center; margin: 2rem 0 .75rem; font-size: .72rem; font-weight: 800; letter-spacing: .09em; } .editorial-select-all label { font-size: .78rem; letter-spacing: normal; cursor: pointer; } .editorial-folder-list { border-top: 1px solid #dde1d5; } .editorial-folder-option { display: flex; justify-content: space-between; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #dde1d5; cursor: pointer; } .editorial-folder-option span i { margin-right: .45rem; color: #6f795a; } .editorial-folder-option small { color: #75736d; } .editorial-error { display: block; margin-top: .4rem; color: #b33a3a; } .editorial-drawer__footer { padding: 1.25rem 2rem; border-top: 1px solid #dde1d5; } .editorial-drawer__footer button { width: 100%; background: #34402f; color: #fff; }
    .editorial-toast { position: fixed; z-index: 10; right: 1.25rem; bottom: 1.25rem; display: flex; align-items: center; gap: .65rem; max-width: min(420px, calc(100% - 2.5rem)); padding: .9rem 1rem; border-radius: .55rem; background: #e8efdf; color: #2f3b2b; box-shadow: 0 12px 35px rgba(40,48,36,.16); } .editorial-toast button { margin-left: auto; border: 0; background: none; cursor: pointer; }
    @media (max-width: 1100px) { .editorial-grid { column-count: 3; } } @media (max-width: 700px) { .editorial-hero { min-height: 340px; flex-direction: column; align-items: flex-start; justify-content: flex-end; } .editorial-hero::after { display: none; } .editorial-download { margin-top: 1rem; } .editorial-grid { column-count: 2; column-gap: .55rem; } .editorial-photo { margin-bottom: .55rem; } .editorial-photo__view { display: none; } } @media (max-width: 420px) { .editorial-grid { column-count: 1; } }
</style>
