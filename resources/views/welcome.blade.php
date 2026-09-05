<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VUE | Galleries, Client Management & Delivery for Photographers</title>
    <meta name="description" content="VUE gives photographers one workspace to manage clients and sessions, publish protected galleries, share work, and deliver downloads.">
    <script>
        (() => {
            const savedTheme = localStorage.getItem('vue-theme');
            document.documentElement.dataset.theme = savedTheme || 'dark';
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #050505;
            --text: #ffffff;
            --muted: rgba(255, 255, 255, 0.48);
            --accent: #8B5CF6; /* Violet */
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
            color-scheme: dark;
        }
        html[data-theme="light"] {
            --bg: #f6f5fb;
            --text: #17131f;
            --muted: rgba(23, 19, 31, 0.6);
            --glass: rgba(255, 255, 255, 0.7);
            --border: rgba(23, 19, 31, 0.12);
            color-scheme: light;
        }
        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .font-display { font-family: 'Bricolage Grotesque', sans-serif; }
        
        /* Glass Effect */
        .glass {
            background: var(--glass);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border);
        }
        
        /* Animated Background Glows */
        .glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
        }
        html[data-theme="light"] .glow {
            background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, rgba(246, 245, 251, 0) 70%);
        }

        .theme-toggle {
            width: 2.75rem;
            height: 2.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 9999px;
            background: var(--glass);
            color: var(--text);
            transition: transform 0.2s ease, background-color 0.2s ease;
        }
        .theme-toggle:hover { transform: rotate(8deg) scale(1.05); }
        .theme-toggle .sun-icon { display: none; }
        html[data-theme="light"] .theme-toggle .sun-icon { display: block; }
        html[data-theme="light"] .theme-toggle .moon-icon { display: none; }

        html[data-theme="light"] .text-white { color: #17131f !important; }
        html[data-theme="light"] .text-white\/70 { color: rgba(23, 19, 31, 0.72) !important; }
        html[data-theme="light"] .text-white\/60 { color: rgba(23, 19, 31, 0.65) !important; }
        html[data-theme="light"] .text-white\/50,
        html[data-theme="light"] .text-white\/40,
        html[data-theme="light"] .text-white\/30,
        html[data-theme="light"] .text-white\/20,
        html[data-theme="light"] .text-white\/10 { color: var(--muted) !important; }
        html[data-theme="light"] .bg-white\/5,
        html[data-theme="light"] .bg-white\/\[0\.02\],
        html[data-theme="light"] .bg-white\/\[0\.04\] { background-color: rgba(23, 19, 31, 0.045) !important; }
        html[data-theme="light"] .bg-white\/\[0\.01\] { background-color: rgba(23, 19, 31, 0.025) !important; }
        html[data-theme="light"] .bg-black,
        html[data-theme="light"] .bg-black\/50,
        html[data-theme="light"] .bg-\[\#0A0A0A\]\/90 { background-color: rgba(255, 255, 255, 0.78) !important; }
        html[data-theme="light"] .border-white\/5,
        html[data-theme="light"] .border-white\/10,
        html[data-theme="light"] .border-white\/20 { border-color: var(--border) !important; }
        html[data-theme="light"] .on-accent,
        html[data-theme="light"] .on-accent.text-white { color: #ffffff !important; }
        html[data-theme="light"] .primary-cta { background: #17131f !important; color: #ffffff !important; }

        /* Custom Hover for Images */
        .img-card {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
        }
        .img-card::after {
            content: 'VIEW EXIF';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
            letter-spacing: 0.2em;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .img-card:hover::after { opacity: 1; }

        /* Step Line */
        .step-line {
            background: linear-gradient(to bottom, var(--accent), transparent);
            width: 1px;
            height: 100%;
        }

        /* Button Hover */
        .btn-vue {
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-vue::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-vue:hover::before { left: 100%; }

        /* AI Orb Animation */
        @keyframes pulse-orb {
            0%, 100% { transform: scale(1); filter: brightness(1); }
            50% { transform: scale(1.1); filter: brightness(1.3); }
        }
        .ai-orb {
            animation: pulse-orb 4s infinite ease-in-out;
            background: radial-gradient(circle at 30% 30%, var(--accent), #4c1d95);
        }

        /* Hide Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    </style>
</head>
<body class="antialiased selection:bg-violet-500 selection:text-white">
    <div class="glow top-[-200px] left-[-200px]"></div>
    <div class="glow bottom-[-200px] right-[-200px]"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 w-full z-50 p-6 md:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass py-4 px-8 rounded-full border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-fuchsia-500 rotate-12 flex items-center justify-center">
                    <span class="on-accent font-display font-black text-white italic">V</span>
                </div>
                <span class="text-xl font-display font-bold tracking-tighter uppercase">Vue</span>
            </div>
            
            <div class="hidden lg:flex items-center gap-10 text-[10px] font-bold uppercase tracking-[0.2em] opacity-60">
                <a href="#process" class="hover:opacity-100 transition-opacity">Workflow</a>
                <a href="#features" class="hover:opacity-100 transition-opacity">Features</a>
                <a href="#studio" class="hover:opacity-100 transition-opacity">Studio tools</a>
            </div>

            <div class="flex items-center gap-4">
                <button id="theme-toggle" class="theme-toggle" type="button" aria-label="Switch to light mode" title="Switch theme">
                    <svg class="moon-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
                    <svg class="sun-icon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg>
                </button>
                <a href="/login" class="text-[10px] font-bold uppercase tracking-widest hidden sm:block">Login</a>
                <a href="/register" class="primary-cta bg-white text-black px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-transform active:scale-95">
                    Start Free
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-40 pb-20 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-1.5 rounded-full mb-8">
                    <span class="w-2 h-2 rounded-full bg-violet-400 animate-ping"></span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-violet-300">One workspace for your photography business</span>
                </div>
                <h1 class="font-display text-6xl md:text-[9.5rem] leading-[0.85] tracking-tighter mb-10">
                    STORAGE <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-400 italic font-medium">REIMAGINED.</span>
                </h1>
                <p class="text-lg md:text-xl text-white/50 max-w-xl leading-relaxed mb-12 font-light">
                    VUE helps photographers organize clients and sessions, publish polished galleries, protect private work, and deliver photos without piecing together multiple tools.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/register" class="btn-vue on-accent bg-violet-600 text-white px-10 py-5 rounded-2xl font-bold uppercase tracking-widest text-xs text-center shadow-2xl shadow-violet-500/20">Create your account</a>
                    <a href="#features" class="px-10 py-5 rounded-2xl font-bold uppercase tracking-widest text-xs text-center border border-white/10 hover:bg-white/5 transition-colors">See what VUE does</a>
                </div>
            </div>
            <div class="lg:col-span-5 relative">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4 pt-12">
                        <div class="img-card h-64"><img src="https://images.unsplash.com/photo-1554048612-b6a482bc67e5?auto=format&fit=crop&q=80&w=600" alt="Photographer working in a studio" class="w-full h-full object-cover" loading="eager"></div>
                        <div class="img-card h-40"><img src="https://images.unsplash.com/photo-1493863641943-9b68992a8d07?auto=format&fit=crop&q=80&w=600" alt="Professional camera equipment" class="w-full h-full object-cover" loading="lazy"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="img-card h-40"><img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=600" alt="Photographer holding a camera" class="w-full h-full object-cover" loading="lazy"></div>
                        <div class="img-card h-64"><img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&q=80&w=600" alt="Camera ready for a photo session" class="w-full h-full object-cover" loading="lazy"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- The Process Section -->
    <section id="process" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-24">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-violet-500 mb-4 block">A simpler workflow</span>
                <h2 class="font-display text-4xl md:text-6xl tracking-tighter">From booking to <span class="italic text-white/40">final delivery.</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-white/5 rounded-[3rem] overflow-hidden bg-white/[0.02]">
                <div class="p-12 md:p-16 border-b md:border-b-0 md:border-r border-white/5 hover:bg-white/[0.04] transition-colors group">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center mb-10 font-display text-xl group-hover:border-violet-500 transition-colors">01</div>
                    <h3 class="text-2xl font-display mb-6 uppercase">Organize</h3>
                    <p class="text-white/40 text-sm leading-relaxed mb-10">Keep client details, appointments, sessions, and galleries connected in one dashboard built around a photographer's workflow.</p>
                    <div class="h-40 bg-black rounded-2xl border border-white/5 overflow-hidden flex items-center justify-center italic text-[10px] opacity-20 uppercase tracking-widest">
                        Client • Session • Gallery
                    </div>
                </div>
                <div class="p-12 md:p-16 border-b md:border-b-0 md:border-r border-white/5 hover:bg-white/[0.04] transition-colors group">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center mb-10 font-display text-xl group-hover:border-violet-500 transition-colors">02</div>
                    <h3 class="text-2xl font-display mb-6 uppercase">Present</h3>
                    <p class="text-white/40 text-sm leading-relaxed mb-10">Upload photos into folders, choose a gallery layout, add a cover, and publish a client-ready experience on your own subdomain.</p>
                    <div class="h-40 bg-black rounded-2xl border border-white/5 overflow-hidden p-4 space-y-3">
                        <div class="w-full h-1.5 bg-violet-900/40 rounded-full overflow-hidden"><div class="w-[70%] h-full bg-violet-500"></div></div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden"><div class="w-[40%] h-full bg-violet-500"></div></div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden"><div class="w-[90%] h-full bg-violet-500"></div></div>
                    </div>
                </div>
                <div class="p-12 md:p-16 hover:bg-white/[0.04] transition-colors group">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center mb-10 font-display text-xl group-hover:border-violet-500 transition-colors">03</div>
                    <h3 class="text-2xl font-display mb-6 uppercase">Deliver</h3>
                    <p class="text-white/40 text-sm leading-relaxed mb-10">Share a public or password-protected gallery through WhatsApp and let clients request complete or selected-folder downloads.</p>
                    <div class="h-40 bg-violet-500/10 rounded-2xl border border-violet-500/30 flex items-center justify-center">
                        <div class="bg-white/10 px-4 py-2 rounded-lg text-[10px] font-bold tracking-tighter">yourstudio.vue/gallery-name</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-32 px-6 bg-white/[0.01]">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-12 mb-20">
                <div class="max-w-2xl">
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-violet-500 mb-4 block">Built for real client work</span>
                    <h2 class="font-display text-5xl md:text-7xl tracking-tighter uppercase leading-[0.9]">Your studio, <br> <span class="italic text-white/30">under control.</span></h2>
                </div>
                <div class="hidden lg:block text-right">
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.5em] mb-4">Core features</p>
                    <div class="flex gap-2">
                        <div class="w-1 h-1 bg-white"></div>
                        <div class="w-1 h-1 bg-white"></div>
                        <div class="w-1 h-1 bg-white"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="glass p-12 rounded-[3rem] hover:border-white/20 transition-all group">
                    <h3 class="text-xl font-display mb-2 uppercase">Client Galleries</h3>
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-12">Presentation & delivery</p>
                    <ul class="space-y-6 mb-12 text-xs font-bold uppercase tracking-widest text-white/60">
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Multiple gallery layouts</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Client & guest passwords</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Folder or full downloads</li>
                    </ul>
                    <a href="/register" class="block text-center w-full py-5 rounded-2xl border border-white/10 bg-white/5 font-bold uppercase tracking-widest text-[10px] transition-all hover:border-violet-500">Build a gallery</a>
                </div>

                <div class="bg-gradient-to-b from-violet-600/20 to-transparent p-[1px] rounded-[3rem]">
                    <div class="glass p-12 rounded-[3rem] bg-[#0A0A0A]/90 h-full relative overflow-hidden">
                        <div class="absolute top-8 right-8 on-accent bg-violet-500 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">All in one</div>
                        <h3 class="text-xl font-display mb-2 uppercase">Studio Management</h3>
                        <p class="text-[10px] font-bold text-violet-400 uppercase tracking-[0.2em] mb-12">Daily operations</p>
                        <ul class="space-y-6 mb-12 text-xs font-bold uppercase tracking-widest text-white">
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Client records</li>
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Sessions & appointments</li>
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Calendar overview</li>
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Finance reporting</li>
                        </ul>
                        <a href="/register" class="block text-center w-full py-5 rounded-2xl on-accent bg-violet-500 text-white font-bold uppercase tracking-widest text-[10px] shadow-2xl shadow-violet-500/40 hover:scale-105 transition-transform">Start your workspace</a>
                    </div>
                </div>

                <div class="glass p-12 rounded-[3rem] hover:border-white/20 transition-all group">
                    <h3 class="text-xl font-display mb-2 uppercase">Your Brand</h3>
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-12">A professional presence</p>
                    <ul class="space-y-6 mb-12 text-xs font-bold uppercase tracking-widest text-white/60">
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Portfolio page</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Custom colors & cover</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Dedicated subdomain</li>
                    </ul>
                    <a href="/register" class="block text-center w-full py-5 rounded-2xl border border-white/10 bg-white/5 font-bold uppercase tracking-widest text-[10px] transition-all hover:border-violet-500">Create your portfolio</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Studio tools -->
    <section id="studio" class="py-32 px-6 relative">
        <div class="max-w-4xl mx-auto glass p-12 md:p-20 rounded-[4rem] relative z-10">
            <div class="flex flex-col items-center text-center mb-12">
                <div class="w-20 h-20 rounded-full ai-orb mb-8 flex items-center justify-center shadow-2xl shadow-violet-500/50">
                    <svg class="on-accent w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg>
                </div>
                <h2 class="font-display text-4xl md:text-5xl mb-6 tracking-tighter uppercase">RUN THE BUSINESS.</h2>
                <p class="text-white/40 max-w-xl leading-relaxed text-sm font-medium">VUE keeps the administrative side of your studio close to the work, so you can track upcoming shoots, client history, and income without losing the creative flow.</p>
            </div>
            <div class="grid sm:grid-cols-3 gap-4 mb-10">
                <div class="bg-white/5 border border-white/5 rounded-2xl p-6 text-center"><strong class="block font-display text-2xl mb-2">Calendar</strong><span class="text-xs text-white/40">See scheduled appointments at a glance.</span></div>
                <div class="bg-white/5 border border-white/5 rounded-2xl p-6 text-center"><strong class="block font-display text-2xl mb-2">Reports</strong><span class="text-xs text-white/40">Review session revenue and studio activity.</span></div>
                <div class="bg-white/5 border border-white/5 rounded-2xl p-6 text-center"><strong class="block font-display text-2xl mb-2">Sharing</strong><span class="text-xs text-white/40">Send gallery details with your saved WhatsApp message.</span></div>
            </div>
            <a href="/register" class="block mx-auto max-w-xs text-center py-5 rounded-2xl on-accent bg-violet-500 text-white font-bold uppercase tracking-widest text-[10px] hover:scale-105 transition-transform">Get started</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-16">
            <div class="max-w-xs">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-fuchsia-500 flex items-center justify-center">
                        <span class="on-accent font-display font-black text-white italic">V</span>
                    </div>
                    <span class="text-xl font-display font-bold tracking-tighter uppercase">Vue</span>
                </div>
                <p class="text-xs text-white/30 leading-relaxed font-medium uppercase tracking-widest">A practical studio workspace for photographers—from first booking to final gallery delivery.</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-16">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Product</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-widest text-white/30 space-y-4">
                        <li><a href="#process" class="hover:text-violet-400 transition-colors">Workflow</a></li>
                        <li><a href="#features" class="hover:text-violet-400 transition-colors">Features</a></li>
                        <li><a href="#studio" class="hover:text-violet-400 transition-colors">Studio tools</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Studio</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-widest text-white/30 space-y-4">
                        <li><a href="/login" class="hover:text-violet-400 transition-colors">Log in</a></li>
                        <li><a href="/register" class="hover:text-violet-400 transition-colors">Create account</a></li>
                        <li><a href="/dashboard" class="hover:text-violet-400 transition-colors">Dashboard</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Highlights</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-widest text-white/30 space-y-4">
                        <li><a href="#features" class="hover:text-violet-400 transition-colors">Protected galleries</a></li>
                        <li><a href="#features" class="hover:text-violet-400 transition-colors">Portfolio pages</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-24 flex justify-between items-center text-[9px] font-black uppercase tracking-[0.5em] text-white/10">
            <span>&copy; {{ date('Y') }} VUE.</span>
            <span>BUILT FOR PHOTOGRAPHERS</span>
        </div>
    </footer>

    <script>
        const themeToggle = document.getElementById('theme-toggle');

        function updateThemeToggle(theme) {
            const nextTheme = theme === 'dark' ? 'light' : 'dark';
            themeToggle.setAttribute('aria-label', `Switch to ${nextTheme} mode`);
        }

        updateThemeToggle(document.documentElement.dataset.theme);
        themeToggle.addEventListener('click', () => {
            const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = nextTheme;
            localStorage.setItem('vue-theme', nextTheme);
            updateThemeToggle(nextTheme);
        });

        // Scroll Observer for smooth entry
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.style.opacity = '1';
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('section').forEach(section => {
            section.style.transition = 'opacity 1s ease';
            section.style.opacity = '0';
            observer.observe(section);
        });

    </script>
</body>
</html>
