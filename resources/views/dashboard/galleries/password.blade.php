@php
    $studioName = $photographer->portfolio_title ?: optional($photographer->user)->name ?: 'VUE Studio';
    $galleryImage = $gallery->background_url ?: $gallery->thumbnail_url ?: $photographer->portfolio_cover_url ?: asset('images/password_screen7.jpg');
    $primaryColor = preg_match('/^#[0-9a-f]{6}$/i', (string) $photographer->portfolio_primary_color)
        ? $photographer->portfolio_primary_color
        : '#8b5cf6';
    $accentColor = preg_match('/^#[0-9a-f]{6}$/i', (string) $photographer->portfolio_accent_color)
        ? $photographer->portfolio_accent_color
        : '#d946ef';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $gallery->name }} | {{ $studioName }}</title>
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --accent: {{ $accentColor }};
            --ink: #18151d;
            --muted: #716b78;
            --line: #e4e1e7;
            --surface: #ffffff;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            background: #0a090c;
            color: var(--ink);
            font-family: Inter, "Segoe UI", system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button, input { font: inherit; }

        .access-page {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(420px, .85fr);
            min-height: 100vh;
        }

        .gallery-preview {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            isolation: isolate;
        }
        .gallery-preview img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-preview::after {
            position: absolute;
            inset: 0;
            z-index: 1;
            content: "";
            background:
                linear-gradient(180deg, rgba(6, 5, 8, .36), transparent 36%),
                linear-gradient(0deg, rgba(6, 5, 8, .82), transparent 58%);
        }

        .preview-top,
        .preview-copy {
            position: absolute;
            z-index: 2;
            left: clamp(28px, 5vw, 72px);
            right: clamp(28px, 5vw, 72px);
            color: #fff;
        }
        .preview-top {
            top: clamp(28px, 5vw, 56px);
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 14px;
            font-weight: 750;
            letter-spacing: .04em;
        }
        .brand-mark {
            display: inline-flex;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,.28);
            border-radius: 11px;
            background: rgba(255,255,255,.13);
            box-shadow: 0 10px 30px rgba(0,0,0,.16);
            font-style: italic;
            font-weight: 900;
            backdrop-filter: blur(14px);
        }
        .preview-copy { bottom: clamp(38px, 7vw, 88px); max-width: 650px; }
        .preview-kicker {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 15px;
            color: rgba(255,255,255,.72);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .17em;
            text-transform: uppercase;
        }
        .preview-kicker::before { width: 28px; height: 1px; content: ""; background: currentColor; }
        .preview-copy h1 {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(2.8rem, 6vw, 5.8rem);
            font-weight: 400;
            letter-spacing: -.055em;
            line-height: .98;
            text-wrap: balance;
        }

        .access-panel {
            position: relative;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 56px clamp(34px, 6vw, 88px);
            overflow: hidden;
            background: #f8f7fa;
        }
        .access-panel::before {
            position: absolute;
            top: -180px;
            right: -180px;
            width: 430px;
            height: 430px;
            border-radius: 50%;
            content: "";
            background: radial-gradient(circle, color-mix(in srgb, var(--primary) 16%, transparent), transparent 68%);
            pointer-events: none;
        }
        .access-card { position: relative; width: 100%; max-width: 430px; }
        .privacy-icon {
            display: inline-flex;
            width: 50px;
            height: 50px;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            border: 1px solid color-mix(in srgb, var(--primary) 22%, white);
            border-radius: 15px;
            background: color-mix(in srgb, var(--primary) 9%, white);
            color: var(--primary);
        }
        .privacy-icon svg { width: 23px; height: 23px; }
        .access-eyebrow {
            display: block;
            margin-bottom: 10px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .16em;
            text-transform: uppercase;
        }
        .access-card h2 {
            margin: 0 0 12px;
            color: var(--ink);
            font-size: clamp(1.85rem, 4vw, 2.45rem);
            font-weight: 760;
            letter-spacing: -.045em;
            line-height: 1.08;
            text-wrap: balance;
        }
        .access-intro {
            max-width: 38ch;
            margin: 0 0 34px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65;
        }

        .error-message {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 18px;
            padding: 12px 14px;
            border: 1px solid #f2c8ce;
            border-radius: 11px;
            background: #fff2f3;
            color: #a31f32;
            font-size: 13px;
            font-weight: 650;
            line-height: 1.45;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #39333f;
            font-size: 13px;
            font-weight: 750;
        }
        .password-wrap { position: relative; }
        .password-input {
            width: 100%;
            height: 54px;
            padding: 0 70px 0 16px;
            border: 1px solid var(--line);
            border-radius: 13px;
            outline: none;
            background: var(--surface);
            color: var(--ink);
            font-size: 15px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }
        .password-input::placeholder { color: #aaa5ae; }
        .password-input:hover { border-color: #c8c3cd; }
        .password-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px color-mix(in srgb, var(--primary) 13%, transparent);
        }
        .password-input.is-invalid { border-color: #c9364c; }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 11px;
            padding: 7px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: var(--primary);
            cursor: pointer;
            font-size: 11px;
            font-weight: 850;
            transform: translateY(-50%);
        }
        .password-toggle:focus-visible { outline: 2px solid var(--primary); outline-offset: 1px; }
        .submit-button {
            display: inline-flex;
            width: 100%;
            min-height: 54px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
            padding: 14px 22px;
            border: 0;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            box-shadow: 0 14px 28px color-mix(in srgb, var(--primary) 25%, transparent);
            color: #fff;
            cursor: pointer;
            font-size: 14px;
            font-weight: 800;
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .submit-button:hover { transform: translateY(-1px); box-shadow: 0 17px 32px color-mix(in srgb, var(--primary) 30%, transparent); }
        .submit-button:active { transform: translateY(0); }
        .submit-button:focus-visible { outline: 3px solid color-mix(in srgb, var(--primary) 28%, transparent); outline-offset: 3px; }
        .submit-button svg { width: 17px; height: 17px; }
        .privacy-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin: 22px 0 0;
            color: #8b858f;
            font-size: 11px;
            line-height: 1.5;
            text-align: center;
        }
        .privacy-note svg { width: 13px; height: 13px; flex: 0 0 auto; }

        @media (max-width: 900px) {
            .access-page { display: block; position: relative; min-height: 100vh; }
            .gallery-preview { position: fixed; inset: 0; min-height: 100vh; }
            .gallery-preview::after { background: rgba(5, 4, 7, .68); backdrop-filter: blur(5px); }
            .preview-copy { display: none; }
            .preview-top { top: 24px; left: 24px; right: 24px; }
            .access-panel { z-index: 3; min-height: 100vh; padding: 92px 20px 30px; background: transparent; }
            .access-panel::before { display: none; }
            .access-card {
                max-width: 460px;
                padding: 30px;
                border: 1px solid rgba(255,255,255,.28);
                border-radius: 22px;
                background: rgba(255,255,255,.96);
                box-shadow: 0 24px 70px rgba(0,0,0,.42);
                backdrop-filter: blur(20px);
            }
            .privacy-icon { margin-bottom: 22px; }
        }

        @media (max-width: 480px) {
            .access-panel { align-items: flex-end; padding: 82px 12px 12px; }
            .access-card { padding: 26px 22px; border-radius: 20px; }
            .access-intro { margin-bottom: 27px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <main class="access-page">
        <section class="gallery-preview" aria-label="Gallery preview">
            <img src="{{ $galleryImage }}" alt="" aria-hidden="true">
            <div class="preview-top">
                <span class="brand-mark">V</span>
                <span>{{ $studioName }}</span>
            </div>
            <div class="preview-copy">
                <span class="preview-kicker">Private client gallery</span>
                <h1>{{ $gallery->name }}</h1>
            </div>
        </section>

        <section class="access-panel">
            <div class="access-card">
                <div class="privacy-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="5" y="10" width="14" height="11" rx="2" stroke-width="1.8"/>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="access-eyebrow">Invitation only</span>
                <h2>Welcome to {{ $gallery->name }}</h2>
                <p class="access-intro">This gallery is private. Enter the password provided by {{ $studioName }} to continue.</p>

                @error('password')
                    <div id="password-error" class="error-message" role="alert">
                        <span aria-hidden="true">!</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <form method="POST" action="{{ route('gallery.show', [$photographer->subdomain, $gallery->slug]) }}" autocomplete="off">
                    @csrf
                    <label class="field-label" for="gallery-password">Gallery password</label>
                    <div class="password-wrap">
                        <input id="gallery-password"
                               class="password-input @error('password') is-invalid @enderror"
                               type="password"
                               name="password"
                               placeholder="Enter your gallery password"
                               autocomplete="off"
                               autocapitalize="none"
                               spellcheck="false"
                               @error('password') aria-describedby="password-error" aria-invalid="true" @enderror
                               required
                               autofocus>
                        <button id="password-toggle" class="password-toggle" type="button" aria-label="Show password">Show</button>
                    </div>
                    <button class="submit-button" type="submit">
                        <span>Open gallery</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path d="M5 12h14m-5-5 5 5-5 5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>

                <p class="privacy-note">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M12 3 5 6v5c0 4.6 2.9 8.8 7 10 4.1-1.2 7-5.4 7-10V6l-7-3Z" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    Access is limited to invited clients and guests.
                </p>
            </div>
        </section>
    </main>

    <script>
        const passwordInput = document.getElementById('gallery-password');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', () => {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            passwordToggle.textContent = isVisible ? 'Show' : 'Hide';
            passwordToggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            passwordInput.focus();
        });
    </script>
</body>
</html>
