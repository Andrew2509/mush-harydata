<!DOCTYPE html>
<html class="dark" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    @include('components.user.seo', ['title' => 'Leaderboard'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&amp;family=Rajdhani:wght@500;600;700&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#F5C754",
                        secondary: "#0EA5E9",
                        "background-dark": "#000000",
                        "card-glass": "rgba(17, 24, 39, 0.4)",
                        "accent-cyan": "#00F0FF",
                        "accent-purple": "#7000FF"
                    },
                    fontFamily: {
                        sans: ["Plus Jakarta Sans", "sans-serif"],
                        tech: ["Rajdhani", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                    animation: {
                        'marquee': 'marquee 35s linear infinite',
                        'pulse-glow': 'pulse-glow 3s ease-in-out infinite',
                        'spin-slow': 'spin 3s linear infinite',
                    },
                    keyframes: {
                        marquee: {
                            '0%': {
                                transform: 'translateX(0%)'
                            },
                            '100%': {
                                transform: 'translateX(-100%)'
                            },
                        },
                        'pulse-glow': {
                            '0%, 100%': {
                                opacity: '0.4',
                                filter: 'brightness(1)'
                            },
                            '50%': {
                                opacity: '0.7',
                                filter: 'brightness(1.3)'
                            },
                        }
                    }
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        :root {
            --bg-deep: #000000;
            --neon-cyan: #00F0FF;
            --neon-glow: rgba(0, 240, 255, 0.5);
            --glass-bg: rgba(0, 0, 0, 0.6);
            --glass-border: rgba(0, 240, 255, 0.2);
            --grid-color: rgba(0, 240, 255, 0.05);
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-deep);
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            color: #e2e8f0;
        }

        .tech-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            background: linear-gradient(180deg, #000000 0%, #0a1020 40%, #000000 100%);
            overflow: hidden;
        }

        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--grid-color) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-color) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at 50% 30%, black 0%, transparent 80%);
            -webkit-mask-image: radial-gradient(circle at 50% 30%, black 0%, transparent 80%);
            animation: pulse-glow 5s infinite ease-in-out;
        }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .glass-nav {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 240, 255, 0.1);
        }

        .leaderboard-card-container {
            position: relative;
        }

        .leaderboard-card {
            background: linear-gradient(145deg, rgba(10, 10, 10, 0.7) 0%, rgba(0, 0, 0, 0.9) 100%);
            border: 1px solid rgba(0, 240, 255, 0.15);
            border-radius: 1rem;
            height: 280px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.05), inset 0 0 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .leaderboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 240, 255, 0.1), inset 0 0 20px rgba(0, 0, 0, 0.5);
            border-color: rgba(0, 240, 255, 0.4);
        }

        .corner-accent {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            z-index: 20;
        }

        .corner-tl {
            top: 0;
            left: 0;
            border-top-color: var(--neon-cyan);
            border-left-color: var(--neon-cyan);
            border-radius: 0.5rem 0 0 0;
        }

        .corner-tr {
            top: 0;
            right: 0;
            border-top-color: var(--neon-cyan);
            border-right-color: var(--neon-cyan);
            border-radius: 0 0.5rem 0 0;
        }

        .corner-bl {
            bottom: 0;
            left: 0;
            border-bottom-color: var(--neon-cyan);
            border-left-color: var(--neon-cyan);
            border-radius: 0 0 0 0.5rem;
        }

        .corner-br {
            bottom: 0;
            right: 0;
            border-bottom-color: var(--neon-cyan);
            border-right-color: var(--neon-cyan);
            border-radius: 0 0 0.5rem 0;
        }

        /* Custom Scrollbar for Rankings */
        .rankings-list {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 240, 255, 0.3) transparent;
        }

        .rankings-list::-webkit-scrollbar {
            width: 6px;
        }

        .rankings-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .rankings-list::-webkit-scrollbar-thumb {
            background-color: rgba(0, 240, 255, 0.3);
            border-radius: 10px;
        }

        .leaderboard-header-pill {
            background: rgba(0, 240, 255, 0.1);
            border: 1px solid rgba(0, 240, 255, 0.3);
            border-bottom: none;
            padding: 0.75rem 2rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            display: inline-block;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--neon-cyan);
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.5);
            position: relative;
            z-index: 2;
            margin-bottom: -1px;
        }

        .glass-icon-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .glass-icon-box:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(0, 240, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 240, 255, 0.2);
        }

        .neon-text-glow {
            text-shadow: 0 0 10px rgba(0, 240, 255, 0.7), 0 0 20px rgba(0, 240, 255, 0.5);
        }

        .hero-collage {
            position: absolute;
            inset: 0;
            opacity: 0.3;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0) 90%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1) 0%, rgba(0, 0, 0, 0) 90%);
            z-index: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
        }

        .hero-collage img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(0.8) contrast(1.2);
            transition: filter 0.5s ease;
        }

        .dropdown-content {
            @apply absolute top-full right-0 mt-2 w-56 bg-slate-900/95 backdrop-blur-xl border border-white/10 rounded-2xl p-2 shadow-2xl opacity-0 invisible translate-y-4 transition-all duration-300;
        }

        .dropdown:hover .dropdown-content {
            @apply opacity-100 visible translate-y-0;
        }

        .shadow-blue-glow {
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.4);
        }

        .shadow-yellow-glow {
            box-shadow: 0 0 20px rgba(245, 199, 84, 0.3);
        }
    </style>
