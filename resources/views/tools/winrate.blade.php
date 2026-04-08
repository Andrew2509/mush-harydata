<!DOCTYPE html>
<html class="dark" lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
@include('components.user.seo', ['title' => 'Kalkulator Win Rate'])
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#00E5FF", // Vibrant Cyan
                    secondary: "#0EA5E9",
                    accent: "#0A2540",
                    "background-light": "#F8FAFC",
                    "background-dark": "#020617",
                },
                fontFamily: {
                    display: ["Plus Jakarta Sans", "sans-serif"],
                    body: ["Plus Jakarta Sans", "sans-serif"],
                },
                borderRadius: {
                    DEFAULT: "12px",
                },
                backgroundImage: {
                    'grid-pattern': "url('https://www.transparenttextures.com/patterns/carbon-fibre.png')",
                }
            },
        },
    };
</script>
<style type="text/tailwindcss">
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        overflow-x: hidden;
    }
    [x-cloak] {
        display: none !important;
    }
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(0, 229, 255, 0.1);
    }
    .glow-cyan {
        text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
    }
    .neon-border {
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }
    .circuit-bg {
        background-image: radial-gradient(circle at 2px 2px, rgba(0, 229, 255, 0.05) 1px, transparent 0);
        background-size: 40px 40px;
    }
    @keyframes rain {
        0% { transform: translateY(-100vh); }
        100% { transform: translateY(100vh); }
    }
    .rain-drop {
        position: fixed;
        width: 1px;
        height: 80px;
        background: linear-gradient(to bottom, transparent, rgba(0, 229, 255, 0.2));
        z-index: 0;
        pointer-events: none;
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
<body class="bg-background-light dark:bg-background-dark text-slate-800 dark:text-slate-100 min-h-screen transition-colors duration-300"
    x-data="{ isSearchModalOpen: false, isMobileMenuOpen: false }"
    x-on:keydown.escape="isSearchModalOpen = false; isMobileMenuOpen = false">

@include('components.user.navbar')

<!-- Rain drops overlay -->
<div class="fixed inset-0 overflow-hidden pointer-events-none opacity-20 z-0">
    <div class="rain-drop" style="left: 10%; animation: rain 2s linear infinite;"></div>
    <div class="rain-drop" style="left: 30%; animation: rain 2.5s linear infinite; animation-delay: 0.5s;"></div>
    <div class="rain-drop" style="left: 55%; animation: rain 1.8s linear infinite; animation-delay: 1.2s;"></div>
    <div class="rain-drop" style="left: 80%; animation: rain 2.2s linear infinite; animation-delay: 0.3s;"></div>
    <div class="rain-drop" style="left: 95%; animation: rain 2.7s linear infinite; animation-delay: 0.8s;"></div>
</div>

<main class="relative z-10 pt-20 pb-12 px-4 circuit-bg min-h-screen text-slate-100 dark:text-slate-100 transition-colors duration-300">
    <div class="max-w-xl mx-auto text-center mb-8">
        <div class="relative inline-block mb-6">
            <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full"></div>
            <img alt="Logo" class="relative w-32 h-auto mx-auto drop-shadow-[0_0_20px_rgba(0,229,255,0.4)]" src="{{ url('') }}{{ $config->logo_header }}"/>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3 glow-cyan tracking-tight">Kalkulator Win Rate</h1>
        <p class="text-slate-400 text-base leading-relaxed max-w-md mx-auto">
            Gunakan untuk menghitung total jumlah pertandingan yang harus diambil untuk mencapai target tingkat kemenangan yang diinginkan.
        </p>
    </div>

    <div class="max-w-lg mx-auto">
        <div class="glass-card neon-border p-6 md:p-8 rounded-3xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 blur-3xl rounded-full"></div>
            <div class="space-y-6 relative">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-300">Total Pertandingan Anda Saat Ini</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-icons-round text-slate-400 group-focus-within:text-primary transition-colors">sports_esports</span>
                        <input id="TotalMatch" class="w-full pl-12 pr-4 py-3 bg-slate-900/50 border border-slate-700 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl outline-none transition-all placeholder:text-slate-400 text-white" placeholder="Contoh: 223" type="number"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-300">Total Win Rate Anda Saat Ini (%)</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-icons-round text-slate-400 group-focus-within:text-primary transition-colors">percent</span>
                        <input id="TotalWr" class="w-full pl-12 pr-4 py-3 bg-slate-900/50 border border-slate-700 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl outline-none transition-all placeholder:text-slate-400 text-white" placeholder="Contoh: 54" type="number"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-300">Win Rate Total yang Anda Inginkan (%)</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-icons-round text-slate-400 group-focus-within:text-primary transition-colors">stars</span>
                        <input id="MauWr" class="w-full pl-12 pr-4 py-3 bg-slate-900/50 border border-slate-700 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl outline-none transition-all placeholder:text-slate-400 text-white" placeholder="Contoh: 70" type="number"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    <button id="hasil" type="button" class="bg-primary hover:bg-cyan-400 text-slate-950 font-extrabold py-3.5 px-6 rounded-2xl shadow-[0_0_15px_rgba(0,229,255,0.4)] hover:shadow-[0_0_25px_rgba(0,229,255,0.6)] transition-all flex items-center justify-center gap-2 group">
                        <span class="material-icons-round">calculate</span>
                        Hitung Sekarang
                    </button>
                    <a href="{{ url('/') }}" class="bg-slate-800 text-white font-bold py-4 px-6 rounded-2xl border border-slate-700 hover:border-primary/50 transition-all flex items-center justify-center gap-2 hover:bg-slate-700">
                        <span class="material-icons-round">account_balance_wallet</span>
                        Top Up Diamonds
                    </a>
                </div>
                
                <div id="HasilKalkulasi" class="mt-4"></div>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-3 px-6 py-4 rounded-2xl bg-primary/5 border border-primary/10">
            <span class="material-icons-round text-primary">info</span>
            <p class="text-sm text-slate-400">Pastikan target Win Rate Anda masuk akal dan lebih besar dari Win Rate saat ini.</p>
        </div>
    </div>
</main>

@include('components.user.footer')

<script>
    // Variables
    const TotalMatch = document.querySelector("#TotalMatch");
    const TotalWr = document.querySelector("#TotalWr");
    const MauWr = document.querySelector("#MauWr");
    const hasil = document.querySelector("#hasil");
    const HasilKalkulasi = document.querySelector("#HasilKalkulasi");

    // Functions
    function res() {
        const resultNum = rumus(TotalMatch.value, TotalWr.value, MauWr.value);
        let text = '';
        
        if (isNaN(resultNum) || !isFinite(resultNum)) {
             text = `	
            <div class="mt-6 rounded-2xl border-l-4 border-red-500 bg-red-500/10 p-4 text-center text-sm font-semibold">
                Ups, target win rate tidak valid.
            </div>`;
        } else {
            text = `	
            <div class="mt-6 rounded-2xl border border-primary/30 bg-primary/10 p-4 text-center text-sm font-semibold tracking-wide shadow-[0_0_15px_rgba(0,229,255,0.1)]">
                Anda membutuhkan sekitar <strong class="text-primary text-base glow-cyan">${resultNum} Win Tanpa Lose</strong> untuk mendapatkan Win Rate <strong class="text-primary text-base glow-cyan">${MauWr.value}%</strong>.
            </div>`;
        }
            
        HasilKalkulasi.innerHTML = text;
    }

    function rumus(TotalMatch, TotalWr, MauWr) {
        let tWin = TotalMatch * (TotalWr / 100);
        let tLose = TotalMatch - tWin;
        let sisaWr = 100 - MauWr;
        let wrResult = 100 / sisaWr;
        let seratusPersen = tLose * wrResult;
        let final = seratusPersen - TotalMatch;
        return Math.round(final);
    }

    // Main
    window.addEventListener("load", init);

    function init() {
        eventListener();
    }

    function eventListener() {
        hasil.addEventListener("click", res);
    }
</script>

</body>
</html>

