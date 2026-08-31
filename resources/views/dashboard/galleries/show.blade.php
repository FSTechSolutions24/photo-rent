<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

@php
    $galleryBackgroundUrl = $gallery->background_url;
@endphp

<header class="masonry-gallery-hero {{ $galleryBackgroundUrl ? 'masonry-gallery-hero--with-image' : '' }}">
    @if ($galleryBackgroundUrl)
        <div class="masonry-gallery-hero__image" style="background-image: url('{{ $galleryBackgroundUrl }}')"></div>
    @endif
    <div class="masonry-gallery-hero__overlay"></div>
    <div class="masonry-gallery-hero__content">
        <p>PRIVATE PHOTO COLLECTION</p>
        <h1>{{ $gallery->name }}</h1>
        <span>{{ $gallery->folders->count() }} {{ Str::plural('collection', $gallery->folders->count()) }} · {{ $gallery->folders->sum(fn ($folder) => $folder->media->count()) }} photographs</span>
    </div>
</header>

<!-- Folder Navbar --> 
<div class="folder-nav"> 
    <div class="folder-buttons"> 
        <button class="folder-btn active" data-folder="all"> All </button> 
        @foreach ($gallery->folders as $folder) 
            <button class="folder-btn" data-folder="folder-{{ $folder->id }}" > {{ $folder->name }} </button> 
        @endforeach 
    </div> 
    <!-- Download Button --> 
    <button class="download-folder-btn" id="open-download-drawer" type="button" aria-controls="download-drawer" aria-expanded="false">
        <i class="fas fa-download"></i> <span>Download</span> 
    </button> 
</div>

@if (session('download_requested'))
    <div class="download-toast" role="status">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('download_requested') }}</span>
        <button class="download-toast__close" type="button" aria-label="Dismiss message">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<div class="download-drawer-backdrop" id="download-drawer-backdrop"></div>
<aside class="download-drawer" id="download-drawer" aria-hidden="true" aria-labelledby="download-drawer-title">
    <div class="download-drawer__accent"></div>
    <div class="download-drawer__header">
        <div>
            <p class="download-drawer__eyebrow">YOUR GALLERY</p>
            <h2 id="download-drawer-title">Prepare your download</h2>
        </div>
        <button class="download-drawer__close" type="button" aria-label="Close download panel">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <form class="download-form" method="POST" action="{{ route('gallery.download', ['photographer_subdomain' => $photographer->subdomain, 'gallery_slug' => $gallery->slug]) }}">
        @csrf
        <div class="download-drawer__body">
            <p class="download-drawer__intro">Choose the folders you would like to receive. We will send a private download link to your email.</p>

            <label class="download-email-label" for="download-email">Email address <span>*</span></label>
            <div class="download-email-wrap">
                <i class="far fa-envelope"></i>
                <input id="download-email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
            </div>
            @error('email') <p class="download-form-error">{{ $message }}</p> @enderror

            <div class="download-selection-heading">
                <div>
                    <span>SELECT FOLDERS</span>
                    <small>{{ $gallery->folders->count() }} available</small>
                </div>
                <label class="download-all-toggle">
                    <input type="checkbox" name="download_all" value="1" id="download-all" {{ old('download_all') ? 'checked' : '' }}>
                    <span>Download all</span>
                </label>
            </div>

            <div class="download-folder-list">
                @forelse ($gallery->folders as $folder)
                    <label class="download-folder-option">
                        <input class="download-folder-checkbox" type="checkbox" name="folder_ids[]" value="{{ $folder->id }}" {{ in_array($folder->id, old('folder_ids', [])) ? 'checked' : '' }}>
                        <span class="download-checkmark"><i class="fas fa-check"></i></span>
                        <span class="download-folder-icon"><i class="far fa-folder"></i></span>
                        <span class="download-folder-name">{{ $folder->name }}</span>
                        <span class="download-folder-count">{{ $folder->media->count() }} {{ Str::plural('photo', $folder->media->count()) }}</span>
                    </label>
                @empty
                    <p class="download-empty-state">There are no folders available for download yet.</p>
                @endforelse
            </div>
            @error('folder_ids') <p class="download-form-error">{{ $message }}</p> @enderror
        </div>

        <div class="download-drawer__footer">
            <p><i class="fas fa-lock"></i> Your link will be sent securely by email.</p>
            <button class="download-submit" type="submit"><i class="fas fa-download"></i> Request download</button>
        </div>
    </form>
</aside>

