<!DOCTYPE html>
<html class="dark" lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
@include('components.user.seo', ['title' => 'Kalkulator Magic Wheel'])
<script src="{{ asset('js/tailwind-cdn.js') }}"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#0ea5e9",
                    "background-light": "#f8fafc",
                    "background-dark": "#060B18",
                    accent: "#facc15",
                },
                fontFamily: {
                    display: ["Plus Jakarta Sans", "sans-serif"],
                },
                borderRadius: {
                    DEFAULT: "0.75rem",
                },
                backgroundImage: {
                    'hero-pattern': "radial-gradient(circle at 50% 50%, #0f172a 0%, #020617 100%)",
                }
            },
        },
    };
</script>
<style type="text/tailwindcss">
    :root {
        --bg-deep: #060B18;
        --cyan-glow: rgba(34, 211, 238, 0.6);
    }
    [x-cloak] {
        display: none !important;
    }
    body {
        font-family: "Plus Jakarta Sans", sans-serif;
        background-color: var(--bg-deep) !important;
        @apply overflow-x-hidden;
    }
    .glass {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .animated-circuit-bg {
        position: fixed;
        inset: 0;
        z-index: -1;
        background: radial-gradient(circle at 50% 50%, #0a1628 0%, #060b18 100%);
        overflow: hidden;
    }
    .circuit-path {
        stroke: #22d3ee;
        stroke-width: 1.5;
        fill: none;
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        opacity: 0.15;
        animation: dash 10s linear infinite;
    }
    @keyframes dash {
        to { stroke-dashoffset: 0; }
    }
    .circuit-dot {
        fill: #22d3ee;
        filter: drop-shadow(0 0 3px #22d3ee);
        animation: pulse-dot 4s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 0.2; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.2); }
    }
    .glow-cyan {
        text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
    }
    .glow-text {
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }
    input[type=range] {
        -webkit-appearance: none;
        width: 100%;
        background: transparent;
    }
    input[type=range]::-webkit-slider-runnable-track {
        width: 100%;
        height: 8px;
        cursor: pointer;
        background: linear-gradient(90deg, #0ea5e9 0%, #facc15 100%);
        border-radius: 4px;
    }
    input[type=range]::-webkit-slider-thumb {
        height: 24px;
        width: 24px;
        border-radius: 50%;
        background: #facc15;
        cursor: pointer;
        -webkit-appearance: none;
        margin-top: -8px;
        box-shadow: 0 0 15px rgba(250, 204, 21, 0.6);
        border: 3px solid #020617;
    }
    .glass-nav {
        background: rgba(10, 15, 25, 0.8) !important;
        backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .dropdown-content {
        @apply absolute top-full right-0 mt-2 w-56 bg-slate-950 backdrop-blur-xl border border-white/10 rounded-2xl p-2 shadow-2xl opacity-0 invisible translate-y-4 transition-all duration-300;
        z-index: 150;
    }
    .dropdown:hover .dropdown-content {
        @apply opacity-100 visible translate-y-0;
    }
    .shadow-blue-glow {
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
    }
</style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen selection:bg-primary selection:text-white"
    x-data="{ isSearchModalOpen: false, isMobileMenuOpen: false }"
    x-on:keydown.escape="isSearchModalOpen = false; isMobileMenuOpen = false">

<!-- Animated Circuit Background -->
<div class="animated-circuit-bg">
    <svg height="100%" preserveAspectRatio="xMidYMid slice" viewBox="0 0 1440 900" width="100%" xmlns="http://www.w3.org/2000/svg">
        <g class="circuit-lines">
            <path class="circuit-path" d="M100 100 L300 100 L350 150 L600 150 L650 100 L900 100"></path>
            <circle class="circuit-dot" cx="100" cy="100" r="3"></circle>
            <circle class="circuit-dot" cx="900" cy="100" r="3"></circle>
            <path class="circuit-path" d="M1200 800 L1000 800 L950 750 L700 750 L650 800 L400 800" style="animation-delay: -2s;"></path>
            <circle class="circuit-dot" cx="1200" cy="800" r="3"></circle>
            <circle class="circuit-dot" cx="400" cy="800" r="3"></circle>
            <path class="circuit-path" d="M50 400 L200 400 L250 350 L250 200" style="animation-delay: -5s;"></path>
            <circle class="circuit-dot" cx="50" cy="400" r="3"></circle>
            <path class="circuit-path" d="M1400 300 L1250 300 L1200 350 L1200 500 L1250 550 L1400 550" style="animation-delay: -7s;"></path>
            <circle class="circuit-dot" cx="1400" cy="300" r="3"></circle>
            <defs>
                <pattern height="60" id="grid" patternUnits="userSpaceOnUse" width="60">
                    <path d="M 60 0 L 0 0 0 60" fill="none" stroke="rgba(34, 211, 238, 0.05)" stroke-width="0.5"></path>
                </pattern>
            </defs>
            <rect fill="url(#grid)" height="100%" width="100%"></rect>
        </g>
    </svg>
</div>

@include('components.user.navbar')

<main class="relative pt-24 pb-12 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none"></div>
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>
    
    <div class="max-w-3xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <div class="relative inline-block mb-6">
                <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full"></div>
                <img alt="Logo" class="relative w-32 h-auto mx-auto drop-shadow-[0_0_20px_rgba(0,229,255,0.4)]" src="{{ url('') }}{{ $config->logo_header }}"/>
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3 glow-cyan tracking-tight">
                Kalkulator Magic Wheel
            </h1>
            <p class="text-slate-400 text-base leading-relaxed max-w-md mx-auto">
                Gunakan alat ini untuk mengetahui total maksimal diamond yang dibutuhkan untuk mendapatkan skin Legends impian Anda di Mobile Legends.
            </p>
        </div>

        <div class="glass p-6 md:p-10 rounded-[2rem] shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <span class="material-icons-round text-6xl">memory</span>
            </div>
            
            <div class="space-y-12">
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-bold uppercase tracking-widest text-primary">Geser sesuai Titik Magic Wheel Anda</label>
                        <span class="text-xl font-black text-accent bg-accent/10 px-3 py-1 rounded-lg" id="sliderValueDisplay">100</span>
                    </div>
                    <input class="w-full" id="magicWheelRange" max="199" min="0" type="range" value="100"/>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/5 dark:bg-black/20 p-5 rounded-xl border border-white/5 flex flex-col items-center justify-center text-center">
                        <span class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Poin Bintang Kamu</span>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span id="currentPoints">100</span>
                            <span class="material-icons-round text-accent text-xl">stars</span>
                        </div>
                    </div>

                    <div class="bg-white/5 dark:bg-black/20 p-5 rounded-xl border border-white/5 flex flex-col items-center justify-center text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-12 h-12 bg-primary/10 blur-xl"></div>
                        <span class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Membutuhkan Maksimal</span>
                        <div class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="text-primary" id="requiredDiamonds">5400</span>
                            <span class="text-primary">Diamond</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <a href="{{ url('/') }}" class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-extrabold py-5 rounded-2xl text-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-[0_0_30px_rgba(14,165,233,0.3)] flex items-center justify-center gap-3 group relative overflow-hidden">
                        <div class="absolute inset-0 bg-primary opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        <span>Top Up Diamond Sekarang!</span>
                        <span class="material-icons-round transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-wrap justify-center gap-8 opacity-60">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-primary">bolt</span>
                <span class="text-sm font-semibold uppercase">Instan & Aman</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-primary">support_agent</span>
                <span class="text-sm font-semibold uppercase">Support 24/7</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-primary">verified</span>
                <span class="text-sm font-semibold uppercase">Resmi & Legal</span>
            </div>
        </div>
    </div>
</main>

@include('components.user.footer')

<script>
    const range = document.getElementById('magicWheelRange');
    const displayVal = document.getElementById('sliderValueDisplay');
    const currentPoints = document.getElementById('currentPoints');
    const requiredDiamonds = document.getElementById('requiredDiamonds');

    function updateValues() {
        const val = parseInt(range.value);
        displayVal.textContent = val;
        currentPoints.textContent = val;
        
        // MLBB Magic Wheel Calculation Logic
        const spinRemaining = 200 - val;
        let diamondRequired = 0;
        
        if (spinRemaining < 196) {
            diamondRequired = Math.ceil(spinRemaining / 5) * 270;
        } else {
            diamondRequired = spinRemaining * 60;
        }
        
        requiredDiamonds.textContent = diamondRequired;
    }

    range.addEventListener('input', updateValues);
    document.addEventListener('DOMContentLoaded', updateValues);
</script>

</body>
</html>
