<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
@php($backgroundUrl = $gallery->background_url)

<main class="luxe-page">
    <header class="luxe-hero">
        @if ($backgroundUrl)<div class="luxe-hero__image" style="background-image: url('{{ $backgroundUrl }}')"></div>@endif
        <div class="luxe-hero__veil"></div>
        <div class="luxe-hero__top"><span>THE PRIVATE COLLECTION</span><button id="luxe-open-download" type="button"><i class="fas fa-download"></i> Download</button></div>
        <div class="luxe-hero__content"><p>EST. {{ now()->format('Y') }}</p><h1>{{ $gallery->name }}</h1><span>{{ $gallery->folders->sum(fn ($folder) => $folder->media->count()) }} carefully captured moments</span></div>
    </header>

    <nav class="luxe-nav" aria-label="Gallery folders">
        <span class="luxe-nav__label">EXPLORE</span>
        <div class="luxe-nav__buttons">
            <button class="is-active" type="button" data-folder="all">All works</button>
            @foreach ($gallery->folders as $folder)<button type="button" data-folder="folder-{{ $folder->id }}">{{ $folder->name }}</button>@endforeach
        </div>
    </nav>

    @include('dashboard.galleries._face_filter', ['faceTheme' => 'luxe'])

    @if (session('download_requested'))
        <div class="luxe-toast"><i class="fas fa-check"></i><span>{{ session('download_requested') }}</span><button type="button" aria-label="Dismiss"><i class="fas fa-times"></i></button></div>
    @endif

    <section class="luxe-gallery" aria-label="{{ $gallery->name }} photos">
        @foreach ($gallery->folders as $folder)
            @foreach ($folder->media as $media)
                <button class="luxe-photo" type="button" data-folder="folder-{{ $folder->id }}" data-full="{{ $media->path }}" aria-label="View photo from {{ $folder->name }}"><span class="luxe-photo__frame"><img src="{{ $media->path }}" alt="" loading="lazy"><i class="fas fa-expand"></i></span></button>
            @endforeach
        @endforeach
    </section>
</main>

<div class="luxe-lightbox" id="luxe-lightbox" aria-hidden="true"><button type="button" aria-label="Close preview"><i class="fas fa-times"></i></button><img src="" alt=""></div>
<div class="luxe-drawer-backdrop" id="luxe-drawer-backdrop"></div>
<aside class="luxe-drawer" id="luxe-drawer" aria-hidden="true">
    <header><div><p>THE PRIVATE COLLECTION</p><h2>Request your photos</h2></div><button type="button" class="luxe-drawer__close" aria-label="Close"><i class="fas fa-times"></i></button></header>
    <form method="POST" action="{{ route('gallery.download', ['photographer_subdomain' => $photographer->subdomain, 'gallery_slug' => $gallery->slug]) }}">@csrf
        <div class="luxe-drawer__body"><p>Choose the folders you would like to receive. We will send your private link by email.</p><label for="luxe-email">Email address <em>*</em></label><input id="luxe-email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">@error('email')<small class="luxe-error">{{ $message }}</small>@enderror
            <div class="luxe-select"><strong>SELECT FOLDERS</strong><label><input id="luxe-all" name="download_all" type="checkbox" value="1" {{ old('download_all') ? 'checked' : '' }}> All photos</label></div>
            <div class="luxe-folders">@forelse ($gallery->folders as $folder)<label><input class="luxe-folder" name="folder_ids[]" type="checkbox" value="{{ $folder->id }}" {{ in_array($folder->id, old('folder_ids', [])) ? 'checked' : '' }}><span><i class="far fa-folder"></i> {{ $folder->name }}</span><small>{{ $folder->media->count() }} {{ Str::plural('photo', $folder->media->count()) }}</small></label>@empty<p>No folders are available for download yet.</p>@endforelse</div>@error('folder_ids')<small class="luxe-error">{{ $message }}</small>@enderror
        </div><footer><button type="submit"><i class="fas fa-download"></i> Request download link</button></footer>
    </form>
</aside>

