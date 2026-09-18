@if($gallery->face_filter_published && $faceClusters->isNotEmpty())
    @php
        $galleryRoute = ['photographer_subdomain' => $photographer->subdomain, 'gallery_slug' => $gallery->slug];
        $faceTheme = $faceTheme ?? 'light';
        $visibleFaceLimit = 10;
        $hasMoreFaces = $faceClusters->count() > $visibleFaceLimit;
        $overflowFaceSelected = $selectedFaceUuid
            && ! $faceClusters->take($visibleFaceLimit)->contains('uuid', $selectedFaceUuid);
    @endphp
    <section class="face-picker face-picker--{{ $faceTheme }}" aria-labelledby="face-picker-title">
        <div class="face-picker__heading">
            <div>
                <span>FIND YOUR PHOTOS</span>
                <h2 id="face-picker-title">Choose a face</h2>
            </div>
        </div>
        <div class="face-picker__row">
            <a class="face-picker__item {{ $selectedFaceUuid ? '' : 'is-active' }}" href="{{ route('gallery.show', $galleryRoute) }}" aria-label="Show all photographs">
                <span class="face-picker__all"><i class="fas fa-images"></i></span>
                <small>All</small>
            </a>
            @foreach($faceClusters->take($visibleFaceLimit) as $cluster)
                <a class="face-picker__item {{ $selectedFaceUuid === $cluster->uuid ? 'is-active' : '' }}"
                   href="{{ route('gallery.show', $galleryRoute + ['face' => $cluster->uuid]) }}"
                   aria-label="Show {{ $cluster->face_count }} photographs containing this person">
                    <img src="{{ route('gallery.faces.thumbnail', $galleryRoute + ['cluster_uuid' => $cluster->uuid]) }}" alt="Anonymous face">
                    <small>{{ $cluster->face_count }} {{ Str::plural('match', $cluster->face_count) }}</small>
                </a>
            @endforeach
            @if($hasMoreFaces)
                <button class="face-picker__item face-picker__more {{ $overflowFaceSelected ? 'is-active' : '' }}" id="open-face-drawer" type="button" aria-controls="face-drawer" aria-expanded="false">
                    <span class="face-picker__more-icon">
                        <i class="fas fa-user-friends"></i>
                        <b>+{{ $faceClusters->count() - $visibleFaceLimit }}</b>
                    </span>
                    <small>More faces</small>
                </button>
            @endif
        </div>
    </section>

    @if($hasMoreFaces)
        <div class="face-drawer-backdrop" id="face-drawer-backdrop"></div>
        <aside class="face-drawer" id="face-drawer" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="face-drawer-title">
            <div class="face-drawer__accent"></div>
            <div class="face-drawer__header">
                <div>
                    <p class="face-drawer__eyebrow">FIND YOUR PHOTOS</p>
                    <h2 id="face-drawer-title">Choose your face</h2>
                </div>
                <button class="face-drawer__close" type="button" aria-label="Close faces panel">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="face-drawer__body">
                <p class="face-drawer__intro">Select a face to see every photograph of that person in this gallery.</p>
                <div class="face-drawer__grid">
                    <a class="face-drawer__item {{ $selectedFaceUuid ? '' : 'is-active' }}" href="{{ route('gallery.show', $galleryRoute) }}">
                        <span class="face-drawer__all"><i class="fas fa-images"></i></span>
                        <span><strong>All photos</strong><small>Clear the face filter</small></span>
                    </a>
                    @foreach($faceClusters as $cluster)
                        <a class="face-drawer__item {{ $selectedFaceUuid === $cluster->uuid ? 'is-active' : '' }}"
                           href="{{ route('gallery.show', $galleryRoute + ['face' => $cluster->uuid]) }}"
                           aria-label="Show {{ $cluster->face_count }} photographs containing this person">
                            <img src="{{ route('gallery.faces.thumbnail', $galleryRoute + ['cluster_uuid' => $cluster->uuid]) }}" alt="Anonymous face" loading="lazy">
                            <span><strong>Person</strong><small>{{ $cluster->face_count }} {{ Str::plural('match', $cluster->face_count) }}</small></span>
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>
    @endif

    <style>
        .face-picker{--face-bg:#fff;--face-text:#222;--face-muted:#687078;--face-border:#dfe3e6;--face-active:#2878c8;padding:1.25rem clamp(1rem,5vw,5rem);border-bottom:1px solid var(--face-border);background:var(--face-bg);color:var(--face-text);font-family:Inter,system-ui,sans-serif}.face-picker--dark{--face-bg:#181818;--face-text:#f4f4f4;--face-muted:#aaa;--face-border:#333;--face-active:#d3b07b}.face-picker--luxe{--face-bg:#171512;--face-text:#f3e7d6;--face-muted:#aa9f91;--face-border:#352f29;--face-active:#c9a775}.face-picker__heading{display:flex;align-items:end;justify-content:space-between;gap:1rem;margin-bottom:1rem}.face-picker__heading span{display:block;margin-bottom:.25rem;color:var(--face-active);font-size:.62rem;font-weight:800;letter-spacing:.16em}.face-picker__heading h2{margin:0;font-size:1.15rem}.face-picker__heading p{margin:0;color:var(--face-muted);font-size:.75rem}.face-picker__row{display:flex;gap:.85rem;overflow-x:auto;padding:.15rem .15rem .55rem;scrollbar-width:thin}.face-picker__item{display:flex;min-width:74px;padding:0;border:0;background:transparent;flex-direction:column;align-items:center;gap:.4rem;color:var(--face-muted);font:inherit;font-size:.72rem;text-decoration:none;cursor:pointer}.face-picker__item img,.face-picker__all,.face-picker__more-icon{display:grid;width:64px;height:64px;place-items:center;border:3px solid transparent;border-radius:50%;object-fit:cover;background:var(--face-border);transition:.2s}.face-picker__item:hover,.face-picker__item.is-active{color:var(--face-text);text-decoration:none}.face-picker__item:hover img,.face-picker__item:hover .face-picker__all,.face-picker__item:hover .face-picker__more-icon,.face-picker__item.is-active img,.face-picker__item.is-active .face-picker__all,.face-picker__item.is-active .face-picker__more-icon{border-color:var(--face-active);transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,0,0,.16)}.face-picker__item small{max-width:76px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.face-picker__more-icon{position:relative;color:var(--face-text);font-size:1rem}.face-picker__more-icon b{position:absolute;right:-6px;bottom:-3px;min-width:25px;padding:3px 5px;border:2px solid var(--face-bg);border-radius:999px;background:var(--face-active);color:var(--face-bg);font-size:.6rem;line-height:1;text-align:center}
        .face-drawer-backdrop{position:fixed;inset:0;background:rgba(3,6,12,.66);backdrop-filter:blur(5px);opacity:0;pointer-events:none;transition:opacity .32s ease;z-index:10007}.face-drawer-backdrop.is-visible{opacity:1;pointer-events:auto}.face-drawer{position:fixed;top:0;right:0;width:min(470px,100vw);height:100dvh;display:flex;flex-direction:column;overflow:hidden;background:#101218;color:#f8fafc;font-family:Inter,system-ui,sans-serif;box-shadow:-24px 0 70px rgba(0,0,0,.45);transform:translateX(104%);transition:transform .38s cubic-bezier(.22,1,.36,1);z-index:10008}.face-drawer.is-open{transform:translateX(0)}.face-drawer__accent{height:4px;flex-shrink:0;background:linear-gradient(90deg,#a78bfa,#ec4899,#fbbf24)}.face-drawer__header{display:flex;align-items:flex-start;justify-content:space-between;padding:29px 30px 22px;border-bottom:1px solid #282c35}.face-drawer__eyebrow{margin:0 0 7px;color:#a78bfa;font-size:10px;letter-spacing:.18em;font-weight:800}.face-drawer h2{margin:0;font-size:25px;letter-spacing:-.04em;line-height:1.1}.face-drawer__close{display:grid;width:36px;height:36px;place-items:center;border:1px solid #333844;border-radius:50%;background:#1a1d25;color:#f8fafc;cursor:pointer;transition:.2s ease}.face-drawer__close:hover{background:#f8fafc;color:#101218;transform:rotate(90deg)}.face-drawer__body{min-height:0;flex:1;overflow-y:auto;padding:25px 30px}.face-drawer__intro{margin:0 0 22px;color:#a6adbb;font-size:14px;line-height:1.65}.face-drawer__grid{display:grid;gap:9px}.face-drawer__item{display:flex;min-height:76px;align-items:center;gap:14px;padding:10px 14px;border:1px solid #2c303a;border-radius:13px;background:#171a21;color:#edf0f5;text-decoration:none;transition:.2s ease}.face-drawer__item:hover,.face-drawer__item.is-active{border-color:#a78bfa;background:#1c2029;color:#fff;text-decoration:none;transform:translateX(2px)}.face-drawer__item img,.face-drawer__all{display:grid;width:54px;height:54px;flex:0 0 54px;place-items:center;border:2px solid #3b404c;border-radius:50%;object-fit:cover;background:#282d37;color:#d8b4fe}.face-drawer__item.is-active img,.face-drawer__item.is-active .face-drawer__all{border-color:#a78bfa;box-shadow:0 0 0 3px rgba(167,139,250,.14)}.face-drawer__item>span:not(.face-drawer__all){display:flex;min-width:0;flex:1;flex-direction:column;gap:4px}.face-drawer__item strong{font-size:14px}.face-drawer__item small{color:#8f97a5;font-size:11px}.face-drawer__item>i{color:#666e7e;font-size:11px}body.face-drawer-open{overflow:hidden}
        @media(max-width:600px){.face-picker__heading{align-items:start;flex-direction:column}.face-picker__heading p{max-width:290px}.face-picker__item{min-width:66px}.face-picker__item img,.face-picker__all,.face-picker__more-icon{width:58px;height:58px}.face-drawer{width:100vw}.face-drawer__header{padding:24px 20px 19px}.face-drawer__body{padding:22px 20px}}
    </style>

    @if($hasMoreFaces)
        <script>
            (() => {
                const drawer = document.getElementById('face-drawer');
                const backdrop = document.getElementById('face-drawer-backdrop');
                const openButton = document.getElementById('open-face-drawer');
                const closeButton = drawer.querySelector('.face-drawer__close');

                const closeDrawer = () => {
                    drawer.classList.remove('is-open');
                    backdrop.classList.remove('is-visible');
                    drawer.setAttribute('aria-hidden', 'true');
                    openButton.setAttribute('aria-expanded', 'false');
                    document.body.classList.remove('face-drawer-open');
                    openButton.focus();
                };
                const openDrawer = () => {
                    drawer.classList.add('is-open');
                    backdrop.classList.add('is-visible');
                    drawer.setAttribute('aria-hidden', 'false');
                    openButton.setAttribute('aria-expanded', 'true');
                    document.body.classList.add('face-drawer-open');
                    setTimeout(() => closeButton.focus(), 250);
                };

                openButton.addEventListener('click', openDrawer);
                closeButton.addEventListener('click', closeDrawer);
                backdrop.addEventListener('click', closeDrawer);
                document.addEventListener('keydown', event => {
                    if (event.key === 'Escape' && drawer.classList.contains('is-open')) closeDrawer();
                });
            })();
        </script>
    @endif
@endif
