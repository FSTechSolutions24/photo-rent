<div class="gallery">
    @foreach ($gallery->folders as $folder)
        @foreach ($folder->media as $media)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $media->path) }}" alt="">
            </div>
        @endforeach
    @endforeach
</div>


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
    transition: transform 0.3s ease, box-shadow 0.3s ease;
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



</style>