<script>
(() => {
    const body = document.body, buttons = document.querySelectorAll('.luxe-nav button'), photos = document.querySelectorAll('.luxe-photo');
    const lightbox = document.getElementById('luxe-lightbox'), lightboxImage = lightbox.querySelector('img');
    const closeLightbox = () => { lightbox.classList.remove('is-open'); lightbox.setAttribute('aria-hidden', 'true'); lightboxImage.src = ''; body.classList.remove('luxe-no-scroll'); };
    buttons.forEach(button => button.addEventListener('click', () => { buttons.forEach(item => item.classList.remove('is-active')); button.classList.add('is-active'); photos.forEach(photo => photo.hidden = button.dataset.folder !== 'all' && photo.dataset.folder !== button.dataset.folder); }));
    photos.forEach(photo => photo.addEventListener('click', () => { lightboxImage.src = photo.dataset.full; lightbox.classList.add('is-open'); lightbox.setAttribute('aria-hidden', 'false'); body.classList.add('luxe-no-scroll'); }));
    lightbox.querySelector('button').addEventListener('click', closeLightbox); lightbox.addEventListener('click', event => { if (event.target === lightbox) closeLightbox(); });
    const drawer = document.getElementById('luxe-drawer'), backdrop = document.getElementById('luxe-drawer-backdrop'), openDrawerButton = document.getElementById('luxe-open-download');
    const closeDrawer = () => { drawer.classList.remove('is-open'); backdrop.classList.remove('is-open'); drawer.setAttribute('aria-hidden', 'true'); body.classList.remove('luxe-no-scroll'); };
    const openDrawer = () => { drawer.classList.add('is-open'); backdrop.classList.add('is-open'); drawer.setAttribute('aria-hidden', 'false'); body.classList.add('luxe-no-scroll'); setTimeout(() => document.getElementById('luxe-email').focus(), 220); };
    openDrawerButton.addEventListener('click', openDrawer); drawer.querySelector('.luxe-drawer__close').addEventListener('click', closeDrawer); backdrop.addEventListener('click', closeDrawer);
    const all = document.getElementById('luxe-all'), checks = [...document.querySelectorAll('.luxe-folder')]; all.addEventListener('change', () => checks.forEach(check => check.checked = all.checked)); checks.forEach(check => check.addEventListener('change', () => all.checked = checks.length > 0 && checks.every(item => item.checked)));
    document.querySelector('.luxe-toast button')?.addEventListener('click', event => event.currentTarget.closest('.luxe-toast').remove()); document.addEventListener('keydown', event => { if (event.key === 'Escape') { closeLightbox(); closeDrawer(); } });
    @if ($errors->has('email') || $errors->has('folder_ids')) openDrawer(); @endif
})();
</script>

