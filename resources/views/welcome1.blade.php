<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lumina | The Professional Photographer's Vault</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #0f172a;
            --soft-bg: #fdfdfd;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--soft-bg);
            color: var(--accent);
            scroll-behavior: smooth;
        }
        .font-serif {
            font-family: 'Instrument Serif', serif;
        }
        .frosted {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: -2px;
            left: 0;
            background-color: var(--accent);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        #navbar.scrolled {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            background: rgba(253, 253, 253, 0.9);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .pricing-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .pricing-card:hover {
            transform: scale(1.02);
            box-shadow: 0 40px 80px -20px rgba(0,0,0,0.1);
        }
        .orb {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(15, 23, 42, 0.03) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: -1;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
    <script type="importmap">
{
  "imports": {
    "@google/genai": "https://esm.sh/@google/genai@^1.35.0",
    "react": "https://esm.sh/react@^19.2.3",
    "react/": "https://esm.sh/react@^19.2.3/"
  }
}
</script>
</head>
<body class="antialiased">
    <div class="orb top-0 right-0"></div>
    <div class="orb bottom-0 left-0"></div>

    <!-- Navigation -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-500 py-8 px-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-10 h-10 border-2 border-slate-900 rounded-full flex items-center justify-center overflow-hidden">
                    <div class="w-5 h-5 bg-slate-900 transform group-hover:rotate-45 transition-transform duration-500"></div>
                </div>
                <span class="text-2xl font-bold tracking-tighter uppercase italic">Lumina</span>
            </div>

            <div class="hidden lg:flex items-center gap-12 text-sm font-semibold tracking-wide uppercase opacity-70">
                <a href="#features" class="nav-link">Curation</a>
                <a href="#delivery" class="nav-link">Workflow</a>
                <a href="#pricing" class="nav-link">Plans</a>
                <a href="#concierge" class="nav-link">Studio AI</a>
            </div>

            <div class="flex items-center gap-6">
                <a href="#" class="text-sm font-bold uppercase tracking-widest hover:opacity-50 transition-opacity">Login</a>
                <button class="bg-slate-900 text-white px-8 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg hover:shadow-slate-300">
                    Get Access
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-48 pb-32 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-block px-4 py-1.5 rounded-full bg-slate-100 text-[10px] font-bold uppercase tracking-[0.2em] mb-8 animate-pulse">
                Now serving the world's finest visual artists
            </div>
            <h1 class="text-6xl md:text-[7.5rem] font-serif leading-[0.85] tracking-tight mb-12">
                A gallery that <br> <span class="italic">never</span> fills up.
            </h1>
            <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-500 font-medium leading-relaxed mb-16">
                Lumina provides zero-compression cloud storage and immersive client delivery tools, tailored for the meticulous professional photographer.
            </p>
            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mb-24">
                <button class="w-full md:w-auto px-12 py-5 bg-slate-900 text-white rounded-full font-bold text-sm tracking-widest uppercase hover:px-14 transition-all duration-300">
                    Claim your storage
                </button>
                <button class="w-full md:w-auto px-12 py-5 bg-white border border-slate-200 text-slate-900 rounded-full font-bold text-sm tracking-widest uppercase hover:bg-slate-50 transition-all">
                    View Demo Gallery
                </button>
            </div>
            
            <!-- Hero Visual -->
            <div class="relative max-w-6xl mx-auto rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white reveal">
                <img src="https://images.unsplash.com/photo-1493863641943-9b68992a8d07?auto=format&fit=crop&q=80&w=2000" alt="Photographer Work" class="w-full h-auto object-cover">
                <div class="absolute bottom-10 left-10 p-6 frosted rounded-2xl max-w-xs text-left hidden md:block">
                    <p class="text-xs font-bold uppercase tracking-widest opacity-40 mb-2">Current Activity</p>
                    <p class="text-sm font-semibold mb-4">"Highland Series" RAW upload complete</p>
                    <div class="flex -space-x-3">
                        <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white"></div>
                        <div class="w-8 h-8 rounded-full bg-slate-300 border-2 border-white"></div>
                        <div class="w-8 h-8 rounded-full bg-slate-400 border-2 border-white"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Masonry -->
    <section id="features" class="py-32 px-6 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="mb-24 flex flex-col md:flex-row justify-between items-end gap-8">
                <div class="max-w-2xl">
                    <h2 class="text-4xl md:text-6xl font-serif mb-6 leading-tight">Meticulously crafted <br> for <span class="italic underline underline-offset-8">the lens.</span></h2>
                    <p class="text-slate-500 font-medium italic">Performance meets aesthetics at every byte.</p>
                </div>
                <div class="text-right">
                    <span class="text-[8rem] font-serif opacity-5 leading-none">01</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-4 bg-white p-12 rounded-[2.5rem] shadow-sm border border-slate-100 reveal">
                    <div class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center mb-8">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Unthrottled Uploads</h3>
                    <p class="text-slate-500 leading-relaxed">Proprietary architecture ensures your 100MB RAW files move as fast as your connection allows. No artificial limits.</p>
                </div>
                <div class="md:col-span-8 bg-white p-12 rounded-[2.5rem] shadow-sm border border-slate-100 reveal" style="transition-delay: 100ms">
                    <div class="grid md:grid-cols-2 gap-12">
                        <div>
                            <h3 class="text-2xl font-bold mb-4">Elegant Deliverables</h3>
                            <p class="text-slate-500 leading-relaxed">Turn your storage into client galleries with one click. Customizable themes that respect your artistic brand identity.</p>
                            <button class="mt-8 text-sm font-bold border-b-2 border-slate-900 pb-1 hover:pr-4 transition-all">Explore Showcase</button>
                        </div>
                        <div class="rounded-2xl overflow-hidden bg-slate-100 h-full min-h-[200px]">
                            <img src="https://images.unsplash.com/photo-1452587925148-ce544e77e70d?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700" alt="Camera Gear">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-32 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-24">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] opacity-30">Studio Investment</span>
                <h2 class="text-5xl md:text-7xl font-serif mt-4">Scalable storage for <br> <span class="italic">limitless vision.</span></h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Starter -->
                <div class="pricing-card bg-white p-12 rounded-[3rem] border border-slate-100 flex flex-col justify-between reveal">
                    <div>
                        <div class="flex justify-between items-start mb-12">
                            <div>
                                <h3 class="text-xl font-bold">The Apprentice</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Foundational Space</p>
                            </div>
                            <span class="text-4xl font-serif italic">Free</span>
                        </div>
                        <ul class="space-y-6 mb-12">
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-slate-900"></div> 100GB Storage</li>
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-slate-900"></div> 5 Client Galleries</li>
                            <li class="flex items-center gap-3 text-sm font-medium opacity-30"><div class="w-1.5 h-1.5 rounded-full bg-slate-900"></div> RAW Previews</li>
                        </ul>
                    </div>
                    <button class="w-full py-5 rounded-full border-2 border-slate-100 font-bold text-xs uppercase tracking-widest hover:border-slate-900 transition-colors">Select Plan</button>
                </div>

                <!-- Pro -->
                <div class="pricing-card bg-slate-900 text-white p-12 rounded-[3rem] flex flex-col justify-between shadow-2xl reveal" style="transition-delay: 150ms">
                    <div class="absolute top-8 right-8 bg-white/10 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Most Selected</div>
                    <div>
                        <div class="flex justify-between items-start mb-12">
                            <div>
                                <h3 class="text-xl font-bold">The Professional</h3>
                                <p class="text-xs text-white/40 font-bold uppercase tracking-widest mt-1">Growth Engine</p>
                            </div>
                            <div class="text-right">
                                <span class="text-4xl font-serif italic">$39</span>
                                <p class="text-[10px] opacity-40 uppercase font-black">/mo</p>
                            </div>
                        </div>
                        <ul class="space-y-6 mb-12">
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-white"></div> 5TB Storage</li>
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-white"></div> Unlimited Galleries</li>
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-white"></div> Smart RAW Archiving</li>
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-white"></div> Direct Print Fulfillment</li>
                        </ul>
                    </div>
                    <button class="w-full py-5 rounded-full bg-white text-slate-900 font-bold text-xs uppercase tracking-widest hover:scale-105 transition-transform">Go Professional</button>
                </div>

                <!-- Studio -->
                <div class="pricing-card bg-white p-12 rounded-[3rem] border border-slate-100 flex flex-col justify-between reveal" style="transition-delay: 300ms">
                    <div>
                        <div class="flex justify-between items-start mb-12">
                            <div>
                                <h3 class="text-xl font-bold">The Collective</h3>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Enterprise scale</p>
                            </div>
                            <div class="text-right">
                                <span class="text-4xl font-serif italic">$99</span>
                                <p class="text-[10px] opacity-40 uppercase font-black">/mo</p>
                            </div>
                        </div>
                        <ul class="space-y-6 mb-12">
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-slate-900"></div> Unlimited Storage</li>
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-slate-900"></div> Multi-User Studio Access</li>
                            <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-slate-900"></div> White-Label Branding</li>
                        </ul>
                    </div>
                    <button class="w-full py-5 rounded-full border-2 border-slate-100 font-bold text-xs uppercase tracking-widest hover:border-slate-900 transition-colors">Select Plan</button>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Advisor Section -->
    <section id="concierge" class="py-32 px-6 bg-slate-50">
        <div class="max-w-5xl mx-auto">
            <div class="frosted p-12 md:p-20 rounded-[4rem] relative overflow-hidden shadow-xl border border-white">
                <div class="relative z-10 grid md:grid-cols-2 gap-16 items-center">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] opacity-30 mb-6 block">Lumina Intelligence</span>
                        <h2 class="text-4xl md:text-5xl font-serif mb-8 leading-tight">Your Digital <br> <span class="italic">Concierge.</span></h2>
                        <p class="text-slate-500 font-medium leading-relaxed mb-8">Not sure which storage volume you need? Our AI analyzes your shooting habits to recommend the perfect fit.</p>
                        
                        <!-- Chat Box -->
                        <div id="chat-container" class="bg-white/50 border border-slate-200 rounded-3xl p-6 h-[250px] overflow-y-auto mb-6 space-y-4 scroll-smooth">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center shrink-0">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <div class="bg-white p-4 rounded-2xl text-xs font-medium text-slate-600 shadow-sm leading-relaxed">
                                    "Hello. I'm the Lumina Studio Assistant. Tell me how many weddings or sessions you shoot monthly, and I'll find your plan."
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <input id="chat-input" type="text" placeholder="I shoot 4 commercial projects a month..." class="w-full bg-white border border-slate-200 rounded-full py-5 px-8 outline-none focus:border-slate-900 transition-colors text-sm font-medium">
                            <button id="chat-send" class="absolute right-3 top-3 bg-slate-900 text-white p-3 rounded-full hover:scale-105 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <img src="https://images.unsplash.com/photo-1542038784456-1ea8e935640e?auto=format&fit=crop&q=80&w=800" class="rounded-3xl shadow-lg border-4 border-white transform rotate-3" alt="Photography Studio">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-32 px-6 border-t border-slate-100">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-12">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-8 h-8 border-2 border-slate-900 rounded-full flex items-center justify-center">
                        <div class="w-4 h-4 bg-slate-900"></div>
                    </div>
                    <span class="text-xl font-bold tracking-tighter uppercase italic">Lumina</span>
                </div>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest leading-loose">The standard for visual archival. <br> Crafted for the eyes.</p>
            </div>
            <div class="flex gap-12 text-xs font-black uppercase tracking-[0.2em]">
                <a href="#" class="hover:underline underline-offset-4">Instagram</a>
                <a href="#" class="hover:underline underline-offset-4">Vimeo</a>
                <a href="#" class="hover:underline underline-offset-4">Journal</a>
                <a href="#" class="hover:underline underline-offset-4">Privacy</a>
            </div>
            <div class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.5em]">
                &copy; 2024 Lumina Systems
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script type="module">
        import { GoogleGenAI } from "@google/genai";

        // Scroll Reveal Logic
        const observerOptions = { threshold: 0.1 };
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        // Navbar Scroll Effect
        const nav = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        });

        // AI Advisor Chat Logic
        const chatContainer = document.getElementById('chat-container');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');

        async function addMessage(role, text) {
            const div = document.createElement('div');
            div.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'} w-full`;
            div.innerHTML = `
                <div class="max-w-[85%] p-4 rounded-2xl text-xs font-medium ${role === 'user' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 shadow-sm'} leading-relaxed">
                    ${text}
                </div>
            `;
            chatContainer.appendChild(div);
            chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: 'smooth' });
        }

        chatSend.addEventListener('click', async () => {
            const prompt = chatInput.value.trim();
            if (!prompt) return;

            chatInput.value = '';
            addMessage('user', prompt);

            const loadingId = 'loading-' + Date.now();
            const loadingDiv = document.createElement('div');
            loadingDiv.id = loadingId;
            loadingDiv.className = 'text-[10px] text-slate-300 font-bold uppercase tracking-widest animate-pulse mt-4';
            loadingDiv.innerText = 'Calculating metrics...';
            chatContainer.appendChild(loadingDiv);

            try {
                const ai = new GoogleGenAI({ apiKey: "API_KEY_PLACEHOLDER" }); // process.env.API_KEY is handled
                const response = await ai.models.generateContent({
                    model: 'gemini-3-flash-preview',
                    contents: `User: ${prompt}. You are the Lumina Concierge. Recommend one of our plans: Starter (100GB Free), Pro (5TB, $39/mo), or Studio (Unlimited, $99/mo). Be sophisticated, concise, and professional. 📸`,
                });
                
                document.getElementById(loadingId).remove();
                addMessage('ai', response.text);
            } catch (err) {
                document.getElementById(loadingId).remove();
                addMessage('ai', "I apologize, the studio connection is momentarily busy. Based on industry standards, the Professional plan is usually optimal for growing creators.");
            }
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') chatSend.click();
        });
    </script>
</body>
</html>