<!-- Gallery -->
<div class="gallery">
    @foreach ($gallery->folders as $folder)
        @foreach ($folder->media as $media)
            <div 
                class="gallery-item loading"
                data-folder="folder-{{ $folder->id }}"
            >
                <div class="skeleton"></div>

                <img 
                    src="{{ $media->path }}"
                    data-full="{{ $media->path }}"
                    alt=""
                    loading="lazy"
                >
            </div>
        @endforeach
    @endforeach
</div>


<div class="lightbox" id="lightbox">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-image" src="">
</div>

<script>
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.querySelector('.lightbox-image');
    const closeBtn = document.querySelector('.lightbox-close');

    document.querySelectorAll('.gallery img').forEach(img => {
        img.addEventListener('click', () => {
            lightboxImage.src = img.dataset.full;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeLightbox() {
        lightbox.classList.remove('active');
        lightboxImage.src = '';
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeLightbox);

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>

<script>
    (() => {
        const drawer = document.getElementById('download-drawer');
        const backdrop = document.getElementById('download-drawer-backdrop');
        const openButton = document.getElementById('open-download-drawer');
        const closeButton = drawer.querySelector('.download-drawer__close');
        const selectAll = document.getElementById('download-all');
        const folderCheckboxes = Array.from(document.querySelectorAll('.download-folder-checkbox'));

        const closeDrawer = () => {
            drawer.classList.remove('is-open');
            backdrop.classList.remove('is-visible');
            drawer.setAttribute('aria-hidden', 'true');
            openButton.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('download-drawer-open');
        };
        const openDrawer = () => {
            drawer.classList.add('is-open');
            backdrop.classList.add('is-visible');
            drawer.setAttribute('aria-hidden', 'false');
            openButton.setAttribute('aria-expanded', 'true');
            document.body.classList.add('download-drawer-open');
            setTimeout(() => document.getElementById('download-email').focus(), 250);
        };

        openButton.addEventListener('click', openDrawer);
        closeButton.addEventListener('click', closeDrawer);
        backdrop.addEventListener('click', closeDrawer);
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape' && drawer.classList.contains('is-open')) closeDrawer();
        });

        selectAll.addEventListener('change', () => {
            folderCheckboxes.forEach(checkbox => checkbox.checked = selectAll.checked);
        });
        folderCheckboxes.forEach(checkbox => checkbox.addEventListener('change', () => {
            selectAll.checked = folderCheckboxes.length > 0 && folderCheckboxes.every(item => item.checked);
        }));

        @if ($errors->has('email') || $errors->has('folder_ids'))
            openDrawer();
        @endif
    })();
</script>

<script>
    document.querySelector('.download-toast__close')?.addEventListener('click', event => {
        event.currentTarget.closest('.download-toast').remove();
    });
</script>

<script>
    const folderButtons = document.querySelectorAll('.folder-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    folderButtons.forEach(btn => {
        btn.addEventListener('click', () => {

            // Active state
            folderButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const selectedFolder = btn.dataset.folder;

            galleryItems.forEach(item => {

                if (selectedFolder === 'all' || item.dataset.folder === selectedFolder) {
                    // SHOW: remove hidden class, set display block immediately
                    item.style.display = 'block';
                    requestAnimationFrame(() => {
                        item.classList.remove('hidden');
                    });
                } else {
                    // HIDE: add hidden class (triggers fade out)
                    item.classList.add('hidden');

                    // After transition ends, hide it completely
                    const transitionDuration = 10; // match CSS transition in ms
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, transitionDuration);
                }
            });
        });
    });
</script>

<script>
    window.addEventListener('load', () => {
        const items = document.querySelectorAll('.gallery-item');

        setTimeout(() => {
            items.forEach(item => {
                item.classList.add('loaded');

                // Show images inside each item
                const img = item.querySelector('img');
                if (img) {
                    img.style.display = 'block';
                }
            });
        }, 1000);
    });
</script>

<style>
/* Reset-ish */
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #0b0b0b;
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    color: #fff;
}

/* Gallery container */
.gallery {
    column-count: 1;
    column-gap: 12px;
    padding: 12px;
}

/* Tablet */
@media (min-width: 640px) {
    .gallery {
        column-count: 2;
    }
}

/* Small desktop */
@media (min-width: 900px) {
    .gallery {
        column-count: 3;
    }
}

/* Large desktop */
@media (min-width: 1200px) {
    .gallery {
        column-count: 4;
    }
}

