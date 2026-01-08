<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LensVault | The Photographer's Digital Darkroom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/framer-motion@10.16.4/dist/framer-motion.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Space+Grotesk:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        :root { --accent: #8b5cf6; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #030303; 
            color: #fafafa; 
            scroll-behavior: smooth;
        }
        h1, h2, h3, .font-heading { font-family: 'Space Grotesk', sans-serif; }

        .glass-panel { 
            background: rgba(255, 255, 255, 0.02); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
        }
        
        .shutter-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            filter: blur(60px);
            z-index: -1;
        }

        .photo-card-stack:hover .card-1 { transform: rotate(-6deg) translateY(-10px); }
        .photo-card-stack:hover .card-2 { transform: rotate(6deg) translateY(-20px); }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 5s ease-in-out infinite; }
    </style>
</head>
<body class="overflow-x-hidden">

    <div class="shutter-glow top-[-10%] left-[-5%]"></div>
    <div class="shutter-glow bottom-[10%] right-[-5%]" style="background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);"></div>

    <nav class="fixed top-0 w-full z-50 px-6 py-4">
        <div class="max-w-6xl mx-auto glass-panel rounded-2xl px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2 font-heading font-bold text-xl tracking-tighter">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <i data-lucide="camera" class="text-black w-5 h-5"></i>
                </div>
                LENSVAULT
            </div>
            <div class="hidden md:flex gap-8 text-sm font-medium text-gray-400">
                <a href="#features" class="hover:text-white transition">Workflow</a>
                <a href="#storage" class="hover:text-white transition">Infrastructure</a>
                <a href="#pricing" class="hover:text-white transition">Plans</a>
            </div>
            <button class="bg-white text-black px-5 py-2 rounded-xl text-sm font-bold hover:scale-105 transition active:scale-95">
                Join Beta
            </button>
        </div>
    </nav>

    <section class="pt-40 pb-20 px-6 max-w-7xl mx-auto relative">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-xs font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    Now supporting 8K RAW Workflows
                </div>
                <h1 class="text-6xl lg:text-8xl font-bold leading-[0.9] mb-8 tracking-tighter">
                    Built for the <br><span class="text-purple-500 italic">Perfect</span> Shot.
                </h1>
                <p class="text-gray-400 text-lg max-w-md mb-10 leading-relaxed">
                    LensVault is more than storage. It's a high-performance engine designed to host, gallery-wrap, and deliver your creative legacy.
                </p>
                <div class="flex items-center gap-6">
                    <button class="h-14 px-8 bg-purple-600 rounded-2xl font-bold shadow-xl shadow-purple-500/20 hover:bg-purple-500 transition-all">Start Storing</button>
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-black" src="https://i.pravatar.cc/100?u=1" alt="">
                        <img class="w-10 h-10 rounded-full border-2 border-black" src="https://i.pravatar.cc/100?u=2" alt="">
                        <img class="w-10 h-10 rounded-full border-2 border-black" src="https://i.pravatar.cc/100?u=3" alt="">
                        <div class="w-10 h-10 rounded-full bg-neutral-800 flex items-center justify-center text-[10px] font-bold border-2 border-black">+2k</div>
                    </div>
                </div>
            </div>

            <div class="relative h-[500px] flex items-center justify-center photo-card-stack">
                <div class="absolute w-64 h-80 bg-cover bg-center rounded-3xl card-1 transition-all duration-500 glass-panel p-2 shadow-2xl" 
                     style="background-image: url('https://images.unsplash.com/photo-1542038784456-1ea8e935640e?q=80&w=1000'); transform: rotate(-3deg);">
                </div>
                <div class="absolute w-64 h-80 bg-cover bg-center rounded-3xl card-2 transition-all duration-500 glass-panel p-2 shadow-2xl" 
                     style="background-image: url('https://images.unsplash.com/photo-1554080353-a576cf803bda?q=80&w=1000'); transform: rotate(3deg);">
                </div>
                <div class="absolute w-72 h-96 bg-cover bg-center rounded-3xl z-10 p-2 shadow-2xl border border-white/20 animate-float" 
                     style="background-image: url('https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=1000');">
                    <div class="absolute bottom-4 left-4 right-4 p-4 glass-panel rounded-2xl">
                        <p class="text-xs font-bold uppercase tracking-widest text-white/70">Last Upload</p>
                        <p class="text-sm font-semibold">"Golden Hour in Alps"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="max-w-7xl mx-auto px-6 py-24">
        <h2 class="text-3xl font-bold mb-12 flex items-center gap-4">
            <i data-lucide="layers" class="text-purple-500"></i> The Photographer's Toolkit
        </h2>
        <div class="grid md:grid-cols-4 gap-6">
            <div class="md:col-span-2 md:row-span-2 glass-panel rounded-[2rem] p-10 flex flex-col justify-between overflow-hidden relative group">
                <div>
                    <i data-lucide="share-2" class="w-12 h-12 text-purple-500 mb-6"></i>
                    <h3 class="text-3xl font-bold mb-4 leading-tight">Instant Client <br>Galleries</h3>
                    <p class="text-gray-400">Turn any folder into a password-protected, white-labeled gallery with one click.</p>
                </div>
                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=500" class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full opacity-20 group-hover:scale-110 transition duration-700" alt="">
            </div>

            <div class="md:col-span-2 glass-panel rounded-[2rem] p-8 flex items-center gap-6">
                <div class="bg-blue-500/20 p-4 rounded-2xl">
                    <i data-lucide="zap" class="text-blue-400"></i>
                </div>
                <div>
                    <h4 class="font-bold text-xl">Zero-Latency Upload</h4>
                    <p class="text-gray-400 text-sm">Our global edge network ensures your 100MB RAW files fly.</p>
                </div>
            </div>

            <div class="glass-panel rounded-[2rem] p-8">
                <i data-lucide="lock" class="text-green-400 mb-4"></i>
                <h4 class="font-bold mb-2">Vault Security</h4>
                <p class="text-gray-400 text-sm">AES-256 encryption for every pixel.</p>
            </div>

            <div class="glass-panel rounded-[2rem] p-8">
                <i data-lucide="hard-drive" class="text-orange-400 mb-4"></i>
                <h4 class="font-bold mb-2">Smart Backup</h4>
                <p class="text-gray-400 text-sm">Redundant copies across 3 continents.</p>
            </div>
        </div>
    </section>

    <section class="py-24 bg-gradient-to-b from-transparent via-purple-500/5 to-transparent">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-5xl font-bold mb-8 tracking-tighter italic">One link to rule them all.</h2>
            <div class="glass-panel p-4 rounded-3xl flex items-center gap-4 max-w-xl mx-auto border-purple-500/30">
                <div class="bg-neutral-800 px-4 py-2 rounded-xl text-xs text-gray-400">lensvault.com/s/summer-wedding-2026</div>
                <button class="ml-auto bg-purple-600 px-6 py-2 rounded-xl font-bold text-sm">Copy Link</button>
            </div>
            <p class="mt-8 text-gray-500">Your clients view your work in a high-end interface, not a folder list.</p>
        </div>
    </section>

    <section id="pricing" class="max-w-7xl mx-auto px-6 py-32">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div class="max-w-xl">
                <h2 class="text-5xl font-bold mb-4">Ready to upgrade <br>your workflow?</h2>
                <p class="text-gray-400">Choose the capacity that fits your shooting frequency.</p>
            </div>
            <div class="flex glass-panel p-1 rounded-2xl">
                <button class="px-6 py-2 bg-white text-black rounded-xl font-bold text-sm">Monthly</button>
                <button class="px-6 py-2 text-gray-400 rounded-xl font-bold text-sm">Yearly (-20%)</button>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="p-8 rounded-[2.5rem] border border-white/5 hover:bg-white/5 transition group">
                <p class="text-gray-400 font-bold mb-4 uppercase tracking-widest text-xs">Starter</p>
                <h3 class="text-4xl font-bold mb-6">$0 <span class="text-sm font-normal text-gray-500">/mo</span></h3>
                <hr class="border-white/10 mb-8">
                <ul class="space-y-4 mb-10">
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4 text-purple-500"></i> 10GB Storage</li>
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4 text-purple-500"></i> 3 Public Galleries</li>
                    <li class="flex items-center gap-3 text-sm text-gray-600"><i data-lucide="x" class="w-4 h-4"></i> Custom Domains</li>
                </ul>
                <button class="w-full py-4 rounded-2xl border border-white/10 font-bold group-hover:bg-white group-hover:text-black transition">Claim Space</button>
            </div>

            <div class="p-8 rounded-[2.5rem] bg-white text-black relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-purple-500 text-white text-[10px] font-bold px-4 py-1 rounded-bl-xl uppercase">Best Value</div>
                <p class="font-bold mb-4 uppercase tracking-widest text-xs opacity-60">Pro Visualist</p>
                <h3 class="text-4xl font-bold mb-6">$24 <span class="text-sm font-normal opacity-60">/mo</span></h3>
                <hr class="border-black/10 mb-8">
                <ul class="space-y-4 mb-10 font-medium">
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4"></i> 2TB High-Speed Storage</li>
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4"></i> Unlimited Galleries</li>
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4"></i> RAW File Previewing</li>
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4"></i> Your Logo / Branding</li>
                </ul>
                <button class="w-full py-4 rounded-2xl bg-black text-white font-bold hover:opacity-90 transition">Go Professional</button>
            </div>

            <div class="p-8 rounded-[2.5rem] border border-white/5 hover:bg-white/5 transition group">
                <p class="text-gray-400 font-bold mb-4 uppercase tracking-widest text-xs">Studio</p>
                <h3 class="text-4xl font-bold mb-6">$59 <span class="text-sm font-normal text-gray-500">/mo</span></h3>
                <hr class="border-white/10 mb-8">
                <ul class="space-y-4 mb-10">
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4 text-purple-500"></i> 10TB Storage</li>
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4 text-purple-500"></i> Team Collaboration</li>
                    <li class="flex items-center gap-3 text-sm"><i data-lucide="check" class="w-4 h-4 text-purple-500"></i> API Access</li>
                </ul>
                <button class="w-full py-4 rounded-2xl border border-white/10 font-bold group-hover:bg-white group-hover:text-black transition">Contact Sales</button>
            </div>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 mb-32">
        <div class="glass-panel rounded-[3rem] p-16 text-center border-purple-500/20 relative overflow-hidden">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-purple-600/20 rounded-full blur-3xl"></div>
            <h2 class="text-5xl font-bold mb-6">Stop managing disks. <br>Start creating art.</h2>
            <p class="text-gray-400 mb-10 max-w-lg mx-auto">Join over 5,000 photographers who trust LensVault with their high-resolution life.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <input type="email" placeholder="enter your email" class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 outline-none focus:border-purple-500 transition sm:w-80">
                <button class="bg-purple-600 px-8 py-4 rounded-2xl font-bold">Get Started</button>
            </div>
        </div>
    </section>

    <footer class="max-w-7xl mx-auto px-6 py-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="flex items-center gap-2 font-heading font-bold text-lg tracking-tighter opacity-50">
            LENSVAULT
        </div>
        <div class="flex gap-8 text-xs font-bold uppercase tracking-widest text-gray-500">
            <a href="#" class="hover:text-white transition">Privacy</a>
            <a href="#" class="hover:text-white transition">Terms</a>
            <a href="#" class="hover:text-white transition">Instagram</a>
            <a href="#" class="hover:text-white transition">Twitter</a>
        </div>
        <p class="text-gray-600 text-xs tracking-widest uppercase">&copy; 2026 LensVault Cloud Inc.</p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>