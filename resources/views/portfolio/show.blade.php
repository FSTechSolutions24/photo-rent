@php
    $title = $photographer->portfolio_title ?: $photographer->user->name;
    // Legacy portfolio styles use the current dark design. Only the new light
    // option changes the palette; the portfolio structure remains identical.
    $theme = $photographer->portfolio_theme === 'light' ? 'light' : 'bold';
    $featured = $galleries->first();
    $featuredImage = $photographer->portfolio_cover_url ?: $featured?->thumbnail_url;
    $contactEmail = $photographer->portfolio_contact_email ?: $photographer->user->email;
    $footerText = $photographer->portfolio_footer_text ?: 'Photography by ' . $title;
    $instagram = ltrim((string) $photographer->portfolio_instagram, '@/');
    $galleryCount = $galleries->count();
    $avatarUrl = $photographer->user->avatar_url;
    $ownerNameParts = preg_split('/\s+/', trim($photographer->user->name ?? 'Photographer'));
    $ownerInitials = collect($ownerNameParts)->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        :root{--ink:{{ $photographer->portfolio_primary_color }};--accent:{{ $photographer->portfolio_accent_color }};--paper:#f8f7f4}*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--paper);color:#20242a;font-family:Arial,sans-serif}.nav{display:flex;align-items:center;justify-content:space-between;max-width:1320px;margin:auto;padding:1.7rem 2.2rem}.brand{font-size:.75rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase}.nav a{padding-bottom:.25rem;border-bottom:1px solid var(--accent);color:#20242a;font-size:.78rem;font-weight:700;letter-spacing:.08em;text-decoration:none;text-transform:uppercase}.hero{display:grid;grid-template-columns:1.05fr .95fr;min-height:610px;background:var(--ink);color:#fff}.hero-copy{display:flex;flex-direction:column;justify-content:center;max-width:680px;padding:6vw 7vw}.eyebrow{margin:0 0 1.2rem;color:var(--accent);font-size:.7rem;font-weight:800;letter-spacing:.2em;text-transform:uppercase}.hero h1,.section-title,.contact h2{margin:0;font-family:Georgia,serif;font-size:clamp(3.4rem,6.5vw,6.8rem);font-weight:400;letter-spacing:-.055em;line-height:.93}.hero p:not(.eyebrow){max-width:470px;margin:2rem 0 0;color:rgba(255,255,255,.78);font-size:1.02rem;line-height:1.8}.hero-image{min-height:360px;background:linear-gradient(135deg,rgba(8,16,25,.1),rgba(8,16,25,.35)),var(--accent) center/cover no-repeat}.hero-image--empty{background:linear-gradient(135deg,var(--accent),#1c2938)}.work{max-width:1320px;margin:auto;padding:8rem 2.2rem}.section-head{display:flex;align-items:end;justify-content:space-between;gap:1rem;margin-bottom:3.3rem}.section-head .section-title{font-size:clamp(2.5rem,4vw,4.6rem)}.section-head p{max-width:240px;margin:0;color:#6c7279;font-size:.9rem;line-height:1.6}.grid{display:grid;grid-template-columns:repeat(12,1fr);gap:1.6rem}.card{grid-column:span 4;overflow:hidden;background:#fff;color:#20242a;text-decoration:none}.card:first-child{grid-column:span 8}.card-media{display:block;width:100%;aspect-ratio:4/3;object-fit:cover;transition:transform .5s ease}.card:first-child .card-media{aspect-ratio:16/9}.placeholder{background:linear-gradient(135deg,var(--ink),var(--accent))}.card:hover .card-media{transform:scale(1.035)}.card-info{display:flex;justify-content:space-between;align-items:start;padding:1.15rem 1.2rem 1.3rem}.card-info h3{margin:0;font-family:Georgia,serif;font-size:1.3rem;font-weight:400}.card-info small{color:#858c93;font-size:.72rem;text-transform:uppercase;letter-spacing:.08em}.empty{grid-column:1/-1;padding:5rem 1rem;text-align:center;color:#777}.contact{padding:7rem 2rem;background:#20242a;color:#fff;text-align:center}.contact h2{max-width:800px;margin:0 auto 2rem;font-size:clamp(2.7rem,5vw,5rem)}.contact a{color:var(--accent);font-size:1rem;font-weight:700}.footer{padding:1.6rem;text-align:center;color:#7b8087;font-size:.72rem;letter-spacing:.07em;text-transform:uppercase}.theme-minimal{background:#fff}.theme-minimal .nav{border-bottom:1px solid #e6e7e8}.theme-minimal .hero{display:block;min-height:0;background:#fff;color:#20242a;text-align:center}.theme-minimal .hero-copy{max-width:850px;margin:auto;padding:9rem 2rem 6rem}.theme-minimal .hero p:not(.eyebrow){color:#656b72;margin-left:auto;margin-right:auto}.theme-minimal .hero-image{display:none}.theme-minimal .grid{gap:2px}.theme-minimal .card,.theme-minimal .card:first-child{grid-column:span 4}.theme-minimal .card:first-child .card-media{aspect-ratio:4/3}.theme-minimal .card-info{display:block;text-align:center}.theme-minimal .card-info small{display:block;margin-top:.5rem}.theme-minimal .work{max-width:1450px}.theme-bold{background:#101722;color:#f8f5ef}.theme-bold .nav{color:#fff}.theme-bold .nav a{color:#fff}.theme-bold .hero{display:block;min-height:690px;background:linear-gradient(90deg,rgba(8,14,22,.82),rgba(8,14,22,.15)),var(--ink) center/cover no-repeat}.theme-bold .hero-copy{min-height:690px;max-width:840px}.theme-bold .work{max-width:1450px}.theme-bold .section-head p{color:#aeb5bc}.theme-bold .grid{gap:.9rem}.theme-bold .card,.theme-bold .card:first-child{grid-column:span 4;border-radius:.65rem}.theme-bold .card:first-child{grid-column:span 8;grid-row:span 2}.theme-bold .card:first-child .card-media{height:100%;aspect-ratio:auto}.theme-bold .card-info{display:none}.theme-bold .contact{background:var(--accent);color:#161b22}.theme-bold .contact a{color:#161b22}@media(max-width:760px){.nav{padding:1.2rem}.hero{grid-template-columns:1fr}.hero-copy{padding:5rem 1.5rem}.hero-image{min-height:300px}.work{padding:4.5rem 1.2rem}.section-head{display:block;margin-bottom:2.2rem}.section-head p{margin-top:1rem}.grid,.theme-minimal .grid{grid-template-columns:1fr}.card,.card:first-child,.theme-minimal .card,.theme-minimal .card:first-child,.theme-bold .card,.theme-bold .card:first-child{grid-column:1;grid-row:auto}.card:first-child .card-media,.theme-bold .card:first-child .card-media{height:auto;aspect-ratio:4/3}.theme-bold .hero,.theme-bold .hero-copy{min-height:560px}}
        .contact{padding:0;background:linear-gradient(135deg,var(--ink),#101722);color:#fff;text-align:left}.contact-inner{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(340px,.95fr);gap:6rem;max-width:1320px;margin:auto;padding:7.5rem 2.2rem}.contact-intro h2{max-width:690px;margin:0;font-size:clamp(2.8rem,5vw,5.5rem)}.contact-copy{max-width:520px;margin:1.8rem 0 0;color:rgba(255,255,255,.62);font-size:.96rem;line-height:1.8}.contact-list{align-self:center;border-top:1px solid rgba(255,255,255,.16)}.contact-item{display:grid!important;grid-template-columns:44px minmax(0,1fr) 24px;align-items:center;gap:1rem;min-width:0!important;padding:1.35rem .2rem!important;border:0!important;border-bottom:1px solid rgba(255,255,255,.16)!important;color:#fff!important;text-decoration:none;transition:padding .2s ease,background .2s ease}.contact-item:hover{padding-left:.65rem!important;background:rgba(255,255,255,.035)}.contact-icon{display:grid!important;width:44px;height:44px;margin:0!important;place-items:center;border:1px solid rgba(255,255,255,.2);border-radius:50%;color:var(--accent)!important}.contact-icon svg{width:18px;height:18px}.contact-text{display:block!important;margin:0!important}.contact-text small{display:block;margin:0 0 .3rem;color:rgba(255,255,255,.48);font-size:.62rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase}.contact-text strong{display:block;overflow:hidden;color:#fff;font-size:.96rem;font-weight:500;text-overflow:ellipsis;white-space:nowrap}.contact-arrow{margin:0!important;color:var(--accent)!important;font-size:1.15rem;transition:transform .2s ease}.contact-item:hover .contact-arrow{transform:translate(3px,-3px)}.footer{display:flex;justify-content:space-between;gap:1rem;padding:1.7rem max(2.2rem,calc((100vw - 1320px)/2 + 2.2rem));border-top:1px solid rgba(255,255,255,.08);background:#0d141c;color:#89939d}.theme-bold .contact{background:linear-gradient(135deg,var(--ink),#101722);color:#fff}.theme-bold .contact-item{border-color:rgba(255,255,255,.16)!important}@media(max-width:900px){.contact-inner{grid-template-columns:1fr;gap:3.5rem;padding:5rem 1.5rem}}@media(max-width:760px){.footer{align-items:center;flex-direction:column;padding:1.5rem;text-align:center}.contact-text strong{font-size:.88rem}}
        .showcase-head{align-items:flex-end}.slider-controls{display:flex;align-items:center;gap:.65rem}.slider-button{display:grid;width:46px;height:46px;padding:0;place-items:center;border:1px solid rgba(255,255,255,.18);border-radius:50%;background:transparent;color:#fff;cursor:pointer;transition:.2s}.slider-button:hover:not(:disabled){border-color:var(--accent);background:var(--accent);color:#111820;transform:translateY(-2px)}.slider-button:disabled{cursor:default;opacity:.3}.slider-button svg{width:18px;height:18px}.project-slider{position:relative}.project-track{display:flex;gap:1.25rem;overflow-x:auto;padding:0 0 1rem;scroll-behavior:smooth;scroll-snap-type:x mandatory;scrollbar-width:none}.project-track::-webkit-scrollbar{display:none}.project-track--single{display:block;overflow:visible;padding-bottom:0}.project-slide{position:relative;flex:0 0 min(82%,980px);overflow:hidden;border-radius:.8rem;background:#1b2530;color:#fff;text-decoration:none;scroll-snap-align:start}.project-track--single .project-slide{display:block;width:100%;max-width:1120px;margin:auto}.project-media{display:block;width:100%;aspect-ratio:16/9;object-fit:cover;transition:transform .7s cubic-bezier(.2,.7,.2,1)}.project-slide:hover .project-media{transform:scale(1.025)}.project-shade{position:absolute;inset:0;background:linear-gradient(180deg,rgba(5,10,16,.03) 35%,rgba(5,10,16,.88) 100%)}.project-copy{position:absolute;right:0;bottom:0;left:0;padding:clamp(1.4rem,4vw,3.2rem)}.project-meta{display:block;margin-bottom:.7rem;color:var(--accent);font-size:.68rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase}.project-copy h3{max-width:760px;margin:0;font-family:Georgia,serif;font-size:clamp(2rem,4vw,4.4rem);font-weight:400;letter-spacing:-.035em;line-height:1}.project-link{display:inline-flex;align-items:center;gap:.55rem;margin-top:1.2rem;color:#fff;font-size:.72rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.project-link span{font-size:1rem;transition:transform .2s}.project-slide:hover .project-link span{transform:translateX(4px)}.slider-status{display:flex;align-items:center;gap:1rem;margin-top:1rem}.slider-counter{min-width:52px;color:#89939d;font-size:.72rem;letter-spacing:.12em}.slider-progress{position:relative;flex:1;height:1px;overflow:hidden;background:rgba(255,255,255,.14)}.slider-progress span{display:block;width:0;height:100%;background:var(--accent);transition:width .3s}.portfolio-empty{padding:5rem 1rem;border:1px solid rgba(255,255,255,.12);border-radius:.8rem;color:#929ca6;text-align:center}@media(max-width:760px){.showcase-head{display:flex}.showcase-head>p{display:none}.slider-button{width:40px;height:40px}.project-slide{flex-basis:92%}.project-media{aspect-ratio:4/5}.project-track--single .project-media{aspect-ratio:4/5}.project-copy{padding:1.4rem}.project-copy h3{font-size:2.15rem}}
        /* Adaptive portfolio slider contrast for light and dark themes. */
        .slider-button{border-color:rgba(32,36,42,.22);color:#20242a}
        .slider-progress{background:rgba(32,36,42,.16)}
        .portfolio-empty{border-color:rgba(32,36,42,.14)}
        .theme-bold .slider-button{border-color:rgba(255,255,255,.2);color:#fff}
        .theme-bold .slider-progress{background:rgba(255,255,255,.14)}
        .theme-bold .portfolio-empty{border-color:rgba(255,255,255,.12)}
        /* Light Showcase keeps the Dark Showcase layout and changes only its palette. */
        .theme-light{background:#f8f7f4;color:#20242a}
        .theme-light .nav{color:#20242a}
        .theme-light .nav a{color:#20242a}
        .theme-light .hero{display:block;min-height:690px;background:#e9e6df center/cover no-repeat;color:#20242a}
        .theme-light .hero-copy{min-height:690px;max-width:840px}
        .theme-light .hero p:not(.eyebrow){color:rgba(32,36,42,.76)}
        .theme-light .work{max-width:1450px}
        .theme-light .section-head p{color:#6c7279}
        .theme-light .grid{gap:.9rem}
        .theme-light .card,.theme-light .card:first-child{grid-column:span 4;border-radius:.65rem}
        .theme-light .card:first-child{grid-column:span 8;grid-row:span 2}
        .theme-light .card:first-child .card-media{height:100%;aspect-ratio:auto}
        .theme-light .card-info{display:none}
        .theme-light .contact{background:linear-gradient(135deg,#f1eee8,#e2e5e8);color:#20242a}
        .theme-light .contact-copy{color:rgba(32,36,42,.64)}
        .theme-light .contact-list{border-color:rgba(32,36,42,.16)}
        .theme-light .contact-item{border-color:rgba(32,36,42,.16)!important;color:#20242a!important}
        .theme-light .contact-item:hover{background:rgba(32,36,42,.035)}
        .theme-light .contact-icon{border-color:rgba(32,36,42,.2)}
        .theme-light .contact-text small{color:rgba(32,36,42,.5)}
        .theme-light .contact-text strong{color:#20242a}
        .theme-light .footer{border-color:rgba(32,36,42,.1);background:#e5e3dd;color:#697078}
        .nav,.contact-inner{max-width:1450px}
        .footer{padding-right:max(2.2rem,calc((100vw - 1450px)/2 + 2.2rem));padding-left:max(2.2rem,calc((100vw - 1450px)/2 + 2.2rem))}
        .theme-bold .hero-copy,.theme-light .hero-copy{width:100%;max-width:1450px;margin:0 auto;padding-right:2.2rem;padding-left:2.2rem}
        .theme-bold .hero-copy-inner,.theme-light .hero-copy-inner{width:100%;max-width:840px}
        .portfolio-avatar-wrap{position:relative;width:118px;height:118px;margin:0 0 2.1rem 7px}
        .portfolio-avatar-wrap::before{position:absolute;inset:-8px;border:1px solid rgba(255,255,255,.24);border-radius:50%;content:''}
        .portfolio-avatar{display:block;width:118px;height:118px;overflow:hidden;border:4px solid rgba(255,255,255,.9);border-radius:50%;object-fit:cover;box-shadow:0 18px 42px rgba(0,0,0,.3)}
        .portfolio-avatar--initials{display:grid;place-items:center;background:linear-gradient(145deg,var(--accent),var(--ink));color:#fff;font-family:Arial,sans-serif;font-size:2.2rem;font-weight:800;letter-spacing:-.05em}
        .portfolio-avatar-badge{position:absolute;right:-1px;bottom:3px;display:grid;width:35px;height:35px;place-items:center;border:4px solid var(--ink);border-radius:50%;background:var(--accent);color:#111820;box-shadow:0 6px 14px rgba(0,0,0,.24)}
        .portfolio-avatar-badge svg{width:15px;height:15px}
        .theme-light .portfolio-avatar-wrap::before{border-color:rgba(32,36,42,.2)}
        .theme-light .portfolio-avatar{border-color:rgba(255,255,255,.95);box-shadow:0 18px 42px rgba(31,36,42,.2)}
        .theme-light .portfolio-avatar-badge{border-color:#e9e6df}
        @media(max-width:760px){.theme-bold .hero-copy,.theme-light .hero-copy{padding-right:1.5rem;padding-left:1.5rem}.footer{padding-right:1.5rem;padding-left:1.5rem}.theme-light .hero,.theme-light .hero-copy{min-height:560px}.theme-light .card,.theme-light .card:first-child{grid-column:1;grid-row:auto}.theme-light .card:first-child .card-media{height:auto;aspect-ratio:4/3}}
        @media(max-width:760px){.portfolio-avatar-wrap{width:96px;height:96px;margin-bottom:1.7rem}.portfolio-avatar{width:96px;height:96px}.portfolio-avatar-badge{width:31px;height:31px}.portfolio-avatar-wrap::before{inset:-6px}}
    </style>
</head>
<body class="theme-{{ $theme }}">
    <nav class="nav"><span class="brand">{{ $title }}</span>@if($photographer->portfolio_show_contact)<a href="#contact">Contact</a>@endif</nav>
    <header class="hero" @if(in_array($theme, ['bold', 'light'], true) && $featuredImage) style="background-image:{{ $theme === 'light' ? 'linear-gradient(90deg,rgba(255,255,255,.9),rgba(255,255,255,.28))' : 'linear-gradient(90deg,rgba(8,14,22,.82),rgba(8,14,22,.15))' }},url('{{ $featuredImage }}')" @endif>
        <div class="hero-copy">
            <div class="hero-copy-inner">
                <div class="portfolio-avatar-wrap">
                    @if($avatarUrl)
                        <img class="portfolio-avatar" src="{{ $avatarUrl }}" alt="{{ $photographer->user->name }}">
                    @else
                        <span class="portfolio-avatar portfolio-avatar--initials" aria-hidden="true">{{ $ownerInitials ?: 'P' }}</span>
                    @endif
                    <span class="portfolio-avatar-badge" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h3l1.4-2h7.2L17 7h3v12H4z"/><circle cx="12" cy="13" r="3.5"/></svg>
                    </span>
                </div>
                <p class="eyebrow">Photography portfolio</p>
                <h1>{{ $title }}</h1>
                @if($photographer->portfolio_bio)<p>{{ $photographer->portfolio_bio }}</p>@endif
            </div>
        </div>
        @if(! in_array($theme, ['bold', 'light'], true))<div class="hero-image {{ $featuredImage ? '' : 'hero-image--empty' }}" @if($featuredImage) style="background-image:url('{{ $featuredImage }}')" @endif></div>@endif
    </header>
    <main class="work">
        <div class="section-head showcase-head">
            <div><p class="eyebrow">Selected stories</p><h2 class="section-title">A collection<br>of moments.</h2></div>
            @if($galleryCount > 1)
                <div class="slider-controls">
                    <button class="slider-button" id="portfolioPrev" type="button" aria-label="Previous gallery"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m15 18-6-6 6-6"/></svg></button>
                    <button class="slider-button" id="portfolioNext" type="button" aria-label="Next gallery"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="m9 18 6-6-6-6"/></svg></button>
                </div>
            @else
                <p>Explore a curated selection of stories, crafted with care and intention.</p>
            @endif
        </div>
        @if($galleryCount)
            <div class="project-slider" id="portfolioSlider">
                <div class="project-track {{ $galleryCount === 1 ? 'project-track--single' : '' }}" id="portfolioTrack">
                    @foreach($galleries as $gallery)
                        <a class="project-slide" href="{{ route('gallery.show', ['photographer_subdomain' => $photographer->subdomain, 'gallery_slug' => $gallery->slug]) }}">
                            @if($gallery->thumbnail_url)
                                <img class="project-media" src="{{ $gallery->thumbnail_url }}" alt="{{ $gallery->name }}">
                            @else
                                <span class="project-media placeholder"></span>
                            @endif
                            <span class="project-shade"></span>
                            <span class="project-copy">
                                <span class="project-meta">{{ $gallery->session->name ?? 'Featured gallery' }}</span>
                                <h3>{{ $gallery->name }}</h3>
                                <span class="project-link">View full story <span>→</span></span>
                            </span>
                        </a>
                    @endforeach
                </div>
                @if($galleryCount > 1)
                    <div class="slider-status"><span class="slider-counter"><b id="portfolioCurrent">01</b> / {{ str_pad($galleryCount, 2, '0', STR_PAD_LEFT) }}</span><span class="slider-progress"><span id="portfolioProgress"></span></span></div>
                @endif
            </div>
        @else
            <div class="portfolio-empty">New public galleries will appear here soon.</div>
        @endif
    </main>
    @if($photographer->portfolio_show_contact)
        <section class="contact" id="contact">
            <div class="contact-inner">
                <div class="contact-intro">
                    <p class="eyebrow">Start a conversation</p>
                    <h2>Let's create something unforgettable.</h2>
                    <p class="contact-copy">Have a story in mind, a date to celebrate, or simply want to know more? Reach out through any of the options here.</p>
                </div>
                <div class="contact-list">
                    @if($contactEmail)
                        <a class="contact-item" href="mailto:{{ $contactEmail }}">
                            <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M3 5h18v14H3z"/><path d="m3 6 9 7 9-7"/></svg></span>
                            <span class="contact-text"><small>Email</small><strong>{{ $contactEmail }}</strong></span>
                            <span class="contact-arrow" aria-hidden="true">↗</span>
                        </a>
                    @endif
                    @if($photographer->portfolio_contact_phone)
                        <a class="contact-item" href="tel:{{ preg_replace('/[^0-9+]/', '', $photographer->portfolio_contact_phone) }}">
                            <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M7 3H4a1 1 0 0 0-1 1c0 9.4 7.6 17 17 17a1 1 0 0 0 1-1v-3l-5-1-1.5 2a15.5 15.5 0 0 1-8.5-8.5L8 8z"/></svg></span>
                            <span class="contact-text"><small>Phone</small><strong>{{ $photographer->portfolio_contact_phone }}</strong></span>
                            <span class="contact-arrow" aria-hidden="true">↗</span>
                        </a>
                    @endif
                    @if($instagram)
                        <a class="contact-item" target="_blank" rel="noopener" href="https://instagram.com/{{ $instagram }}">
                            <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8" fill="currentColor" stroke="none"/></svg></span>
                            <span class="contact-text"><small>Instagram</small><strong>{{ '@' . $instagram }}</strong></span>
                            <span class="contact-arrow" aria-hidden="true">↗</span>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif
    <footer class="footer"><span>{{ $footerText }}</span><span>&copy; {{ date('Y') }}</span></footer>
    @if($galleryCount > 1)
        <script>
            (() => {
                const track = document.getElementById('portfolioTrack');
                const slides = [...track.querySelectorAll('.project-slide')];
                const previous = document.getElementById('portfolioPrev');
                const next = document.getElementById('portfolioNext');
                const current = document.getElementById('portfolioCurrent');
                const progress = document.getElementById('portfolioProgress');
                let activeIndex = 0;

                const update = () => {
                    const trackLeft = track.getBoundingClientRect().left;
                    activeIndex = slides.reduce((best, slide, index) =>
                        Math.abs(slide.getBoundingClientRect().left - trackLeft) <
                        Math.abs(slides[best].getBoundingClientRect().left - trackLeft) ? index : best, 0);
                    current.textContent = String(activeIndex + 1).padStart(2, '0');
                    progress.style.width = ((activeIndex + 1) / slides.length * 100) + '%';
                    previous.disabled = activeIndex === 0;
                    next.disabled = activeIndex === slides.length - 1;
                };

                const goTo = index => {
                    const slide = slides[Math.max(0, Math.min(index, slides.length - 1))];
                    const left = slide.getBoundingClientRect().left - track.getBoundingClientRect().left + track.scrollLeft;
                    track.scrollTo({ left, behavior: 'smooth' });
                };

                previous.addEventListener('click', () => goTo(activeIndex - 1));
                next.addEventListener('click', () => goTo(activeIndex + 1));
                track.addEventListener('scroll', () => requestAnimationFrame(update), { passive: true });
                window.addEventListener('resize', update);
                update();
            })();
        </script>
    @endif
</body></html>