/* Gallery item */
.gallery-item {
    break-inside: avoid;
    margin-bottom: 12px;
    border-radius: 14px;
    overflow: hidden;
    background: #111;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
}

/* Image */
.gallery-item img {
    width: 100%;
    height: auto;
    display: none;
    object-fit: cover;
    transition: transform 0.4s ease;
}

/* Hover effect (desktop only) */
@media (hover: hover) {
    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.9);
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }
}

/* Lightbox overlay */
.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
    z-index: 9999;
}

/* Active state */
.lightbox.active {
    opacity: 1;
    visibility: visible;
}

/* Image */
.lightbox-image {
    max-width: 95%;
    max-height: 95%;
    border-radius: 12px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.8);
    animation: zoomIn 0.35s ease;
}

/* Close button */
.lightbox-close {
    position: absolute;
    top: 20px;
    right: 25px;
    font-size: 40px;
    color: #fff;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.lightbox-close:hover {
    opacity: 1;
}

/* Animation */
@keyframes zoomIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@media (hover: hover) {
    .gallery-item img {
        cursor: zoom-in;
    }

    .gallery-item:hover img {
        filter: brightness(1.05);
    }
}


/* Folder navigation */
.folder-nav { 
    display: flex; 
    align-items: center; 
    gap: 10px; 
    padding: 12px; 
    background: #0b0b0b; 
    position: sticky; 
    top: 0; z-index: 20; 
} 
/* Folder buttons container */ 
.folder-buttons { 
    display: flex; 
    align-items: center; 
    gap: 10px; 
    overflow-x: auto; 
    flex: 1; 
} 
.folder-buttons::-webkit-scrollbar { 
    display: none; 
} 
.folder-btn { 
    background: #111; 
    color: #bbb; 
    border: 1px solid #222; 
    padding: 8px 16px; 
    border-radius: 999px; 
    font-size: 14px; 
    cursor: pointer; 
    white-space: nowrap; 
    transition: all 0.25s ease; 
} 
.folder-btn:hover { 
    color: #fff; 
    border-color: #333; 
} 
.folder-btn.active { 
    background: #fff; 
    color: #000; 
    border-color: #fff; 
} 
/* Download button */ 
.download-folder-btn { 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    gap: 8px; 
    background: #fff; 
    color: #000; 
    border: 1px solid #fff; 
    padding: 8px 16px; 
    border-radius: 999px; 
    font-size: 14px; 
    font-weight: 600; 
    cursor: pointer; 
    white-space: nowrap; 
    transition: all 0.25s ease; 
    flex-shrink: 0; 
} 
.download-folder-btn i { 
    font-size: 13px; 
} 
.download-folder-btn:hover { 
    background: #ddd; 
    border-color: #ddd; 
    transform: translateY(-1px); 
} 
.download-folder-btn:active { 
    transform: translateY(0); 
}

.gallery-item {
    opacity: 1;
    transform: scale(1);
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.gallery-item.hidden {
    opacity: 0;
    transform: scale(0.96);
    pointer-events: none;
}

/* Skeleton */
.skeleton {
    width: 100%;
    aspect-ratio: 3 / 4; /* fallback height */
    background: linear-gradient(
        90deg,
        #1a1a1a 25%,
        #2a2a2a 37%,
        #1a1a1a 63%
    );
    background-size: 400% 100%;
    animation: shimmer 1.4s ease infinite;
    border-radius: 14px;
}

/* Hide image initially */
.gallery-item img {
    opacity: 0;
    transition: opacity 0.5s ease;
    transition: transform 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

/* Loaded state */
.gallery-item.loaded img {
    opacity: 1;
}

.gallery-item.loaded .skeleton {
    display: none;
}

/* Shimmer animation */
@keyframes shimmer {
    0% {
        background-position: 100% 0;
    }
    100% {
        background-position: -100% 0;
    }
}


/* Gallery item */
.gallery-item {
    position: relative;
}

/* Applies to all scrollable areas */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #0b0b0b; /* matches gallery background */
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: #444; /* slightly lighter than gallery item background */
    border-radius: 8px;
    border: 2px solid #0b0b0b; /* creates padding effect */
}

::-webkit-scrollbar-thumb:hover {
    background: #fff; /* highlight on hover for interactivity */
}

/* Optional: smooth corner transition */
::-webkit-scrollbar-thumb {
    transition: background 0.3s ease;
}



.folder-nav::-webkit-scrollbar {
    height: 8px;
}

.folder-nav::-webkit-scrollbar-track {
    background: #0b0b0b;
    border-radius: 6px;
}

.folder-nav::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 6px;
    border: 2px solid #0b0b0b;
}

