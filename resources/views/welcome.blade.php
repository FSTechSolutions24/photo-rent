<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VUE | Cinematic Storage & Delivery for Photographers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #050505;
            --accent: #8B5CF6; /* Violet */
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
        }
        body {
            background-color: var(--bg);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
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
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #1a1a1a; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }
    </style>
    <script type="importmap">
    {
      "imports": {
        "@google/genai": "https://esm.sh/@google/genai@^1.35.0"
      }
    }
    </script>
</head>
<body class="antialiased selection:bg-violet-500 selection:text-white">
    <div class="glow top-[-200px] left-[-200px]"></div>
    <div class="glow bottom-[-200px] right-[-200px]"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 w-full z-50 p-6 md:px-12">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass py-4 px-8 rounded-full border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-fuchsia-500 rotate-12 flex items-center justify-center">
                    <span class="font-display font-black text-white italic">V</span>
                </div>
                <span class="text-xl font-display font-bold tracking-tighter uppercase">Vue</span>
            </div>
            
            <div class="hidden lg:flex items-center gap-10 text-[10px] font-bold uppercase tracking-[0.2em] opacity-60">
                <a href="#vision" class="hover:opacity-100 transition-opacity">The Vision</a>
                <a href="#process" class="hover:opacity-100 transition-opacity">Process</a>
                <a href="#plans" class="hover:opacity-100 transition-opacity">Plans</a>
                <a href="#lia" class="hover:opacity-100 transition-opacity">Lia AI</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="#" class="text-[10px] font-bold uppercase tracking-widest hidden sm:block">Login</a>
                <button class="bg-white text-black px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-transform active:scale-95">
                    Start Archiving
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-40 pb-20 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 bg-white/5 border border-white/10 px-4 py-1.5 rounded-full mb-8">
                    <span class="w-2 h-2 rounded-full bg-violet-400 animate-ping"></span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-violet-300">V3.0 Now Live: 10-Bit Delivery</span>
                </div>
                <h1 class="font-display text-6xl md:text-[9.5rem] leading-[0.85] tracking-tighter mb-10">
                    STORAGE <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-400 italic font-medium">REIMAGINED.</span>
                </h1>
                <p class="text-lg md:text-xl text-white/50 max-w-xl leading-relaxed mb-12 font-light">
                    Your lens captures the light. We preserve the data. Vue is the world's most beautiful cloud for professional photographers to host, curate, and share high-bitrate visual stories.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="btn-vue bg-violet-600 text-white px-10 py-5 rounded-2xl font-bold uppercase tracking-widest text-xs shadow-2xl shadow-violet-500/20">
                        Reserve 5TB Storage
                    </button>
                    <button class="px-10 py-5 rounded-2xl font-bold uppercase tracking-widest text-xs border border-white/10 hover:bg-white/5 transition-colors">
                        Explore Demo Gallery
                    </button>
                </div>
            </div>
            <div class="lg:col-span-5 relative">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4 pt-12">
                        <div class="img-card h-64"><img src="https://images.unsplash.com/photo-1554048612-b6a482bc67e5?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover"></div>
                        <div class="img-card h-40"><img src="https://images.unsplash.com/photo-1493863641943-9b68992a8d07?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="img-card h-40"><img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover"></div>
                        <div class="img-card h-64"><img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- The Process Section -->
    <section id="process" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-24">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-violet-500 mb-4 block">The Pipeline</span>
                <h2 class="font-display text-4xl md:text-6xl tracking-tighter">Capture to Delivery in <span class="italic text-white/40">Real-Time.</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-white/5 rounded-[3rem] overflow-hidden bg-white/[0.02]">
                <div class="p-12 md:p-16 border-b md:border-b-0 md:border-r border-white/5 hover:bg-white/[0.04] transition-colors group">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center mb-10 font-display text-xl group-hover:border-violet-500 transition-colors">01</div>
                    <h3 class="text-2xl font-display mb-6 uppercase">Raw Archival</h3>
                    <p class="text-white/40 text-sm leading-relaxed mb-10">Upload your massive RAW libraries with zero compression. Our parallel processing creates instant 8K previews as you go.</p>
                    <div class="h-40 bg-black rounded-2xl border border-white/5 overflow-hidden flex items-center justify-center italic text-[10px] opacity-20 uppercase tracking-widest">
                        Uploading... 89%
                    </div>
                </div>
                <div class="p-12 md:p-16 border-b md:border-b-0 md:border-r border-white/5 hover:bg-white/[0.04] transition-colors group">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center mb-10 font-display text-xl group-hover:border-violet-500 transition-colors">02</div>
                    <h3 class="text-2xl font-display mb-6 uppercase">Curate with Lia</h3>
                    <p class="text-white/40 text-sm leading-relaxed mb-10">Our Studio AI analyzes your focus, exposure, and composition to help you cull a wedding gallery in minutes, not hours.</p>
                    <div class="h-40 bg-black rounded-2xl border border-white/5 overflow-hidden p-4 space-y-3">
                        <div class="w-full h-1.5 bg-violet-900/40 rounded-full overflow-hidden"><div class="w-[70%] h-full bg-violet-500"></div></div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden"><div class="w-[40%] h-full bg-violet-500"></div></div>
                        <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden"><div class="w-[90%] h-full bg-violet-500"></div></div>
                    </div>
                </div>
                <div class="p-12 md:p-16 hover:bg-white/[0.04] transition-colors group">
                    <div class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center mb-10 font-display text-xl group-hover:border-violet-500 transition-colors">03</div>
                    <h3 class="text-2xl font-display mb-6 uppercase">Immersive Links</h3>
                    <p class="text-white/40 text-sm leading-relaxed mb-10">Share high-fidelity links that feel like a premium app. Your clients view photos in a liquid gallery tailored to their screen.</p>
                    <div class="h-40 bg-violet-500/10 rounded-2xl border border-violet-500/30 flex items-center justify-center">
                        <div class="bg-white/10 px-4 py-2 rounded-lg text-[10px] font-bold tracking-tighter">vue.ai/x/client-01</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="plans" class="py-32 px-6 bg-white/[0.01]">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row justify-between items-end gap-12 mb-20">
                <div class="max-w-2xl">
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-violet-500 mb-4 block">Subscriptions</span>
                    <h2 class="font-display text-5xl md:text-7xl tracking-tighter uppercase leading-[0.9]">Scaling with <br> <span class="italic text-white/30">your shutter count.</span></h2>
                </div>
                <div class="hidden lg:block text-right">
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.5em] mb-4">Pricing Models</p>
                    <div class="flex gap-2">
                        <div class="w-1 h-1 bg-white"></div>
                        <div class="w-1 h-1 bg-white"></div>
                        <div class="w-1 h-1 bg-white"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Hobbyist -->
                <div class="glass p-12 rounded-[3rem] hover:border-white/20 transition-all group">
                    <h3 class="text-xl font-display mb-2 uppercase">The Shutter</h3>
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-12">Essential Storage</p>
                    <div class="text-5xl font-display tracking-tighter mb-12">$15<span class="text-xs font-light opacity-30">/mo</span></div>
                    <ul class="space-y-6 mb-12 text-xs font-bold uppercase tracking-widest text-white/60">
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> 1TB Archival</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Standard Links</li>
                        <li class="flex items-center gap-3 opacity-20"><div class="w-1.5 h-1.5 rounded-full bg-white"></div> Smart Culling</li>
                    </ul>
                    <button class="w-full py-5 rounded-2xl border border-white/10 hover:bg-white text-black bg-white/5 font-bold uppercase tracking-widest text-[10px] transition-all group-hover:bg-white group-hover:text-black">Select Tier</button>
                </div>

                <!-- Pro -->
                <div class="bg-gradient-to-b from-violet-600/20 to-transparent p-[1px] rounded-[3rem]">
                    <div class="glass p-12 rounded-[3rem] bg-[#0A0A0A]/90 h-full relative overflow-hidden">
                        <div class="absolute top-8 right-8 bg-violet-500 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase">Popular</div>
                        <h3 class="text-xl font-display mb-2 uppercase">The Exposure</h3>
                        <p class="text-[10px] font-bold text-violet-400 uppercase tracking-[0.2em] mb-12">Studio Engine</p>
                        <div class="text-5xl font-display tracking-tighter mb-12">$45<span class="text-xs font-light opacity-30">/mo</span></div>
                        <ul class="space-y-6 mb-12 text-xs font-bold uppercase tracking-widest text-white">
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> 10TB SSD Archival</li>
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Unlimited Galleries</li>
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Lia AI Smart Culling</li>
                            <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> White-Label Portals</li>
                        </ul>
                        <button class="w-full py-5 rounded-2xl bg-violet-500 text-white font-bold uppercase tracking-widest text-[10px] shadow-2xl shadow-violet-500/40 hover:scale-105 transition-transform">Select Pro Tier</button>
                    </div>
                </div>

                <!-- Agency -->
                <div class="glass p-12 rounded-[3rem] hover:border-white/20 transition-all group">
                    <h3 class="text-xl font-display mb-2 uppercase">The Aperture</h3>
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mb-12">Agency Power</p>
                    <div class="text-5xl font-display tracking-tighter mb-12">$120<span class="text-xs font-light opacity-30">/mo</span></div>
                    <ul class="space-y-6 mb-12 text-xs font-bold uppercase tracking-widest text-white/60">
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Unlimited SSD Space</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> Multiple User Seats</li>
                        <li class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-violet-500"></div> API Infrastructure</li>
                    </ul>
                    <button class="w-full py-5 rounded-2xl border border-white/10 bg-white/5 font-bold uppercase tracking-widest text-[10px] transition-all group-hover:bg-white group-hover:text-black">Contact Studio</button>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Lia Interface -->
    <section id="lia" class="py-32 px-6 relative">
        <div class="max-w-4xl mx-auto glass p-12 md:p-20 rounded-[4rem] relative z-10">
            <div class="flex flex-col items-center text-center mb-16">
                <div class="w-20 h-20 rounded-full ai-orb mb-8 flex items-center justify-center shadow-2xl shadow-violet-500/50">
                    <div class="w-4 h-4 bg-white rounded-full animate-pulse"></div>
                </div>
                <h2 class="font-display text-4xl md:text-5xl mb-6 tracking-tighter uppercase">MEET LIA.</h2>
                <p class="text-white/40 max-w-lg leading-relaxed text-sm font-medium">Your studio manager, librarian, and curator. Lia learns your style and handles the technical debt of your workflow.</p>
            </div>

            <div class="bg-black/50 border border-white/5 rounded-3xl p-8 min-h-[300px] mb-8 flex flex-col justify-end">
                <div id="chat-history" class="space-y-6 overflow-y-auto max-h-[400px] mb-4 pr-4">
                    <div class="flex items-start gap-4">
                        <div class="w-6 h-6 rounded-full bg-violet-500 shrink-0 mt-1"></div>
                        <div class="bg-white/5 p-4 rounded-2xl rounded-tl-none text-xs leading-loose text-white/70 max-w-[80%]">
                            "Hello. I can help you project your storage growth for 2024. How many sessions do you typically shoot per month, and what's your average file size?"
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <input id="chat-input" type="text" placeholder="I shoot about 4 weddings a month at 60MB per RAW..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-5 px-6 outline-none focus:border-violet-500 transition-colors text-xs font-medium placeholder:opacity-20">
                    <button id="send-btn" class="absolute right-3 top-3 bg-violet-500 text-white p-3 rounded-xl hover:scale-105 transition-transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </div>
            <div class="flex gap-4 justify-center flex-wrap">
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/20">Neural Engine: Active</span>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/20">Model: VUE-PRO-1.2</span>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/20">Latency: 12ms</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-16">
            <div class="max-w-xs">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-fuchsia-500 flex items-center justify-center">
                        <span class="font-display font-black text-white italic">V</span>
                    </div>
                    <span class="text-xl font-display font-bold tracking-tighter uppercase">Vue</span>
                </div>
                <p class="text-xs text-white/30 leading-relaxed font-medium uppercase tracking-widest">A standard of excellence in digital archival. For those who see what others don't.</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-16">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Protocol</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-widest text-white/30 space-y-4">
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Archival Specs</a></li>
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Edge Network</a></li>
                        <li><a href="#" class="hover:text-violet-400 transition-colors">API Keys</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Studio</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-widest text-white/30 space-y-4">
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Dashboard</a></li>
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Galleries</a></li>
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Lia Training</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Connect</h4>
                    <ul class="text-[10px] font-bold uppercase tracking-widest text-white/30 space-y-4">
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Instagram</a></li>
                        <li><a href="#" class="hover:text-violet-400 transition-colors">Journal</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-24 flex justify-between items-center text-[9px] font-black uppercase tracking-[0.5em] text-white/10">
            <span>&copy; 2024 VUE SYSTEMS CO.</span>
            <span>DATA INTEGRITY: VERIFIED</span>
        </div>
    </footer>

    <script type="module">
        import { GoogleGenAI } from "@google/genai";

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

        // Lia AI logic
        const chatInput = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');
        const chatHistory = document.getElementById('chat-history');

        async function addMessage(role, text) {
            const div = document.createElement('div');
            div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} items-start gap-4 animate-in fade-in slide-in-from-bottom-2 duration-500`;
            div.innerHTML = `
                ${role === 'ai' ? '<div class="w-6 h-6 rounded-full bg-violet-500 shrink-0 mt-1"></div>' : ''}
                <div class="${role === 'user' ? 'bg-violet-600/20 border border-violet-500/30' : 'bg-white/5'} p-4 rounded-2xl text-xs leading-loose text-white/70 max-w-[80%]">
                    ${text}
                </div>
                ${role === 'user' ? '<div class="w-6 h-6 rounded-full bg-white/10 shrink-0 mt-1"></div>' : ''}
            `;
            chatHistory.appendChild(div);
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        sendBtn.addEventListener('click', async () => {
            const msg = chatInput.value.trim();
            if (!msg) return;

            chatInput.value = '';
            addMessage('user', msg);

            const loading = document.createElement('div');
            loading.className = 'text-[10px] font-black uppercase tracking-[0.3em] text-violet-500/50 animate-pulse';
            loading.innerText = 'Lia is thinking...';
            chatHistory.appendChild(loading);

            try {
                const ai = new GoogleGenAI({ apiKey: "ENV_KEY" }); // handled
                const response = await ai.models.generateContent({
                    model: 'gemini-3-flash-preview',
                    contents: `Context: You are Lia, an AI studio manager for VUE (photography cloud storage). User query: "${msg}". Goal: Recommend a plan (Shutter $15, Exposure $45, Aperture $120) or help with storage projections. Be sophisticated, professional, and slightly futuristic. Use brief, punchy sentences.`,
                });
                loading.remove();
                addMessage('ai', response.text);
            } catch (err) {
                loading.remove();
                addMessage('ai', "My neural uplink is momentarily saturated. Based on your input, I recommend our Exposure Tier for seamless studio management.");
            }
        });

        chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendBtn.click(); });
    </script>
</body>
</html>