</head>

<body class="bg-black text-slate-100 min-h-screen flex flex-col" 
    x-data="{ isSearchModalOpen: false, isMobileMenuOpen: false }"
    x-on:keydown.escape="isSearchModalOpen = false; isMobileMenuOpen = false">
    <div class="tech-bg">
        <div class="grid-pattern"></div>
    </div>

    @include('components.user.navbar')

    <!-- Header Section -->
    <div class="relative pt-24 pb-16 overflow-hidden">
        <div class="absolute inset-0 z-0 h-[700px] pointer-events-none opacity-60 overflow-hidden">
            <video autoplay loop muted playsinline class="w-full h-full object-cover object-[center_20%]">
                <source src="{{ asset('video/dfbb795f533b96c3cb19d0e7b675c86d.mp4') }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-[#000000]/40 via-[#000000]/80 to-[#000000]"></div>
            <div class="absolute inset-0 bg-[#000000]/30 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 max-w-3xl mx-auto text-center px-4">
            <h1
                class="font-tech text-3xl md:text-5xl font-bold text-accent-cyan mb-2 tracking-tighter uppercase neon-text-glow">
                Leaderboard
            </h1>
            <div
                class="h-1 w-32 mx-auto bg-gradient-to-r from-transparent via-accent-cyan to-transparent mb-6 opacity-70">
            </div>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-light">
                Top 10 highest purchases at <span class="text-white font-semibold">{{ ENV('APP_NAME') }}</span>.
                Real-time data synchronization directly from our secure servers.
            </p>
            <div
                class="mt-8 flex items-center justify-center gap-3 text-slate-400 text-sm font-medium bg-black/30 backdrop-blur-sm py-2 px-4 rounded-full border border-white/5 w-fit mx-auto">
                <span class="material-symbols-outlined text-accent-cyan text-sm">shield</span>
                <span>The Most Trusted &amp; Safest Platform</span>
                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                <span class="text-accent-cyan truncate">Verified System</span>
            </div>
        </div>
    </div>

    <!-- Leaderboard Cards Section -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 relative z-10 w-full flex-grow">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Daily Top 10 -->
            <div class="flex flex-col leaderboard-card-container">
                <div class="text-center">
                    <span class="leaderboard-header-pill">Top 10 - Hari ini</span>
                </div>
                <div class="leaderboard-card glass-panel relative group">
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay">
                    </div>
                    <div class="corner-accent corner-tl opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-tr opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-bl opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-br opacity-50 group-hover:opacity-100"></div>

                    <div class="flex flex-col items-center gap-4 z-10 w-full h-full p-6 pt-10">
                        @forelse($top10Today as $index => $item)
                            @if ($item->username)
                                @if ($loop->first)
                                    <ul
                                        class="w-full space-y-3 text-sm leading-6 text-white rankings-list overflow-y-auto pr-2 absolute inset-0 p-6 pt-10 pb-6">
                                @endif

                                @php
                                    $usernameLength = strlen($item->username);
                                    $sensorLength = $usernameLength <= 5 ? 2 : 4;
                                    $start = floor(($usernameLength - $sensorLength) / 2);
                                    $censoredUsername = substr_replace(
                                        $item->username,
                                        str_repeat('*', $sensorLength),
                                        $start,
                                        $sensorLength,
                                    );
                                @endphp

                                <li
                                    class="flex items-center justify-between gap-x-2 pb-2.5 border-b border-white/5 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 font-tech text-sm">
                                        <span class="text-slate-500 font-bold w-4">{{ $index + 1 }}.</span>
                                        <span
                                            class="text-slate-200 uppercase tracking-widest font-semibold">{{ $censoredUsername }}</span>
                                        <span>
                                            @if ($index == 0)
                                                🏆
                                            @elseif($index == 1)
                                                🥇
                                            @elseif($index == 2)
                                                🥈
                                            @endif
                                        </span>
                                    </div>
                                    <div class="font-bold text-accent-cyan font-mono text-xs">
                                        Rp&nbsp;{{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                </li>

                                @if ($loop->last)
                                    </ul>
                                @endif
                            @endif
                        @empty
                            <div class="flex flex-col items-center justify-center h-full gap-4 text-center mt-6">
                                <div class="relative w-16 h-16 flex items-center justify-center">
                                    <div
                                        class="absolute inset-0 border-2 border-accent-cyan/30 rounded-full border-t-accent-cyan animate-spin-slow">
                                    </div>
                                    <span
                                        class="material-symbols-outlined text-accent-cyan text-3xl">hourglass_empty</span>
                                </div>
                                <div>
                                    <p class="text-slate-400 font-tech text-lg tracking-wide uppercase">Tidak Ada
                                        Transaksi</p>
                                    <p class="text-xs text-slate-600 font-mono">Waiting for data stream...</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Weekly Top 10 -->
            <div class="flex flex-col leaderboard-card-container">
                <div class="text-center">
                    <span class="leaderboard-header-pill">Top 10 - Minggu ini</span>
                </div>
                <div class="leaderboard-card glass-panel relative group">
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay">
                    </div>
                    <div class="corner-accent corner-tl opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-tr opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-bl opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-br opacity-50 group-hover:opacity-100"></div>

                    <div class="flex flex-col items-center gap-4 z-10 w-full h-full p-6 pt-10">
                        @forelse($top10ThisWeek as $index => $item)
                            @if ($item->username)
                                @if ($loop->first)
                                    <ul
                                        class="w-full space-y-3 text-sm leading-6 text-white rankings-list overflow-y-auto pr-2 absolute inset-0 p-6 pt-10 pb-6">
                                @endif

                                @php
                                    $usernameLength = strlen($item->username);
                                    $sensorLength = $usernameLength <= 5 ? 2 : 4;
                                    $start = floor(($usernameLength - $sensorLength) / 2);
                                    $censoredUsername = substr_replace(
                                        $item->username,
                                        str_repeat('*', $sensorLength),
                                        $start,
                                        $sensorLength,
                                    );
                                @endphp

                                <li
                                    class="flex items-center justify-between gap-x-2 pb-2.5 border-b border-white/5 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 font-tech text-sm">
                                        <span class="text-slate-500 font-bold w-4">{{ $index + 1 }}.</span>
                                        <span
                                            class="text-slate-200 uppercase tracking-widest font-semibold">{{ $censoredUsername }}</span>
                                        <span>
                                            @if ($index == 0)
                                                🏆
                                            @elseif($index == 1)
                                                🥇
                                            @elseif($index == 2)
                                                🥈
                                            @endif
                                        </span>
                                    </div>
                                    <div class="font-bold text-accent-cyan font-mono text-xs">
                                        Rp&nbsp;{{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                </li>

                                @if ($loop->last)
                                    </ul>
                                @endif
                            @endif
                        @empty
                            <div class="flex flex-col items-center justify-center h-full gap-4 text-center mt-6">
                                <div class="relative w-16 h-16 flex items-center justify-center">
                                    <div class="absolute inset-0 border-2 border-accent-cyan/30 rounded-full border-t-accent-cyan animate-spin-slow"
                                        style="animation-duration: 4s;"></div>
                                    <span class="material-symbols-outlined text-accent-cyan text-3xl">query_stats</span>
                                </div>
                                <div>
                                    <p class="text-slate-400 font-tech text-lg tracking-wide uppercase">Tidak Ada
                                        Transaksi</p>
                                    <p class="text-xs text-slate-600 font-mono">Syncing weekly records...</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Monthly Top 10 -->
            <div class="flex flex-col leaderboard-card-container">
                <div class="text-center">
                    <span class="leaderboard-header-pill">Top 10 - Bulan ini</span>
                </div>
                <div class="leaderboard-card glass-panel relative group">
                    <div
                        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay">
                    </div>
                    <div class="corner-accent corner-tl opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-tr opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-bl opacity-50 group-hover:opacity-100"></div>
                    <div class="corner-accent corner-br opacity-50 group-hover:opacity-100"></div>

                    <div class="flex flex-col items-center gap-4 z-10 w-full h-full p-6 pt-10">
                        @forelse($top10ThisMonth as $index => $item)
                            @if ($item->username)
                                @if ($loop->first)
                                    <ul
                                        class="w-full space-y-3 text-sm leading-6 text-white rankings-list overflow-y-auto pr-2 absolute inset-0 p-6 pt-10 pb-6">
                                @endif

                                @php
                                    $usernameLength = strlen($item->username);
                                    $sensorLength = $usernameLength <= 5 ? 2 : 4;
                                    $start = floor(($usernameLength - $sensorLength) / 2);
                                    $censoredUsername = substr_replace(
                                        $item->username,
                                        str_repeat('*', $sensorLength),
                                        $start,
                                        $sensorLength,
                                    );
                                @endphp

                                <li
                                    class="flex items-center justify-between gap-x-2 pb-2.5 border-b border-white/5 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 font-tech text-sm">
                                        <span class="text-slate-500 font-bold w-4">{{ $index + 1 }}.</span>
                                        <span
                                            class="text-slate-200 uppercase tracking-widest font-semibold">{{ $censoredUsername }}</span>
                                        <span>
                                            @if ($index == 0)
                                                🏆
                                            @elseif($index == 1)
                                                🥇
                                            @elseif($index == 2)
                                                🥈
                                            @endif
                                        </span>
                                    </div>
                                    <div class="font-bold text-accent-cyan font-mono text-xs">
                                        Rp&nbsp;{{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                </li>

                                @if ($loop->last)
                                    </ul>
                                @endif
                            @endif
                        @empty
                            <div class="flex flex-col items-center justify-center h-full gap-4 text-center mt-6">
                                <div class="relative w-16 h-16 flex items-center justify-center">
                                    <div class="absolute inset-0 border-2 border-accent-cyan/30 rounded-full border-t-accent-cyan animate-spin-slow"
                                        style="animation-duration: 5s;"></div>
                                    <span class="material-symbols-outlined text-accent-cyan text-3xl">database</span>
                                </div>
                                <div>
                                    <p class="text-slate-400 font-tech text-lg tracking-wide uppercase">Tidak Ada
                                        Transaksi</p>
                                    <p class="text-xs text-slate-600 font-mono">Loading monthly archives...</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </main>


    @include('components.user.footer')
</body>

</html>
