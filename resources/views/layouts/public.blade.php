<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#080a09">
    <title>@yield('title') | {{ config('business.name') }}</title>
    <meta name="description" content="@yield('description')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root{--ink:#080a09;--paper:#f2efe7;--muted:#a3a79f;--line:rgba(255,255,255,.12);--acid:#c9ff45;--aqua:#66e3d1}*{box-sizing:border-box}html{background:var(--ink);scroll-behavior:smooth}body{margin:0;color:var(--paper);background:radial-gradient(circle at 80% 5%,rgba(102,227,209,.1),transparent 30%),var(--ink);font-family:Manrope,sans-serif;-webkit-font-smoothing:antialiased}a{color:inherit}.shell{width:min(1120px,calc(100% - 40px));margin:auto}.site-header{border-bottom:1px solid var(--line);background:rgba(8,10,9,.88)}.nav{min-height:78px;display:flex;align-items:center;justify-content:space-between;gap:24px}.brand{display:flex;align-items:center;gap:11px;text-decoration:none;font:800 21px/1 Syne,sans-serif}.brand-mark{width:32px;height:32px;position:relative;border-radius:9px;background:var(--acid);transform:rotate(-7deg)}.brand-mark:after{content:'';position:absolute;width:10px;height:10px;inset:11px;border-radius:50%;background:var(--ink)}.nav-links{display:flex;align-items:center;gap:22px;font-size:13px;font-weight:700}.nav-links a{text-decoration:none;color:var(--muted)}.nav-links a:hover{color:var(--acid)}main{padding:90px 0 110px}.eyebrow{color:var(--acid);font:500 11px/1 DM Mono,monospace;letter-spacing:.14em;text-transform:uppercase}.page-title{max-width:850px;margin:18px 0 22px;font:700 clamp(44px,7vw,82px)/.95 Syne,sans-serif;letter-spacing:-.05em}.lead{max-width:760px;margin:0;color:var(--muted);font-size:18px;line-height:1.8}.content{max-width:820px;margin-top:70px}.content section{padding:32px 0;border-top:1px solid var(--line)}.content h2{margin:0 0 15px;font:600 25px/1.2 Syne,sans-serif;letter-spacing:-.025em}.content h3{margin:22px 0 8px;font-size:16px}.content p,.content li{color:#b4b8b0;font-size:15px;line-height:1.8}.content p{margin:0 0 14px}.content ul{margin:0;padding-left:20px}.content a{color:var(--acid)}.contact-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:55px}.contact-card{min-height:165px;padding:24px;border:1px solid var(--line);border-radius:18px;background:rgba(255,255,255,.035)}.contact-card small{display:block;margin-bottom:18px;color:var(--aqua);font:500 10px/1 DM Mono,monospace;letter-spacing:.13em;text-transform:uppercase}.contact-card a,.contact-card address{font-style:normal;font-size:16px;line-height:1.6;overflow-wrap:anywhere}.updated{margin-top:26px;color:#737970;font:500 10px/1.5 DM Mono,monospace;letter-spacing:.1em;text-transform:uppercase}footer{padding:36px 0;border-top:1px solid var(--line)}.footer-row{display:flex;justify-content:space-between;gap:28px}.footer-links{display:flex;flex-wrap:wrap;gap:18px;font-size:12px}.footer-links a{color:var(--muted);text-decoration:none}.footer-links a:hover{color:var(--acid)}.copyright{color:#747970;font-size:11px}@media(max-width:760px){.nav{align-items:flex-start;flex-direction:column;padding:20px 0}.nav-links{flex-wrap:wrap}.contact-grid{grid-template-columns:1fr}.footer-row{flex-direction:column}main{padding-top:65px}}
        .brand-logo{display:block;width:auto;height:48px;object-fit:contain}
    </style>
</head>
<body>
    <header class="site-header">
        <nav class="nav shell" aria-label="Primary navigation">
            <a class="brand" href="{{ route('home') }}" aria-label="Galerive home"><img class="brand-logo" src="{{ asset('images/final_logo.png') }}" alt="Galerive"></a>
            <div class="nav-links"><a href="{{ route('home') }}#pricing">Pricing</a><a href="{{ route('about') }}">About us</a><a href="{{ route('contact') }}">Contact us</a><a href="{{ route('login') }}">Log in</a></div>
        </nav>
    </header>
    <main><div class="shell">@yield('content')</div></main>
    <footer><div class="shell footer-row"><div class="footer-links"><a href="{{ route('about') }}">About</a><a href="{{ route('contact') }}">Contact</a><a href="{{ route('privacy') }}">Privacy policy</a><a href="{{ route('delivery') }}">Delivery &amp; shipping</a><a href="{{ route('refunds') }}">Refunds &amp; cancellation</a></div><div class="copyright">&copy; {{ date('Y') }} {{ config('business.name') }}</div></div></footer>
</body>
</html>
