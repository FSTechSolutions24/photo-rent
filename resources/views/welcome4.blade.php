<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRISM | The Art of Archival</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,500;0,700;1,300;1,500&family=Manrope:wght@200;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #FFFFFF;
            --surface: #F9F8F6;
            --accent: #E5E7EB;
            --prism-gradient: linear-gradient(135deg, #fce7f3 0%, #e0f2fe 50%, #f0f9ff 100%);
        }
        body {
            background-color: var(--bg);
            color: #111111;
            font-family: 'Manrope', sans-serif;
            overflow-x: hidden;
        }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
        
        /* Prismatic Glass */
        .prism-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Refraction Effect */
        .refraction {
            position: relative;
        }
        .refraction::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(90deg, #ff000022, #00ff0022, #0000ff22);
            z-index: -1;
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .refraction:hover::before { opacity: 1; }

        /* Floating Animation */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .float { animation: float 6s ease-in-out infinite; }

        /* Custom Cursors/Focus */
        .lens-hover {
            cursor: crosshair;
        }

        /* Iridescent Border */
        .iridescent-border {
            position: relative;
            background: white;
            padding: 1px;
            border-radius: 24px;
            z-index: 1;
        }
        .iridescent-border::after {
            content: '';
            position: absolute;
            inset: -1px;
            background: linear-gradient(45deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 24px;
            z-index: -1;
            opacity: 0.3;
        }

        /* Iridescent Text */
        .text-prism {
            background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Section Dividers */
        .divider {
            height: 1px;
            background: radial-gradient(circle, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 100%);
        }

        #meter-viz {
            transition: height 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <script type="importmap">
    {
      "imports": {
        "@google/genai": "https://esm.sh/@google/genai@^1.35.0"
      }
    }
    </script>
</head>
<body class="selection:bg-black selection:text-white">
    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 p-6">
        <div class="max-w-6xl mx-auto prism-glass rounded-full px-8 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 100 100" class="rotate-45">
                    <defs>
                        <linearGradient id="prism-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <polygon points="50,5 95,95 5,95" fill="url(#prism-grad)" />
                </svg>
                <span class="text-xl font-bold tracking-tighter uppercase">Prism</span>
            </div>
            <div class="hidden md:flex gap-8 text-[11px] font-extrabold uppercase tracking-[0.2em] opacity-50">
                <a href="#archives" class="hover:opacity-100 transition-opacity">Archives</a>
                <a href="#optics" class="hover:opacity-100 transition-opacity">Optics</a>
                <a href="#meter" class="hover:opacity-100 transition-opacity">Light Meter</a>
                <a href="#pricing" class="hover:opacity-100 transition-opacity">Vault</a>
            </div>
            <button class="bg-black text-white text-[10px] font-bold uppercase tracking-widest px-6 py-2.5 rounded-full hover:scale-105 transition-all">
                Access Vault
            </button>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-40 pb-20 px-6">
        <div class="max-w-6xl mx-auto text-center">
            <div class="inline-block mb-10 overflow-hidden">
                <span class="text-[10px] font-black uppercase tracking-[0.6em] text-slate-400 block animate-slide-up">EST. 2024 / PHOTOGRAPHIC INFRASTRUCTURE</span>
            </div>
            <h1 class="font-serif text-7xl md:text-[12rem] leading-[0.8] tracking-tighter mb-16 italic">
                Light <br><span class="not-italic text-prism">Into Data.</span>
            </h1>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center text-left">
                <div class="md:col-span-4 space-y-8">
                    <p class="text-lg text-slate-500 font-light leading-relaxed">
                        Prism is a high-fidelity archival layer for the modern photographer. We don't just host pixels; we preserve the physical metadata of light.
                    </p>
                    <div class="flex flex-col gap-4">
                        <button class="bg-black text-white px-8 py-5 rounded-2xl font-bold text-xs uppercase tracking-widest hover:shadow-2xl transition-all">
                            Initialize Storage
                        </button>
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-30">Triple-redundant SSD arrays active.</p>
                    </div>
                </div>
                <div class="md:col-span-8">
                    <div class="relative group">
                        <div class="aspect-[16/9] rounded-[3rem] overflow-hidden float">
                            <img src="https://images.unsplash.com/photo-1554048612-b6a482bc67e5?auto=format&fit=crop&q=80&w=1600" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000" alt="Gallery">
                        </div>
                        <!-- UI Overlay -->
                        <div class="absolute -bottom-6 -left-6 prism-glass p-6 rounded-3xl shadow-xl max-w-[200px]">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                <span class="text-[10px] font-bold uppercase tracking-widest">UPLINK STABLE</span>
                            </div>
                            <div class="space-y-2">
                                <div class="h-1 w-full bg-slate-100 rounded-full"><div class="h-full w-[80%] bg-black"></div></div>
                                <span class="text-[9px] font-bold opacity-40">4.2 TB / 5.0 TB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="divider"></div>

    <!-- The Journey of the Image -->
    <section id="optics" class="py-32 px-6 bg-slate-50/50">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-24">
                <div class="max-w-xl">
                    <h2 class="font-serif text-5xl md:text-7xl italic mb-6">The Lens to <br>The Vault.</h2>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-widest">A three-stage refinement process for your visual assets.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                <!-- Step 1 -->
                <div class="space-y-8 group">
                    <div class="aspect-square bg-white rounded-[2.5rem] flex items-center justify-center p-12 shadow-sm border border-slate-100 group-hover:shadow-xl transition-all">
                        <svg class="w-full text-slate-100 group-hover:text-indigo-50 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tighter">01. Raw Intake</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-light">Direct ingest for ARW, CR3, and DNG. We extract the full dynamic range previews before you even finish your first coffee.</p>
                </div>
                <!-- Step 2 -->
                <div class="space-y-8 group">
                    <div class="aspect-square bg-white rounded-[2.5rem] flex items-center justify-center p-12 shadow-sm border border-slate-100 group-hover:shadow-xl transition-all">
                        <svg class="w-full text-slate-100 group-hover:text-fuchsia-50 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.96-2.36L6.5 17h11l-3.54-4.71z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tighter">02. The Gallery Studio</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-light">Curate with speed. Our "Light Meter" AI assists in culling by identifying sharpness, focus points, and color balance consistency.</p>
                </div>
                <!-- Step 3 -->
                <div class="space-y-8 group">
                    <div class="aspect-square bg-white rounded-[2.5rem] flex items-center justify-center p-12 shadow-sm border border-slate-100 group-hover:shadow-xl transition-all">
                        <svg class="w-full text-slate-100 group-hover:text-sky-50 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold uppercase tracking-tighter">03. High-Key Delivery</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-light">Share cinematic, password-protected links. Your clients experience your work in a fluid, immersive high-resolution browser portal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- The Light Meter (AI tool) -->
    <section id="meter" class="py-32 px-6">
        <div class="max-w-4xl mx-auto iridescent-border p-12 md:p-24 shadow-2xl">
            <div class="text-center mb-16">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-indigo-500 mb-4 block">AI Engine</span>
                <h2 class="font-serif text-5xl italic mb-6">The Light Meter.</h2>
                <p class="text-sm text-slate-400 font-medium">Tell Prism about your gear and workload. We'll project your storage path.</p>
            </div>

            <div class="space-y-8">
                <div class="relative">
                    <input id="meter-input" type="text" placeholder="I shoot 2,000 photos/mo with a 61MP Sony A7R..." class="w-full bg-slate-50 border-none px-8 py-6 rounded-2xl text-sm font-medium outline-none focus:ring-2 ring-indigo-500/20 transition-all">
                    <button id="meter-btn" class="absolute right-3 top-3 bg-black text-white px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-all">Analyze</button>
                </div>

                <div id="meter-result" class="hidden prism-glass rounded-3xl p-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <div class="flex flex-col md:flex-row gap-12 items-center">
                        <div class="w-32 h-32 rounded-full border-8 border-slate-100 flex items-center justify-center relative">
                            <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="flex-1 space-y-4">
                            <h4 class="font-bold uppercase text-xs tracking-widest">Projection Analysis</h4>
                            <p id="meter-text" class="text-sm text-slate-600 leading-relaxed"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing (The Vault) -->
    <section id="pricing" class="py-32 px-6 bg-slate-50/50">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="font-serif text-6xl italic mb-6">Select Your Vault.</h2>
                <p class="text-xs font-bold uppercase tracking-[0.4em] text-slate-300">Annual Subscriptions</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Tier 1 -->
                <div class="bg-white p-12 rounded-[3rem] shadow-sm hover:shadow-xl transition-all border border-slate-100 flex flex-col justify-between">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-8 block">Option 01</span>
                        <h3 class="text-2xl font-bold tracking-tighter mb-4 uppercase">The Aperture</h3>
                        <p class="text-sm text-slate-400 font-light mb-10">Essential archival for independent artists.</p>
                        <div class="text-5xl font-serif italic mb-10">$18<span class="text-xs font-sans not-italic opacity-30"> / mo</span></div>
                        <ul class="space-y-4 text-xs font-bold uppercase tracking-widest text-slate-600 opacity-60">
                            <li>✦ 1.5 TB Secure Archival</li>
                            <li>✦ Dynamic Previews</li>
                            <li>✦ Standard Edge Links</li>
                        </ul>
                    </div>
                    <button class="w-full py-5 rounded-2xl bg-slate-50 text-black font-bold text-[10px] uppercase tracking-widest mt-12 hover:bg-black hover:text-white transition-all">Acquire Vault</button>
                </div>

                <!-- Tier 2 -->
                <div class="bg-black text-white p-12 rounded-[3rem] shadow-2xl scale-105 relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/20 blur-3xl rounded-full"></div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-indigo-400 mb-8 block">The Standard</span>
                        <h3 class="text-2xl font-bold tracking-tighter mb-4 uppercase">The Focal</h3>
                        <p class="text-sm text-slate-400 font-light mb-10">Optimized for active professional studios.</p>
                        <div class="text-5xl font-serif italic mb-10 text-indigo-400">$48<span class="text-xs font-sans not-italic opacity-30 text-white"> / mo</span></div>
                        <ul class="space-y-4 text-xs font-bold uppercase tracking-widest text-slate-300">
                            <li>✦ 8 TB Secure Archival</li>
                            <li>✦ Infinite Proof Galleries</li>
                            <li>✦ Priority Global Delivery</li>
                            <li>✦ AI Selection Assistant</li>
                        </ul>
                    </div>
                    <button class="w-full py-5 rounded-2xl bg-white text-black font-bold text-[10px] uppercase tracking-widest mt-12 hover:scale-105 transition-all">Acquire Vault</button>
                </div>

                <!-- Tier 3 -->
                <div class="bg-white p-12 rounded-[3rem] shadow-sm hover:shadow-xl transition-all border border-slate-100 flex flex-col justify-between">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-8 block">Option 03</span>
                        <h3 class="text-2xl font-bold tracking-tighter mb-4 uppercase">The Infinity</h3>
                        <p class="text-sm text-slate-400 font-light mb-10">Maximum scale for high-volume agencies.</p>
                        <div class="text-5xl font-serif italic mb-10">$128<span class="text-xs font-sans not-italic opacity-30"> / mo</span></div>
                        <ul class="space-y-4 text-xs font-bold uppercase tracking-widest text-slate-600 opacity-60">
                            <li>✦ Unmetered Storage</li>
                            <li>✦ Custom White-labeling</li>
                            <li>✦ API Pipeline Access</li>
                            <li>✦ Dedicated Concierge</li>
                        </ul>
                    </div>
                    <button class="w-full py-5 rounded-2xl bg-slate-50 text-black font-bold text-[10px] uppercase tracking-widest mt-12 hover:bg-black hover:text-white transition-all">Contact Curators</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-24">
                <div class="md:col-span-4">
                    <div class="flex items-center gap-3 mb-8">
                        <svg width="32" height="32" viewBox="0 0 100 100" class="rotate-45">
                            <polygon points="50,5 95,95 5,95" fill="black" />
                        </svg>
                        <span class="text-2xl font-bold tracking-tighter uppercase">Prism</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-loose uppercase tracking-[0.2em]">
                        Standardizing the digital archival of the visual arts. Dedicated to the light.
                    </p>
                </div>
                <div class="md:col-span-8 grid grid-cols-2 md:grid-cols-3 gap-12">
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest mb-8">Resources</h4>
                        <ul class="text-[10px] font-bold uppercase tracking-widest text-slate-400 space-y-4">
                            <li><a href="#" class="hover:text-black">Uptime Map</a></li>
                            <li><a href="#" class="hover:text-black">API Documentation</a></li>
                            <li><a href="#" class="hover:text-black">Press Kit</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest mb-8">Studio</h4>
                        <ul class="text-[10px] font-bold uppercase tracking-widest text-slate-400 space-y-4">
                            <li><a href="#" class="hover:text-black">My Vault</a></li>
                            <li><a href="#" class="hover:text-black">Integrations</a></li>
                            <li><a href="#" class="hover:text-black">Support</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest mb-8">Legal</h4>
                        <ul class="text-[10px] font-bold uppercase tracking-widest text-slate-400 space-y-4">
                            <li><a href="#" class="hover:text-black">Privacy Protocol</a></li>
                            <li><a href="#" class="hover:text-black">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-8 text-[9px] font-black uppercase tracking-[0.5em] text-slate-300">
                <span>&copy; 2024 PRISM ARCHIVAL PROTOCOL</span>
                <div class="flex gap-12">
                    <span>GENEVA</span>
                    <span>NEW YORK</span>
                    <span>TOKYO</span>
                </div>
            </div>
        </div>
    </footer>

    <script type="module">
        import { GoogleGenAI } from "@google/genai";

        // Scroll Logic for Nav
        const nav = document.querySelector('nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.style.transform = 'translateY(-10px)';
            } else {
                nav.style.transform = 'translateY(0)';
            }
        });

        // AI Light Meter Logic
        const meterInput = document.getElementById('meter-input');
        const meterBtn = document.getElementById('meter-btn');
        const meterResult = document.getElementById('meter-result');
        const meterText = document.getElementById('meter-text');

        meterBtn.addEventListener('click', async () => {
            const prompt = meterInput.value.trim();
            if (!prompt) return;

            meterBtn.disabled = true;
            meterBtn.innerText = 'CALCULATING...';
            meterResult.classList.add('hidden');

            try {
                const ai = new GoogleGenAI({ apiKey: "ENV_KEY" }); // handled
                const response = await ai.models.generateContent({
                    model: 'gemini-3-flash-preview',
                    contents: `You are the PRISM Light Meter AI. Photographers provide shooting volume/gear details: "${prompt}". Suggest one of our plans: Aperture ($18, 1.5TB), Focal ($48, 8TB), Infinity ($128, Unlim). Be incredibly sophisticated, technical, and precise. Use professional photography terminology. 2-3 sentences max.`,
                });
                
                meterText.innerText = response.text;
                meterResult.classList.remove('hidden');
                meterBtn.innerText = 'ANALYZE';
                meterBtn.disabled = false;
            } catch (err) {
                meterText.innerText = "System saturation occurred. Based on typical professional metadata, we recommend initializing with the Focal Tier ($48) for optimal studio headroom.";
                meterResult.classList.remove('hidden');
                meterBtn.innerText = 'ANALYZE';
                meterBtn.disabled = false;
            }
        });

        meterInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') meterBtn.click(); });
    </script>
</body>
</html>