<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRAME | High-Performance Storage for Creators</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@700,600,500&f[]=space-mono@400,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #FF3B00;
            --dark: #111111;
            --bg: #FFFFFF;
        }
        body {
            font-family: 'Space Mono', monospace;
            background-color: var(--bg);
            color: var(--dark);
            overflow-x: hidden;
        }
        .font-heading {
            font-family: 'Clash Display', sans-serif;
        }
        .grid-lines {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, #f0f0f0 1px, transparent 1px), linear-gradient(to bottom, #f0f0f0 1px, transparent 1px);
        }
        .marquee {
            white-space: nowrap;
            overflow: hidden;
            display: inline-block;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .border-bold {
            border: 2px solid var(--dark);
        }
        .shadow-bold {
            box-shadow: 8px 8px 0px 0px var(--dark);
        }
        .shadow-bold-hover:hover {
            box-shadow: 12px 12px 0px 0px var(--accent);
            transform: translate(-4px, -4px);
        }
        .transition-brutal {
            transition: all 0.2s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        section {
            border-bottom: 2px solid #f0f0f0;
        }
        #nav.scrolled {
            background: white;
            border-bottom: 2px solid var(--dark);
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
<body class="selection:bg-[#FF3B00] selection:text-white">
    <!-- Marquee Top Bar -->
    <div class="bg-black text-white py-2 overflow-hidden border-b-2 border-black">
        <div class="marquee flex gap-12 text-[10px] font-bold uppercase tracking-widest">
            <span>Unlimited RAW Storage Available</span>
            <span>✦</span>
            <span>New: Global Edge Delivery</span>
            <span>✦</span>
            <span>Used by 500+ Studios Globally</span>
            <span>✦</span>
            <span>Unlimited RAW Storage Available</span>
            <span>✦</span>
            <span>New: Global Edge Delivery</span>
            <span>✦</span>
            <span>Used by 500+ Studios Globally</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav id="nav" class="sticky top-0 z-50 py-6 px-6 bg-white transition-all duration-300">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#FF3B00] flex items-center justify-center text-white font-bold">F</div>
                <span class="font-heading text-2xl tracking-tighter uppercase">Frame</span>
            </div>
            <div class="hidden md:flex gap-10 text-[11px] font-bold uppercase tracking-widest">
                <a href="#features" class="hover:text-[#FF3B00] transition-colors">Technical Specs</a>
                <a href="#workflow" class="hover:text-[#FF3B00] transition-colors">Workflow</a>
                <a href="#pricing" class="hover:text-[#FF3B00] transition-colors">Pricing</a>
                <a href="#advisor" class="hover:text-[#FF3B00] transition-colors">AI Advisor</a>
            </div>
            <button class="border-bold px-6 py-2 text-[11px] font-bold uppercase tracking-widest shadow-bold transition-brutal hover:bg-black hover:text-white">
                Dashboard
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative grid-lines">
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-12 border-x-2 border-slate-100">
            <div class="lg:col-span-7 p-8 lg:p-24 border-r-2 border-slate-100 flex flex-col justify-center">
                <h1 class="font-heading text-6xl lg:text-[120px] leading-[0.9] tracking-tighter mb-12">
                    STORAGE <br>FOR THE <br><span class="text-[#FF3B00]">OBSESSED.</span>
                </h1>
                <p class="text-lg text-slate-500 max-w-lg mb-12 font-medium leading-relaxed">
                    Zero compression. Instant previews. Built for the technical requirements of modern digital photography.
                </p>
                <div class="flex flex-wrap gap-4">
                    <button class="bg-black text-white px-10 py-5 font-bold uppercase tracking-widest text-xs border-bold shadow-bold hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                        Create Account
                    </button>
                    <button class="bg-white text-black px-10 py-5 font-bold uppercase tracking-widest text-xs border-bold hover:bg-slate-50 transition-colors">
                        The Spec Sheet
                    </button>
                </div>
            </div>
            <div class="lg:col-span-5 relative min-h-[500px] overflow-hidden group">
                <img src="https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&q=80&w=1200" 
                     class="absolute inset-0 w-full h-full object-cover grayscale transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" alt="Camera lens">
                <div class="absolute bottom-8 right-8 bg-white border-bold p-6 shadow-bold">
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-2">Technical Status</p>
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-bold">EDGE NETWORK ACTIVE</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Technical Specs -->
    <section id="features" class="py-24 px-6 bg-[#F9FAFB]">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-20">
                <h2 class="font-heading text-4xl lg:text-7xl tracking-tighter uppercase leading-none">
                    ENGINEERED <br>BY ARTISTS.
                </h2>
                <div class="max-w-md">
                    <p class="text-sm font-bold uppercase tracking-widest text-[#FF3B00] mb-4">Core Technology</p>
                    <p class="text-slate-500 leading-relaxed">We don't just host files. We preserve data. Every pixel, every metadata flag, every color profile is kept in its native state.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white border-bold p-8 shadow-bold transition-brutal shadow-bold-hover group">
                    <span class="text-4xl font-bold mb-8 block group-hover:text-[#FF3B00]">01.</span>
                    <h3 class="font-heading text-xl uppercase mb-4">RAW ENGINE</h3>
                    <p class="text-xs text-slate-500 leading-loose">Instant high-res previews for .ARW, .CR3, .DNG and more. No more waiting for downloads to see your work.</p>
                </div>
                <div class="bg-white border-bold p-8 shadow-bold transition-brutal shadow-bold-hover group">
                    <span class="text-4xl font-bold mb-8 block group-hover:text-[#FF3B00]">02.</span>
                    <h3 class="font-heading text-xl uppercase mb-4">GLOBAL CDN</h3>
                    <p class="text-xs text-slate-500 leading-loose">Upload from a shoot in Tokyo, deliver to a client in London. Distributed servers mean zero latency.</p>
                </div>
                <div class="bg-white border-bold p-8 shadow-bold transition-brutal shadow-bold-hover group">
                    <span class="text-4xl font-bold mb-8 block group-hover:text-[#FF3B00]">03.</span>
                    <h3 class="font-heading text-xl uppercase mb-4">WHITE LABEL</h3>
                    <p class="text-xs text-slate-500 leading-loose">Your domain, your brand. Our technology fades into the background, letting your work take center stage.</p>
                </div>
                <div class="bg-white border-bold p-8 shadow-bold transition-brutal shadow-bold-hover group">
                    <span class="text-4xl font-bold mb-8 block group-hover:text-[#FF3B00]">04.</span>
                    <h3 class="font-heading text-xl uppercase mb-4">SMART METRICS</h3>
                    <p class="text-xs text-slate-500 leading-loose">See exactly which photos your clients are spending time on. Actionable feedback for every gallery.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-24 px-6">
        <div class="max-w-[1440px] mx-auto">
            <div class="text-center mb-20">
                <h2 class="font-heading text-5xl lg:text-8xl tracking-tighter uppercase mb-6">CHOOSE YOUR <span class="italic text-slate-300">VOLUME.</span></h2>
                <p class="text-[10px] font-bold uppercase tracking-[0.5em] text-slate-400">Monthly Studio Subscriptions</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 border-2 border-black">
                <!-- Plan 1 -->
                <div class="p-12 border-b-2 lg:border-b-0 lg:border-r-2 border-black flex flex-col justify-between hover:bg-slate-50 transition-colors group">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF3B00] mb-8 block">Option 01</span>
                        <h3 class="font-heading text-3xl uppercase mb-4">The Freelancer</h3>
                        <p class="text-xs text-slate-500 mb-12 leading-loose">Ideal for individual contributors and weekend warriors.</p>
                        <div class="text-5xl font-bold tracking-tighter mb-12">$25<span class="text-xs uppercase tracking-widest opacity-40">/mo</span></div>
                        <ul class="space-y-4 mb-12">
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-black"></div> 1TB NVMe Storage</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-black"></div> 10 Live Galleries</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-black"></div> Standard Support</li>
                        </ul>
                    </div>
                    <button class="w-full border-bold py-4 text-xs font-bold uppercase tracking-widest transition-all group-hover:bg-black group-hover:text-white">Select Tier</button>
                </div>

                <!-- Plan 2 -->
                <div class="p-12 border-b-2 lg:border-b-0 lg:border-r-2 border-black bg-black text-white flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF3B00] mb-8 block">Option 02</span>
                        <h3 class="font-heading text-3xl uppercase mb-4">The Studio</h3>
                        <p class="text-xs text-slate-400 mb-12 leading-loose">The industry standard for professional commercial studios.</p>
                        <div class="text-5xl font-bold tracking-tighter mb-12 text-[#FF3B00]">$65<span class="text-xs uppercase tracking-widest opacity-40 text-white">/mo</span></div>
                        <ul class="space-y-4 mb-12">
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-[#FF3B00]"></div> 10TB NVMe Storage</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-[#FF3B00]"></div> Unlimited Galleries</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-[#FF3B00]"></div> Custom Domain Setup</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-[#FF3B00]"></div> Lightroom Direct Sync</li>
                        </ul>
                    </div>
                    <button class="w-full bg-[#FF3B00] text-white py-4 text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-black transition-all">Select Tier</button>
                </div>

                <!-- Plan 3 -->
                <div class="p-12 flex flex-col justify-between hover:bg-slate-50 transition-colors group">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF3B00] mb-8 block">Option 03</span>
                        <h3 class="font-heading text-3xl uppercase mb-4">The Enterprise</h3>
                        <p class="text-xs text-slate-500 mb-12 leading-loose">Global agencies requiring massive scale and custom SLAs.</p>
                        <div class="text-5xl font-bold tracking-tighter mb-12">$199<span class="text-xs uppercase tracking-widest opacity-40">/mo</span></div>
                        <ul class="space-y-4 mb-12">
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-black"></div> Unlimited Storage</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-black"></div> 24/7 Priority Concierge</li>
                            <li class="flex items-center gap-3 text-xs font-bold uppercase"><div class="w-1.5 h-1.5 bg-black"></div> API Access</li>
                        </ul>
                    </div>
                    <button class="w-full border-bold py-4 text-xs font-bold uppercase tracking-widest transition-all group-hover:bg-black group-hover:text-white">Contact Sales</button>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Advisor Section -->
    <section id="advisor" class="py-24 px-6 grid-lines">
        <div class="max-w-[1000px] mx-auto bg-white border-bold shadow-bold p-8 lg:p-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <div class="lg:col-span-5">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF3B00] mb-6 block">Frame Assistant v1.0</span>
                    <h2 class="font-heading text-4xl uppercase mb-6 leading-tight">CALCULATE YOUR <br>EXPOSURE.</h2>
                    <p class="text-xs text-slate-500 leading-loose mb-8">Tell our assistant how many shoots you handle and your average file size. We'll suggest a tier.</p>
                    <div id="advisor-chat" class="h-[200px] overflow-y-auto mb-8 pr-4 space-y-4 text-[11px] border-l-2 border-slate-100 pl-4 font-bold">
                        <div class="text-slate-400 uppercase tracking-widest italic">Awaiting technical parameters...</div>
                    </div>
                </div>
                <div class="lg:col-span-7 flex flex-col justify-end">
                    <div class="relative">
                        <textarea id="advisor-input" placeholder="Example: I shoot 12 commercial sessions/mo in 100MP RAW..." 
                                  class="w-full border-bold p-6 text-sm font-bold uppercase outline-none focus:border-[#FF3B00] transition-colors resize-none h-40"></textarea>
                        <button id="advisor-send" class="absolute bottom-4 right-4 bg-black text-white px-6 py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-[#FF3B00] transition-colors">
                            Process
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-24 px-6">
        <div class="max-w-[1440px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-24">
                <div class="col-span-1 lg:col-span-2">
                    <div class="flex items-center gap-2 mb-8">
                        <div class="w-8 h-8 bg-[#FF3B00] flex items-center justify-center text-white font-bold">F</div>
                        <span class="font-heading text-3xl tracking-tighter uppercase">Frame</span>
                    </div>
                    <p class="text-slate-400 max-w-sm text-xs leading-loose font-medium">
                        Frame is a specialized infrastructure platform for visual media archival. Dedicated to the preservation of high-fidelity digital art.
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-white mb-8">Index</h4>
                    <ul class="text-xs text-slate-500 space-y-4 uppercase font-bold tracking-widest">
                        <li><a href="#" class="hover:text-white">Network Status</a></li>
                        <li><a href="#" class="hover:text-white">API Docs</a></li>
                        <li><a href="#" class="hover:text-white">Brand Assets</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-white mb-8">Contact</h4>
                    <ul class="text-xs text-slate-500 space-y-4 uppercase font-bold tracking-widest">
                        <li><a href="#" class="hover:text-white">X / Twitter</a></li>
                        <li><a href="#" class="hover:text-white">Support Dept</a></li>
                        <li><a href="#" class="hover:text-white">Legal</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex justify-between items-center pt-12 border-t border-white/10 text-[10px] text-slate-600 font-bold uppercase tracking-[0.4em]">
                <span>&copy; 2024 FRAME PROTOCOL INC.</span>
                <span>SYSTEM STATUS: OPTIMAL</span>
            </div>
        </div>
    </footer>

    <!-- Logic -->
    <script type="module">
        import { GoogleGenAI } from "@google/genai";

        // Nav Scroll Effect
        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        });

        // AI Advisor Logic
        const chatBox = document.getElementById('advisor-chat');
        const input = document.getElementById('advisor-input');
        const sendBtn = document.getElementById('advisor-send');

        async function log(role, msg) {
            const div = document.createElement('div');
            div.className = `mb-4 ${role === 'ai' ? 'text-[#FF3B00]' : 'text-black'}`;
            div.innerHTML = `<span class="opacity-30 mr-2">[${role.toUpperCase()}]</span> ${msg}`;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        sendBtn.addEventListener('click', async () => {
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            chatBox.innerHTML = ''; // Clear initial message
            log('user', text);

            const loading = document.createElement('div');
            loading.className = 'text-slate-300 animate-pulse';
            loading.innerText = 'Analyzing storage requirements...';
            chatBox.appendChild(loading);

            try {
                const ai = new GoogleGenAI({ apiKey: "ENV_KEY" }); 
                const response = await ai.models.generateContent({
                    model: 'gemini-3-flash-preview',
                    contents: `User asks: ${text}. You are the technical advisor for FRAME (storage for photographers). Plans: Freelancer ($25, 1TB), Studio ($65, 10TB), Enterprise ($199, Unlim). Calculate needs and suggest. Be very technical, brief, and professional. 📸`,
                });
                
                loading.remove();
                log('ai', response.text);
            } catch (err) {
                loading.remove();
                log('ai', "SYSTEM ERROR: UNABLE TO PROCESS PARAMETERS. RECOMMENDATION: STUDIO TIER (DEFAULT).");
            }
        });
    </script>
</body>
</html>