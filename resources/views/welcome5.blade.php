<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUX | Digital Archival Laboratory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lux-orange: #FF4F00;
            --lux-bg: #FDFDFD;
            --lux-border: #E5E5E5;
        }
        body {
            background-color: var(--lux-bg);
            color: #111111;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        .font-serif { font-family: 'Instrument Serif', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* Sidebar Layout */
        @media (min-width: 1024px) {
            .sidebar {
                width: 320px;
                position: fixed;
                height: 100vh;
                border-right: 1px solid var(--lux-border);
                z-index: 50;
            }
            .main-canvas {
                margin-left: 320px;
            }
        }

        /* Hover Magnifier Effect */
        .loupe-card {
            position: relative;
            overflow: hidden;
            border: 1px solid var(--lux-border);
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
        }
        .loupe-card:hover {
            border-color: var(--lux-orange);
            transform: translateY(-4px);
        }
        .loupe-card img {
            transition: transform 0.8s ease;
        }
        .loupe-card:hover img {
            transform: scale(1.08);
        }

        /* Technical Callouts */
        .callout-line {
            stroke-dasharray: 4;
            animation: dash 20s linear infinite;
        }
        @keyframes dash {
            to { stroke-dashoffset: 100; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E5E5; }
        ::-webkit-scrollbar-thumb:hover { background: var(--lux-orange); }

        /* AI Cursor */
        .ai-status-pulse {
            width: 8px;
            height: 8px;
            background: var(--lux-orange);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--lux-orange);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.5; }
        }

        /* Section Transition */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
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
<body class="selection:bg-[#FF4F00] selection:text-white">

    <!-- Sidebar Dashboard -->
    <aside class="sidebar hidden lg:flex flex-col bg-white p-10 justify-between">
        <div class="space-y-12">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 border-2 border-black flex items-center justify-center font-bold text-xl">L</div>
                <h1 class="font-bold tracking-tighter text-2xl uppercase">Lux</h1>
            </div>

            <nav class="flex flex-col gap-6 text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400">
                <a href="#vision" class="hover:text-[#FF4F00] transition-colors flex items-center gap-2">
                    <span class="w-1 h-1 bg-slate-200"></span> 01. The Vision
                </a>
                <a href="#archives" class="hover:text-[#FF4F00] transition-colors flex items-center gap-2">
                    <span class="w-1 h-1 bg-slate-200"></span> 02. Archives
                </a>
                <a href="#curator" class="hover:text-[#FF4F00] transition-colors flex items-center gap-2">
                    <span class="w-1 h-1 bg-slate-200"></span> 03. AI Curator
                </a>
                <a href="#pricing" class="hover:text-[#FF4F00] transition-colors flex items-center gap-2">
                    <span class="w-1 h-1 bg-slate-200"></span> 04. Vault Tiers
                </a>
            </nav>
        </div>

        <div class="space-y-8">
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[9px] font-bold uppercase tracking-widest opacity-40">Network Load</span>
                    <div class="ai-status-pulse"></div>
                </div>
                <div class="h-1 w-full bg-slate-200 rounded-full overflow-hidden">
                    <div class="h-full w-[65%] bg-[#FF4F00]"></div>
                </div>
                <p class="text-[9px] font-mono mt-3 opacity-40 uppercase tracking-widest">Global Uplink: Optimal</p>
            </div>
            
            <button class="w-full bg-black text-white py-4 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-[#FF4F00] transition-colors rounded-xl">
                Open Dashboard
            </button>
        </div>
    </aside>

    <!-- Mobile Nav (Only visible on small screens) -->
    <div class="lg:hidden fixed top-0 w-full z-50 p-4">
        <div class="bg-white border border-slate-200 p-4 rounded-2xl flex justify-between items-center shadow-sm">
            <span class="font-bold tracking-tighter uppercase">Lux</span>
            <button class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 border border-slate-200 rounded-lg">Menu</button>
        </div>
    </div>

    <!-- Main Content Canvas -->
    <main class="main-canvas min-h-screen">
        
        <!-- Hero: Asymmetric Narrative -->
        <section id="vision" class="min-h-screen flex flex-col justify-center px-6 md:px-20 py-32 border-b border-slate-100 relative">
            <div class="max-w-4xl relative z-10">
                <h2 class="text-[10px] font-bold uppercase tracking-[0.6em] text-[#FF4F00] mb-8">Archival Excellence // V1.0</h2>
                <h3 class="font-serif text-6xl md:text-[8rem] leading-[0.9] tracking-tight mb-12">
                    The Sanctuary for <br> <span class="italic text-slate-300">Pure Optics.</span>
                </h3>
                <p class="text-xl md:text-2xl text-slate-500 font-light leading-relaxed max-w-2xl mb-16">
                    Professional photographers don't just "upload" files. They archive light. Lux is the technical home for high-bitrate digital assets.
                </p>
                
                <div class="flex flex-wrap gap-12 items-end">
                    <button class="group flex items-center gap-6">
                        <div class="w-16 h-16 rounded-full border border-black flex items-center justify-center group-hover:bg-[#FF4F00] group-hover:border-[#FF4F00] transition-all">
                            <svg class="w-6 h-6 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-[0.2em]">Begin Ingest</span>
                    </button>

                    <div class="hidden md:flex gap-16 text-slate-400 font-mono text-[10px] uppercase tracking-widest border-l border-slate-200 pl-16">
                        <div>
                            <span class="block mb-2 text-black font-bold">Latency</span>
                            <span>< 12ms</span>
                        </div>
                        <div>
                            <span class="block mb-2 text-black font-bold">Bit-Depth</span>
                            <span>16-Bit Native</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Absolute Hero Image -->
            <div class="lg:absolute top-1/2 right-10 -translate-y-1/2 w-full lg:w-[450px] mt-20 lg:mt-0">
                <div class="loupe-card aspect-[3/4] rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1492691523567-61723c275df8?auto=format&fit=crop&q=80&w=1200" class="w-full h-full object-cover">
                </div>
                <div class="mt-6 flex justify-between items-center px-2">
                    <span class="text-[9px] font-mono opacity-30">IMG_0293.RAW</span>
                    <span class="text-[9px] font-mono opacity-30">ISO 100 / 35MM / F1.4</span>
                </div>
            </div>
        </section>

        <!-- Gallery / Archives: Grid Layout -->
        <section id="archives" class="py-32 px-6 md:px-20 bg-slate-50">
            <div class="flex flex-col md:flex-row justify-between items-start mb-24 gap-8">
                <div>
                    <h4 class="font-serif text-4xl md:text-6xl mb-4">The Archive Grid.</h4>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Curated Delivery Ecosystem</p>
                </div>
                <div class="max-w-xs text-sm text-slate-500 leading-relaxed font-light">
                    Every shared link is a bespoke exhibition. Your clients don't just download files; they step into your vision.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-8 loupe-card h-[400px] rounded-3xl overflow-hidden reveal">
                    <img src="https://images.unsplash.com/photo-1554048612-b6a482bc67e5?auto=format&fit=crop&q=80&w=1400" class="w-full h-full object-cover">
                    <div class="absolute bottom-6 right-6 p-4 bg-white/80 backdrop-blur-md rounded-2xl border border-white/40">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Shareable Gallery V1</span>
                    </div>
                </div>
                <div class="md:col-span-4 loupe-grow h-[400px] bg-black text-white p-12 rounded-3xl flex flex-col justify-between reveal">
                    <h5 class="text-2xl font-serif">Instant Link <br> Generation.</h5>
                    <div class="space-y-4">
                        <div class="h-[1px] w-full bg-white/20"></div>
                        <p class="text-[10px] font-mono opacity-60 uppercase tracking-widest">One click. Zero latency. Professional white-label branding for every studio.</p>
                    </div>
                </div>
                <div class="md:col-span-4 loupe-card h-[300px] rounded-3xl overflow-hidden reveal">
                    <img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=1000" class="w-full h-full object-cover">
                </div>
                <div class="md:col-span-8 loupe-card h-[300px] rounded-3xl overflow-hidden reveal">
                    <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&q=80&w=1400" class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        <!-- AI Curator: Chat Interface -->
        <section id="curator" class="py-32 px-6 md:px-20 border-b border-slate-100">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center gap-4 mb-16">
                    <div class="ai-status-pulse"></div>
                    <h4 class="text-sm font-bold uppercase tracking-[0.4em]">The Digital Curator</h4>
                </div>
                
                <h2 class="font-serif text-5xl md:text-7xl italic mb-12">Project your <br> archival growth.</h2>
                
                <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl overflow-hidden">
                    <div id="curator-chat" class="h-[300px] overflow-y-auto p-10 space-y-6 text-sm font-light text-slate-500 leading-relaxed border-b border-slate-50">
                        <div class="flex gap-4">
                            <div class="w-2 h-2 rounded-full bg-[#FF4F00] mt-1"></div>
                            <p>Calibration complete. I am ready to calculate your storage trajectory. Describe your typical monthly workflow (resolutions, shoot frequency, etc).</p>
                        </div>
                    </div>
                    <div class="p-8 flex gap-4 items-center bg-slate-50/50">
                        <input id="curator-input" type="text" placeholder="I shoot 10 commercial sessions per month at 50MP..." class="flex-1 bg-transparent border-none outline-none font-medium text-sm text-black">
                        <button id="curator-send" class="bg-black text-white px-8 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-[#FF4F00] transition-colors">Process</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing: Grid Cards -->
        <section id="pricing" class="py-32 px-6 md:px-20">
            <div class="mb-20 text-center">
                <h2 class="font-serif text-5xl md:text-7xl mb-4">Choose Your Vault.</h2>
                <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-slate-400">Monthly Subscriptions / Cancel Anytime</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-slate-100 rounded-[3rem] overflow-hidden">
                <div class="p-16 border-b md:border-b-0 md:border-r border-slate-100 hover:bg-slate-50 transition-colors">
                    <h5 class="text-xs font-bold uppercase tracking-widest text-[#FF4F00] mb-12">Option 01</h5>
                    <h6 class="text-3xl font-serif mb-6 italic">The Minimalist</h6>
                    <div class="text-4xl font-bold tracking-tighter mb-10">$19<span class="text-xs opacity-30">/MO</span></div>
                    <ul class="space-y-4 text-xs text-slate-400 mb-12">
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-200"></span> 2TB SSD Archival</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-200"></span> Unlimited Links</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-200"></span> Standard Support</li>
                    </ul>
                    <button class="w-full py-4 border border-black text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-xl">Acquire</button>
                </div>
                <div class="p-16 border-b md:border-b-0 md:border-r border-slate-100 bg-black text-white relative">
                    <div class="absolute top-8 right-8 text-[8px] font-bold bg-[#FF4F00] text-white px-3 py-1 rounded-full uppercase tracking-widest">Recommended</div>
                    <h5 class="text-xs font-bold uppercase tracking-widest text-[#FF4F00] mb-12">Option 02</h5>
                    <h6 class="text-3xl font-serif mb-6 italic">The Studio</h6>
                    <div class="text-4xl font-bold tracking-tighter mb-10">$49<span class="text-xs opacity-30">/MO</span></div>
                    <ul class="space-y-4 text-xs text-slate-300 mb-12">
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-[#FF4F00]"></span> 10TB SSD Archival</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-[#FF4F00]"></span> Custom Subdomains</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-[#FF4F00]"></span> AI Curator Access</li>
                    </ul>
                    <button class="w-full py-4 bg-[#FF4F00] text-white text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-all rounded-xl">Acquire</button>
                </div>
                <div class="p-16 hover:bg-slate-50 transition-colors">
                    <h5 class="text-xs font-bold uppercase tracking-widest text-[#FF4F00] mb-12">Option 03</h5>
                    <h6 class="text-3xl font-serif mb-6 italic">The Collective</h6>
                    <div class="text-4xl font-bold tracking-tighter mb-10">$129<span class="text-xs opacity-30">/MO</span></div>
                    <ul class="space-y-4 text-xs text-slate-400 mb-12">
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-200"></span> Unlimited Storage</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-200"></span> Team Multi-Seats</li>
                        <li class="flex items-center gap-2"><span class="w-1 h-1 bg-slate-200"></span> API Pipeline Access</li>
                    </ul>
                    <button class="w-full py-4 border border-black text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all rounded-xl">Acquire</button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-24 px-6 md:px-20 border-t border-slate-100 flex flex-col md:flex-row justify-between items-start gap-16">
            <div class="max-w-xs">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-8 border-2 border-black flex items-center justify-center font-bold text-sm">L</div>
                    <span class="font-bold tracking-tighter text-lg uppercase">Lux Archival</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-slate-400 leading-relaxed">Dedicated to the preservation of photographic legacy. Standard 402.2 Compliant.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-16">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-black mb-6">Index</h4>
                    <ul class="text-[10px] font-bold text-slate-400 space-y-3 uppercase tracking-widest">
                        <li><a href="#" class="hover:text-[#FF4F00]">About</a></li>
                        <li><a href="#" class="hover:text-[#FF4F00]">Systems</a></li>
                        <li><a href="#" class="hover:text-[#FF4F00]">Legal</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-black mb-6">Contact</h4>
                    <ul class="text-[10px] font-bold text-slate-400 space-y-3 uppercase tracking-widest">
                        <li><a href="#" class="hover:text-[#FF4F00]">Support</a></li>
                        <li><a href="#" class="hover:text-[#FF4F00]">Inquiries</a></li>
                        <li><a href="#" class="hover:text-[#FF4F00]">Status</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </main>

    <script type="module">
        import { GoogleGenAI } from "@google/genai";

        // Scroll Reveal
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // AI Curator Logic
        const chatInput = document.getElementById('curator-input');
        const sendBtn = document.getElementById('curator-send');
        const chatBox = document.getElementById('curator-chat');

        async function logMessage(role, text) {
            const div = document.createElement('div');
            div.className = "flex gap-4 animate-in fade-in slide-in-from-bottom-2 duration-500";
            div.innerHTML = `
                <div class="w-2 h-2 rounded-full ${role === 'ai' ? 'bg-[#FF4F00]' : 'bg-black'} mt-1"></div>
                <p class="${role === 'user' ? 'text-black font-semibold' : 'text-slate-500'}">${text}</p>
            `;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        sendBtn.addEventListener('click', async () => {
            const prompt = chatInput.value.trim();
            if (!prompt) return;

            chatInput.value = '';
            logMessage('user', prompt);

            const loading = document.createElement('div');
            loading.className = 'text-[10px] font-mono opacity-20 uppercase tracking-widest animate-pulse ml-6';
            loading.innerText = 'Calculating spectral density and archival load...';
            chatBox.appendChild(loading);

            try {
                const ai = new GoogleGenAI({ apiKey: "ENV_KEY" }); // handled
                const response = await ai.models.generateContent({
                    model: 'gemini-3-flash-preview',
                    contents: `Context: You are the LUX Digital Curator. Photographers provide shooting volume: "${prompt}". Suggest one of our plans: Minimalist ($19, 2TB), Studio ($49, 10TB), Collective ($129, Unlim). Be incredibly professional, brief, and use technical terminology (ISO, dynamic range, bit-depth). 2 sentences max.`,
                });
                loading.remove();
                logMessage('ai', response.text);
            } catch (err) {
                loading.remove();
                logMessage('ai', "SYSTEM ERROR: FAILED TO PROCESS TRAJECTORY. RECOMMENDATION: STUDIO TIER FOR MAXIMUM HEADROOM.");
            }
        });

        chatInput.addEventListener('keypress', (e) => { if(e.key === 'Enter') sendBtn.click(); });
    </script>
</body>
</html>