<style>
*{box-sizing:border-box}body{margin:0;background:#12110f;color:#efe9df;font-family:Inter,system-ui,sans-serif}.luxe-no-scroll{overflow:hidden}.luxe-hero{position:relative;display:flex;min-height:470px;flex-direction:column;justify-content:space-between;overflow:hidden;background:radial-gradient(circle at 75% 25%,#675340,#161412 60%)}.luxe-hero__image,.luxe-hero__veil{position:absolute;inset:0}.luxe-hero__image{background:center/cover}.luxe-hero__veil{background:linear-gradient(90deg,rgba(11,10,9,.78),rgba(11,10,9,.35)),linear-gradient(0deg,rgba(11,10,9,.72),transparent 60%)}.luxe-hero__top,.luxe-hero__content{position:relative;z-index:1;padding-left:clamp(1.3rem,7vw,8rem);padding-right:clamp(1.3rem,7vw,8rem)}.luxe-hero__top{display:flex;justify-content:space-between;align-items:center;padding-top:1.7rem;color:#d6bc91;font-size:.66rem;font-weight:800;letter-spacing:.2em}.luxe-hero__top button,.luxe-drawer footer button{border:1px solid #c9a775;background:rgba(24,21,18,.65);color:#f3dfbd;padding:.72rem 1rem;font:inherit;font-size:.72rem;font-weight:800;letter-spacing:.08em;cursor:pointer}.luxe-hero__top button:hover{background:#c9a775;color:#201b16}.luxe-hero__top i{margin-right:.45rem}.luxe-hero__content{padding-bottom:4.4rem}.luxe-hero__content p{margin:0 0 .8rem;color:#d6bc91;font-size:.65rem;letter-spacing:.23em}.luxe-hero h1{max-width:880px;margin:0;font-family:Georgia,serif;font-size:clamp(3.2rem,8vw,7.4rem);font-weight:400;letter-spacing:-.065em;line-height:.85}.luxe-hero__content span{display:block;margin-top:1.5rem;color:#e8ded0;font-size:.86rem}.luxe-nav{display:flex;align-items:center;gap:2rem;padding:1.1rem clamp(1.3rem,7vw,8rem);border-bottom:1px solid #302c27;background:#171512}.luxe-nav__label{color:#ad9066;font-size:.63rem;font-weight:800;letter-spacing:.16em}.luxe-nav__buttons{display:flex;gap:.35rem;overflow-x:auto}.luxe-nav button{border:0;background:none;color:#aaa198;padding:.55rem .72rem;font:inherit;font-size:.82rem;white-space:nowrap;cursor:pointer}.luxe-nav button.is-active,.luxe-nav button:hover{color:#f5e6ce}.luxe-nav button.is-active{border-bottom:1px solid #c9a775}.luxe-gallery{columns:3 280px;gap:1.4rem;padding:clamp(1.5rem,5vw,5rem) clamp(1.3rem,7vw,8rem)}.luxe-photo{display:block;width:100%;margin:0 0 1.4rem;padding:0;border:0;background:transparent;break-inside:avoid;cursor:zoom-in}.luxe-photo[hidden]{display:none}.luxe-photo__frame{position:relative;display:block;padding:8px;background:#2a2520;box-shadow:0 18px 35px rgba(0,0,0,.25);transition:transform .35s,background .35s}.luxe-photo img{display:block;width:100%;height:auto}.luxe-photo i{position:absolute;right:18px;bottom:18px;display:grid;width:32px;height:32px;place-items:center;opacity:0;border-radius:50%;background:#ead4ad;color:#2a2118;font-size:.7rem;transition:.25s}.luxe-photo:hover .luxe-photo__frame{transform:translateY(-5px);background:#b79a70}.luxe-photo:hover i{opacity:1}.luxe-lightbox,.luxe-drawer-backdrop{position:fixed;inset:0;z-index:20;opacity:0;pointer-events:none;transition:opacity .25s}.luxe-lightbox{display:grid;place-items:center;padding:0;background:rgba(5,4,3,.97)}.luxe-lightbox.is-open,.luxe-drawer-backdrop.is-open{opacity:1;pointer-events:auto}.luxe-lightbox img{display:block;width:auto!important;height:auto!important;max-width:95vw;max-height:95vh;max-height:95dvh;object-fit:contain;border-radius:12px;box-shadow:0 30px 80px rgba(0,0,0,.8)}.luxe-lightbox button{position:absolute;z-index:1;top:1.5rem;right:1.5rem;width:42px;height:42px;border:1px solid #8d7658;border-radius:50%;background:none;color:#fff;cursor:pointer}.luxe-drawer-backdrop{z-index:29;background:rgba(0,0,0,.62);backdrop-filter:blur(3px)}.luxe-drawer{position:fixed;z-index:30;top:0;right:0;display:flex;width:min(450px,100%);height:100dvh;flex-direction:column;transform:translateX(100%);background:#1c1915;color:#f0e6d8;transition:transform .35s}.luxe-drawer.is-open{transform:translateX(0)}.luxe-drawer header{display:flex;justify-content:space-between;padding:2rem;border-bottom:1px solid #3c342b}.luxe-drawer header p{margin:0 0 .5rem;color:#c6a675;font-size:.65rem;font-weight:800;letter-spacing:.15em}.luxe-drawer h2{margin:0;font-family:Georgia,serif;font-weight:400;font-size:2rem}.luxe-drawer__close{width:38px;height:38px;border:1px solid #62533f;border-radius:50%;background:none;color:#f0e6d8;cursor:pointer}.luxe-drawer form{display:flex;min-height:0;flex:1;flex-direction:column}.luxe-drawer__body{flex:1;overflow:auto;padding:2rem}.luxe-drawer__body>p{margin-top:0;color:#b9afa3;line-height:1.6}.luxe-drawer label[for=luxe-email]{display:block;margin:1.5rem 0 .5rem;font-size:.82rem;font-weight:700}.luxe-drawer em{color:#e99c8e}.luxe-drawer input[type=email]{width:100%;padding:.82rem;border:1px solid #514535;background:#25211c;color:#fff;font:inherit}.luxe-select{display:flex;justify-content:space-between;align-items:center;margin:2rem 0 .7rem;color:#d2b080;font-size:.68rem;letter-spacing:.11em}.luxe-select label{color:#e7ded2;font-size:.78rem;letter-spacing:0}.luxe-folders{border-top:1px solid #3c342b}.luxe-folders label{display:flex;justify-content:space-between;gap:1rem;padding:1rem 0;border-bottom:1px solid #3c342b;cursor:pointer}.luxe-folders i{margin-right:.5rem;color:#c9a775}.luxe-folders small{color:#a99d90}.luxe-error{display:block;margin-top:.5rem;color:#f09a8e}.luxe-drawer footer{padding:1.25rem 2rem;border-top:1px solid #3c342b}.luxe-drawer footer button{width:100%;background:#c9a775;color:#211a13}.luxe-toast{position:fixed;right:1.2rem;bottom:1.2rem;z-index:10;display:flex;align-items:center;gap:.6rem;padding:.85rem 1rem;background:#d7bc90;color:#2a2118;box-shadow:0 12px 30px rgba(0,0,0,.3)}.luxe-toast button{margin-left:auto;border:0;background:none;cursor:pointer}@media(max-width:600px){.luxe-hero{min-height:420px}.luxe-hero__top{font-size:.56rem}.luxe-hero__top button{padding:.6rem;font-size:.62rem}.luxe-nav{gap:1rem}.luxe-nav__label{display:none}.luxe-gallery{columns:2 145px;gap:.7rem;padding:1rem}.luxe-photo{margin-bottom:.7rem}.luxe-photo__frame{padding:4px}.luxe-photo i{display:none}}
</style>