.folder-nav::-webkit-scrollbar-thumb:hover {
    background: #fff;
}

.folder-nav {
    scrollbar-width: thin;
    scrollbar-color: #444 #0b0b0b;
}

.folder-nav {
    scroll-snap-type: x mandatory;
}

.folder-btn {
    scroll-snap-align: start;
}

/* Gallery heading — shared background image, styled to preserve the original dark masonry experience. */
.masonry-gallery-hero {
    position: relative;
    display: flex;
    align-items: flex-end;
    min-height: 300px;
    padding: clamp(2rem, 6vw, 5rem) clamp(1rem, 5vw, 5rem) 2.6rem;
    overflow: hidden;
    background: linear-gradient(135deg, #191919, #090909);
}
.masonry-gallery-hero__image,
.masonry-gallery-hero__overlay {
    position: absolute;
    inset: 0;
}
.masonry-gallery-hero__image {
    background-position: center;
    background-size: cover;
}
.masonry-gallery-hero__overlay {
    background: linear-gradient(90deg, rgba(0, 0, 0, .8), rgba(0, 0, 0, .43));
}
.masonry-gallery-hero__content {
    position: relative;
    z-index: 1;
    max-width: 900px;
}
.masonry-gallery-hero p {
    margin: 0 0 .7rem;
    color: #d6d6d6;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .18em;
}
.masonry-gallery-hero h1 {
    margin: 0;
    color: #fff;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(2.4rem, 6vw, 5.5rem);
    font-weight: 400;
    letter-spacing: -.05em;
    line-height: .92;
}
.masonry-gallery-hero span {
    display: block;
    margin-top: 1rem;
    color: #d5d5d5;
    font-size: 13px;
}
@media (max-width: 700px) {
    .masonry-gallery-hero { min-height: 340px; }
}

/* Download drawer */
.download-drawer-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(3, 6, 12, 0.66);
    backdrop-filter: blur(5px);
    opacity: 0;
    pointer-events: none;
    transition: opacity .32s ease;
    z-index: 9997;
}
.download-drawer-backdrop.is-visible { opacity: 1; pointer-events: auto; }
.download-drawer {
    position: fixed;
    top: 0;
    right: 0;
    width: min(470px, 100vw);
    height: 100dvh;
    display: flex;
    flex-direction: column;
    background: #101218;
    color: #f8fafc;
    box-shadow: -24px 0 70px rgba(0,0,0,.45);
    transform: translateX(104%);
    transition: transform .38s cubic-bezier(.22, 1, .36, 1);
    z-index: 9998;
    overflow: hidden;
}
.download-drawer.is-open { transform: translateX(0); }
.download-drawer__accent { height: 4px; flex-shrink: 0; background: linear-gradient(90deg, #a78bfa, #ec4899, #fbbf24); }
.download-drawer__header { display: flex; align-items: flex-start; justify-content: space-between; padding: 29px 30px 22px; border-bottom: 1px solid #282c35; }
.download-drawer__eyebrow { margin: 0 0 7px; color: #a78bfa; font-size: 10px; letter-spacing: .18em; font-weight: 800; }
.download-drawer h2 { margin: 0; font-size: 25px; letter-spacing: -.04em; line-height: 1.1; }
.download-drawer__close { display: grid; width: 36px; height: 36px; place-items: center; border: 1px solid #333844; border-radius: 50%; background: #1a1d25; color: #f8fafc; cursor: pointer; transition: .2s ease; }
.download-drawer__close:hover { background: #f8fafc; color: #101218; transform: rotate(90deg); }
.download-form { min-height: 0; display: flex; flex: 1; flex-direction: column; }
.download-drawer__body { flex: 1; overflow-y: auto; padding: 25px 30px; }
.download-drawer__intro { margin: 0 0 25px; color: #a6adbb; font-size: 14px; line-height: 1.65; }
.download-email-label { display: block; margin-bottom: 9px; color: #e5e7eb; font-size: 13px; font-weight: 700; }
.download-email-label span { color: #f472b6; }
.download-email-wrap { position: relative; }
.download-email-wrap > i { position: absolute; top: 50%; left: 15px; color: #9ca3af; transform: translateY(-50%); }
.download-email-wrap input { width: 100%; height: 48px; padding: 0 15px 0 42px; border: 1px solid #363b47; border-radius: 12px; outline: 0; background: #181b23; color: #fff; font: inherit; transition: .2s ease; }
.download-email-wrap input::placeholder { color: #687080; }
.download-email-wrap input:focus { border-color: #a78bfa; box-shadow: 0 0 0 4px rgba(167,139,250,.14); }
.download-selection-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 28px 0 12px; }
.download-selection-heading > div { display: flex; align-items: baseline; gap: 8px; }
.download-selection-heading span { color: #d1d5db; font-size: 11px; letter-spacing: .1em; font-weight: 800; }
.download-selection-heading small { color: #747d8d; font-size: 11px; }
.download-all-toggle { display: inline-flex; align-items: center; gap: 7px; color: #d8b4fe; font-size: 12px; font-weight: 700; cursor: pointer; }
.download-all-toggle input { appearance: none; width: 15px; height: 15px; border: 1px solid #6d5a9e; border-radius: 4px; background: transparent; display: grid; place-content: center; cursor: pointer; }
.download-all-toggle input:checked { background: #a78bfa; border-color: #a78bfa; }
.download-all-toggle input:checked::after { content: '✓'; color: #17141e; font-size: 11px; font-weight: 900; }
.download-folder-list { display: grid; gap: 9px; }
.download-folder-option { position: relative; display: flex; align-items: center; gap: 12px; min-height: 61px; padding: 11px 13px; border: 1px solid #2c303a; border-radius: 13px; background: #171a21; cursor: pointer; transition: .2s ease; }
.download-folder-option:hover { border-color: #575e70; background: #1c2029; transform: translateX(2px); }
.download-folder-option input { position: absolute; opacity: 0; pointer-events: none; }
.download-checkmark { display: grid; flex: 0 0 20px; width: 20px; height: 20px; place-items: center; border: 1px solid #525968; border-radius: 6px; color: transparent; transition: .18s ease; }
.download-checkmark i { font-size: 11px; }
.download-folder-option input:checked + .download-checkmark { border-color: #a78bfa; background: #a78bfa; color: #17141e; }
.download-folder-icon { display: grid; width: 32px; height: 32px; place-items: center; border-radius: 9px; color: #fbbf24; background: rgba(251,191,36,.12); }
.download-folder-name { overflow: hidden; color: #edf0f5; font-size: 14px; font-weight: 650; text-overflow: ellipsis; white-space: nowrap; }
.download-folder-count { margin-left: auto; color: #8f97a5; font-size: 11px; white-space: nowrap; }
.download-empty-state, .download-form-error { margin: 8px 0 0; color: #fda4af; font-size: 12px; }
.download-empty-state { color: #9ca3af; }
.download-drawer__footer { padding: 18px 30px 27px; border-top: 1px solid #282c35; background: #14171e; }
.download-drawer__footer p { margin: 0 0 14px; color: #8c95a5; font-size: 11px; }
.download-drawer__footer p i { margin-right: 5px; color: #a78bfa; }
.download-submit { width: 100%; height: 49px; border: 0; border-radius: 12px; background: linear-gradient(105deg, #a78bfa, #d946ef); color: #fff; font: inherit; font-size: 14px; font-weight: 800; cursor: pointer; box-shadow: 0 10px 24px rgba(168,85,247,.25); transition: .2s ease; }
.download-submit:hover { filter: brightness(1.09); transform: translateY(-1px); box-shadow: 0 13px 28px rgba(168,85,247,.35); }
.download-submit i { margin-right: 7px; }
.download-toast { position: fixed; right: 20px; bottom: 20px; z-index: 9996; display: flex; align-items: flex-start; gap: 9px; max-width: 340px; padding: 14px 12px 14px 16px; border: 1px solid rgba(134,239,172,.3); border-radius: 13px; background: #153522; color: #dcfce7; font-size: 13px; box-shadow: 0 12px 30px rgba(0,0,0,.3); }
.download-toast i { color: #86efac; }
.download-toast__close { width: 22px; height: 22px; margin: -3px 0 0 2px; flex: 0 0 22px; border: 0; border-radius: 6px; background: transparent; color: #bbf7d0; cursor: pointer; transition: .2s ease; }
.download-toast__close:hover { background: rgba(220,252,231,.15); color: #fff; }
body.download-drawer-open { overflow: hidden; }
@media (max-width: 600px) {
    .download-drawer { width: 100vw; }
    .download-drawer__header { padding: 24px 20px 19px; }
    .download-drawer__body { padding: 22px 20px; }
    .download-drawer__footer { padding: 16px 20px 22px; }
    .download-folder-count { font-size: 10px; }
}
</style>
