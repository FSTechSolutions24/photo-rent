<!-- Folder Navbar -->
<div class="folder-nav">
    <button class="folder-btn active" data-folder="all">All</button>

    @foreach ($gallery->folders as $folder)
        <button 
            class="folder-btn"
            data-folder="folder-{{ $folder->id }}"
        >
            {{ $folder->name }}
        </button>
    @endforeach
</div>

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
                    src="{{ asset('storage/' . $media->path) }}"
                    data-full="{{ asset('storage/' . $media->path) }}"
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
    display: block;
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
    gap: 10px;
    padding: 12px;
    overflow-x: auto;
    background: #0b0b0b;
    position: sticky;
    top: 0;
    z-index: 20;
}

.folder-nav::-webkit-scrollbar {
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
</style>