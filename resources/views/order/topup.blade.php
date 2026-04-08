@extends('layouts.user')

@section('custom_style')
    <script src="{{ asset('js/tailwind-cdn.js') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#06b6d4",
                        secondary: "#0f172a",
                        accent: "#22d3ee",
                        "background-dark": "#050b14",
                        "card-dark": "rgba(30, 41, 59, 0.4)",
                        "card-border": "rgba(6, 182, 212, 0.2)",
                        "review-bg": "#1e293b",
                        "review-header": "#0f172a",
                    },
                    fontFamily: {
                        display: ["Rajdhani", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                    animation: {
                        'pulse-glow': 'pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'rain': 'rain 1s linear infinite',
                    },
                    keyframes: {
                        'pulse-glow': {
                            '0%, 100%': { opacity: '1', boxShadow: '0 0 20px rgba(6, 182, 212, 0.5)' },
                            '50%': { opacity: '.8', boxShadow: '0 0 5px rgba(6, 182, 212, 0.2)' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        'rain': {
                            '0%': { transform: 'translateY(-100vh)' },
                            '100%': { transform: 'translateY(100vh)' }
                        }
                    }
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        :root {
            --primary-glow: rgba(6, 182, 212, 0.6);
            --glass-bg: rgba(15, 23, 42, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body, html {
            background-color: #050b14 !important;
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        #app, main {
            background-color: #050b14 !important;
        }

        #circuit-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            opacity: 0.4;
            pointer-events: none;
        }

        .rain-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAICAYAAADED76LAAAANUlEQVQYV2NkIACwMhAD/uPzJ8wI0wQhA6aJqAJ0TYQVAhviUgQyAqYJIgxADoMJohCAMwMAiYkEA/8y7LwAAAAASUVORK5CYII=') repeat;
            opacity: 0.05;
            pointer-events: none;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
            font-family: 'Rajdhani', sans-serif;
        }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .glass-nav {
            background: rgba(6, 11, 24, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            width: 14rem;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transform: translateY(1rem);
            transition: all 0.3s ease;
        }

        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .shadow-blue-glow {
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .section-header {
            background: linear-gradient(90deg, rgba(6, 182, 212, 0.2) 0%, rgba(15, 23, 42, 0) 100%);
            border-left: 4px solid #06b6d4;
        }

        .nominal-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .nominal-card:hover {
            transform: scale(1.03);
            border-color: #22d3ee;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        }

        .nominal-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: skewX(-25deg);
            transition: 0.5s;
        }

        .nominal-card:hover::after {
            left: 150%;
            transition: 0.7s ease-in-out;
        }

        .nominal-card.selected {
            background: rgba(6, 182, 212, 0.15);
            border: 2px solid #22d3ee;
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.6), inset 0 0 15px rgba(6, 182, 212, 0.2);
            transform: scale(1.02);
        }

        .check-icon {
            opacity: 0;
            transform: scale(0.5) rotate(-45deg);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .nominal-card.selected .check-icon {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #020617;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
            border: 1px solid #334155;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #06b6d4;
        }

        .flag-icon {
            width: 20px;
            height: 14px;
            object-fit: cover;
            border-radius: 2px;
            display: inline-block;
            margin-right: 4px;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
        }

        #order-summary-bar {
            transform: translateY(200%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px dashed rgba(255, 255, 255, 0.2);
        }

        #order-summary-bar.visible {
            transform: translateY(0);
        }

        .ribbon-wrapper {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 100px;
            height: 100px;
            overflow: hidden;
            z-index: 20;
        }
        .ribbon {
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 10px;
            font-weight: 800;
            padding: 5px 0;
            transform: rotate(45deg);
            position: absolute;
            top: 25px;
            right: -25px;
            width: 120px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
            letter-spacing: 1px;
        }

        .payment-category-header {
           background: #1e293b;
           border: 1px solid transparent;
        }
        .payment-category-header:hover {
            background: #334155;
        }
        .payment-logo-footer {
            background: #475569;
            height: 40px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 1rem;
        }

        .method-list.active {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }

        .material-symbols-outlined.fill-1 {
            font-variation-settings: 'FILL' 1;
        }

        .rating-bar-fill {
            width: 0%;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px rgba(234, 179, 8, 0.4);
        }

        .rating-section.is-visible .rating-bar-fill-5 { width: 80%; }
        .rating-section.is-visible .rating-bar-fill-4 { width: 15%; }
        .rating-section.is-visible .rating-bar-fill-3 { width: 3%; }
        .rating-section.is-visible .rating-bar-fill-2 { width: 1%; }
        .rating-section.is-visible .rating-bar-fill-1 { width: 1%; }
        /* Toast Notifications */
        #react-notif {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: #1e293b;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            display: flex;
            items: center;
            gap: 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-out;
        }

        .toast.success { border-color: rgba(34, 197, 94, 0.4); }
        .toast.error { border-color: rgba(239, 68, 68, 0.4); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
@endsection

@section('content')
    <!-- Elements for technical aesthetics -->
    <canvas id="circuit-canvas"></canvas>
    <div class="rain-overlay"></div>

    @include('components.user.navbar')


    @php
        // Desktop Banner Logic
        $desktop_banner = $banner_topup;
        $desktop_path = $desktop_banner ? asset($desktop_banner->path) : asset($kategori->banner);
        $is_desktop_video = false;
        if ($desktop_banner) {
            $extension = pathinfo($desktop_banner->path, PATHINFO_EXTENSION);
            $is_desktop_video = in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);
        }

        // Mobile Banner Logic
        $mobile_banner = $banner_topup_mobile ?? $banner_topup;
        $mobile_path = $mobile_banner ? asset($mobile_banner->path) : asset($kategori->banner);
        $is_mobile_video = false;
        if ($mobile_banner) {
            $extension = pathinfo($mobile_banner->path, PATHINFO_EXTENSION);
            $is_mobile_video = in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);
        }
    @endphp

    <!-- Topup Page Banner (Dual Mode) -->
    <div class="relative w-full overflow-hidden group">
        <!-- Desktop Container -->
        <div class="hidden md:block relative w-full h-[450px] bg-gray-900">
            <div class="absolute inset-0">
                @if($is_desktop_video)
                    <video src="{{ $desktop_path }}" class="w-full h-full object-cover object-top opacity-75 transition-transform duration-[10s] ease-out" autoplay muted loop playsinline></video>
                @else
                    <img alt="{{ $kategori->nama }} Banner"
                        class="w-full h-full object-cover object-top opacity-75 group-hover:scale-105 transition-transform duration-[10s] ease-out"
                        src="{{ $desktop_path }}" 
                        onerror="this.src='https://placehold.co/1920x600/0f172a/06b6d4?text=TOP+UP+{{ $kategori->nama }}'"/>
                @endif
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#050b14]/80 via-transparent to-[#050b14]"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#050b14]/70 via-transparent to-[#050b14]/70"></div>
            
            <div class="absolute inset-0 flex flex-col items-center justify-center pt-2 z-10">
                <h1 class="text-5xl md:text-7xl font-display font-black text-white italic tracking-tighter drop-shadow-[0_0_15px_rgba(255,255,255,0.3)] transform -skew-x-12 animate-float uppercase">
                    TOP UP {{ $kategori->nama }}
                </h1>
                <h2 class="text-3xl md:text-5xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-cyan-400 italic tracking-tight drop-shadow-lg transform -skew-x-12 mt-2 animate-pulse">
                    TER SAT-SET CUMA DI SINI
                </h2>
                <div class="mt-6 flex gap-2">
                    <span class="px-3 py-1 bg-cyan-500/20 border border-cyan-500/50 rounded text-cyan-300 text-xs font-mono uppercase tracking-widest">Secure</span>
                    <span class="px-3 py-1 bg-blue-500/20 border border-blue-500/50 rounded text-blue-300 text-xs font-mono uppercase tracking-widest">Instant</span>
                    <span class="px-3 py-1 bg-purple-500/20 border border-purple-500/50 rounded text-purple-300 text-xs font-mono uppercase tracking-widest">24/7 Support</span>
                </div>
            </div>
        </div>

        <!-- Mobile Container -->
        <div class="block md:hidden relative w-full h-[300px] bg-gray-900">
            <div class="absolute inset-0">
                @if($is_mobile_video)
                    <video src="{{ $mobile_path }}" class="w-full h-full object-cover opacity-80" autoplay muted loop playsinline></video>
                @else
                    <img alt="{{ $kategori->nama }} Mobile Banner"
                        class="w-full h-full object-cover opacity-80"
                        src="{{ $mobile_path }}" 
                        onerror="this.src='https://placehold.co/800x600/0f172a/06b6d4?text=TOP+UP+{{ $kategori->nama }}'"/>
                @endif
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-[#050b14]/60 via-transparent to-[#050b14]"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center pt-10 z-10 px-4 text-center">
                <h1 class="text-3xl font-display font-black text-white italic tracking-tighter drop-shadow-lg transform -skew-x-12 uppercase">
                    TOP UP {{ $kategori->nama }}
                </h1>
                <p class="text-cyan-400 text-xs font-bold tracking-widest mt-2 uppercase">Proses Kilat 24 Jam Otomatis</p>
            </div>
        </div>
    </div>
    <div class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-32 relative z-10 w-full pb-12" x-data="{ activeTab: 'transaksi' }">
        <div class="glass-panel rounded-2xl p-4 md:p-6 mb-8 flex flex-row items-center gap-4 md:gap-8 relative overflow-hidden group">
            <!-- Game Logo -->
            <div class="relative w-24 h-24 md:w-44 md:h-44 rounded-xl md:rounded-2xl overflow-hidden shadow-[0_0_30px_rgba(6,182,212,0.15)] border border-cyan-500/30 flex-shrink-0 group-hover:shadow-[0_0_50px_rgba(6,182,212,0.4)] transition-all duration-500">
                <img alt="{{ $kategori->nama }} Logo"
                    class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700"
                    src="{{ $kategori->thumbnail }}" />
            </div>
            
            <!-- Game Info -->
            <div class="flex-grow text-left z-10">
                <h1 class="text-xl md:text-5xl font-display font-black text-white uppercase tracking-tight leading-none drop-shadow-md">
                    {{ $kategori->nama }}
                </h1>
                <p class="text-cyan-400 text-[10px] md:text-sm font-bold tracking-widest mt-1 mb-3 md:mb-6">
                    {{ $kategori->sub_nama }}
                </p>
                
                <!-- Feature Badges -->
                <div class="flex flex-wrap gap-1.5 md:gap-3">
                    <div class="flex items-center gap-1.5 bg-[#1e293b]/80 px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/5 hover:border-cyan-500/50 transition-colors">
                        <i class="fas fa-bolt text-yellow-400 text-[10px] md:text-xs"></i>
                        <span class="text-[9px] md:text-xs font-bold text-gray-200">Proses Detik</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-[#1e293b]/80 px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/5 hover:border-cyan-500/50 transition-colors">
                        <i class="fas fa-headset text-blue-400 text-[10px] md:text-xs"></i>
                        <span class="text-[9px] md:text-xs font-bold text-gray-200">Layanan 24/7</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-[#1e293b]/80 px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/5 hover:border-cyan-500/50 transition-colors">
                        <i class="fas fa-shield-alt text-green-400 text-[10px] md:text-xs"></i>
                        <span class="text-[9px] md:text-xs font-bold text-gray-200">Garansi Resmi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Menu (Desktop) / Tabs (Mobile) -->
            <div class="lg:col-span-3 space-y-4">
                <div class="glass-panel rounded-2xl p-4 sticky top-24 border border-white/5 shadow-2xl">
                    <div class="flex flex-row lg:flex-col gap-2">
                        <!-- Transaksi Button -->
                        <button 
                            @click="activeTab = 'transaksi'"
                            :class="activeTab === 'transaksi' ? 'bg-gradient-to-r from-cyan-600 to-blue-700 text-white shadow-[0_0_20px_rgba(6,182,212,0.4)]' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white'"
                            class="flex-1 lg:w-full flex items-center gap-3 lg:gap-4 px-4 lg:px-5 py-3 lg:py-4 rounded-xl transition-all duration-300 group">
                            <div :class="activeTab === 'transaksi' ? 'bg-white/20' : 'bg-gray-800'" class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                                <i class="fas fa-shopping-cart text-base lg:text-lg"></i>
                            </div>
                            <div class="text-left">
                                <p class="hidden lg:block text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Order Now</p>
                                <p class="text-xs lg:text-sm font-bold truncate">Transaksi</p>
                            </div>
                            <i class="hidden lg:block fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-40 transition-opacity" :style="activeTab === 'transaksi' ? 'opacity: 1' : ''"></i>
                        </button>

                        <!-- Keterangan Button -->
                        <button 
                            @click="activeTab = 'keterangan'"
                            :class="activeTab === 'keterangan' ? 'bg-gradient-to-r from-purple-600 to-indigo-700 text-white shadow-[0_0_20px_rgba(168,85,247,0.4)]' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white'"
                            class="flex-1 lg:w-full flex items-center gap-3 lg:gap-4 px-4 lg:px-5 py-3 lg:py-4 rounded-xl transition-all duration-300 group">
                            <div :class="activeTab === 'keterangan' ? 'bg-white/20' : 'bg-gray-800'" class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg flex items-center justify-center transition-colors flex-shrink-0">
                                <i class="fas fa-info-circle text-base lg:text-lg"></i>
                            </div>
                            <div class="text-left">
                                <p class="hidden lg:block text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Information</p>
                                <p class="text-xs lg:text-sm font-bold truncate">Info Menu</p>
                            </div>
                            <i class="hidden lg:block fas fa-chevron-right ml-auto text-xs opacity-0 group-hover:opacity-40 transition-opacity" :style="activeTab === 'keterangan' ? 'opacity: 1' : ''"></i>
                        </button>
                    </div>

                    <div class="hidden lg:flex mt-8 pt-6 border-t border-white/5 flex-col gap-4">
                        <div class="flex items-center gap-3 px-2">
                             <div class="p-2 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                <i class="fas fa-shield-alt text-yellow-500 text-xs"></i>
                             </div>
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Garansi Layanan</p>
                        </div>
                        <div class="flex items-center gap-3 px-2">
                             <div class="p-2 rounded-lg bg-green-500/10 border border-green-500/20">
                                <i class="fas fa-check-circle text-green-500 text-xs"></i>
                             </div>
                             <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Otomatis 24 Jam</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-9">
                <!-- Tab: Transaksi (Buying Forms) -->
                <div x-show="activeTab === 'transaksi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                    <input type="hidden" id="nominal">
                    <input type="hidden" id="metode">
                    <input type="hidden" id="ktg_tipe" value="{{ $kategori->tipe }}">

                    <!-- Step 1: MASUKKAN DATA AKUN -->
                    <div class="glass-panel rounded-2xl overflow-hidden relative">
                        <div class="section-header py-4 px-6 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(6,182,212,0.5)]">1</div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Masukkan Data Akun</h3>
                        </div>
                        <div class="p-6 md:p-8">
                            @php
                            if ($kategori->field_2 !== null) {
                                $field2Values = explode(',', $kategori->field_2);
                                $selectValue = isset($field2Values[2]) ? trim($field2Values[2]) : null;
                            }
                            $fieldSelectTitle = explode(',', $kategori->field_select_title);
                            $fieldSelect = explode(',', $kategori->field_select);
                            $field1Values = explode(',', $kategori->field_1);
                            @endphp
                            <div class="grid grid-cols-1 {{ $kategori->field_2 !== null ? 'md:grid-cols-3' : '' }} gap-6">
                                <div class="{{ $kategori->field_2 !== null ? 'md:col-span-2' : '' }}">
                                    <label class="block text-xs font-bold text-cyan-400 uppercase tracking-wider mb-2">{{ $field1Values[0] ?? 'Player ID' }}</label>
                                    <div class="relative group">
                                        <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                        <input class="relative w-full bg-[#0f172a] border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-0 focus:border-cyan-500 placeholder-gray-600 font-mono transition-all"
                                            placeholder="{{ $field1Values[1] ?? 'Contoh: 12345678' }}" type="{{ $field1Values[2] ?? 'text' }}" id="user_id" />
                                    </div>
                                </div>
                                @if ($kategori->field_2 !== null)
                                <div>
                                    <label class="block text-xs font-bold text-cyan-400 uppercase tracking-wider mb-2">{{ $field2Values[0] ?? 'Server' }}</label>
                                    <div class="relative group">
                                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                                        @if ($selectValue == 'select')
                                            <select id="zone_id" class="relative w-full bg-[#0f172a] border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-0 focus:border-cyan-500 placeholder-gray-600 font-mono transition-all appearance-none">
                                                <option value="" class="bg-slate-900">{{ $field2Values[1] ?? 'Pilih Server' }}</option>
                                                @foreach ($fieldSelectTitle as $key => $fst)
                                                    <option value="{{ $fieldSelect[$key] ?? '' }}" class="bg-slate-900">{{ $fst }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input class="relative w-full bg-[#0f172a] border border-gray-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-0 focus:border-cyan-500 placeholder-gray-600 font-mono transition-all"
                                                placeholder="{{ $field2Values[1] ?? '(1234)' }}" type="{{ $field2Values[2] ?? 'text' }}" id="zone_id" />
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-[11px] text-gray-500 font-mono">
                                <i class="fas fa-question-circle"></i>
                                <span>{!! $kategori->deskripsi_field !!}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: PILIH NOMINAL -->
                    <div class="glass-panel rounded-2xl overflow-hidden">
                        <div class="section-header py-4 px-6 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(6,182,212,0.5)]">2</div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Pilih Nominal</h3>
                        </div>
                        <div class="p-6 md:p-8" x-data="{ selectedPaket: 'all' }">
                            <div class="flex flex-wrap gap-2 mb-8 p-1 bg-black/40 rounded-xl w-fit border border-white/5">
                                <button @click="selectedPaket = 'all'" :class="selectedPaket === 'all' ? 'bg-cyan-600 text-white shadow-cyan-500/30' : 'bg-transparent text-gray-400 hover:text-white hover:bg-white/5'" class="px-5 py-2 rounded-lg text-sm font-bold transition-all hover:scale-105">
                                    <i class="fas fa-globe mr-1"></i> Semua
                                </button>
                                @foreach($pakets as $paket)
                                    @php
                                        $btnIcon = '';
                                        if(str_contains(strtolower($paket['nama']), 'special')) $btnIcon = '<i class="fas fa-fire text-orange-500 mr-1"></i>';
                                        elseif(str_contains(strtolower($paket['nama']), 'brazil')) $btnIcon = '<span class="text-green-500 mr-1">🇧🇷</span>';
                                        elseif(str_contains(strtolower($paket['nama']), 'philippines') || str_contains(strtolower($paket['nama']), 'filipina')) $btnIcon = '<span class="text-yellow-500 mr-1">🇵🇭</span>';
                                    @endphp
                                    <button @click="selectedPaket = '{{ $loop->index }}'" :class="selectedPaket === '{{ $loop->index }}' ? 'bg-cyan-600 text-white shadow-cyan-500/30' : 'bg-transparent text-gray-400 hover:text-white hover:bg-white/5'" class="px-5 py-2 rounded-lg text-sm font-bold transition-all hover:scale-105">
                                        {!! $btnIcon !!} {{ $paket['nama'] }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="space-y-8">
                                @foreach($pakets as $paket)
                                <div x-show="selectedPaket === 'all' || selectedPaket === '{{ $loop->index }}'" x-transition>
                                    @php
                                        $icon = 'fas fa-globe-asia'; $color = 'blue';
                                        if(str_contains(strtolower($paket['nama']), 'special') || str_contains(strtolower($paket['nama']), 'item')) { $icon = 'fas fa-fire'; $color = 'orange'; }
                                        elseif(str_contains(strtolower($paket['nama']), 'brazil')) { $icon = 'flag-icon'; $color = 'green'; }
                                    @endphp
                                    <h4 class="text-sm font-bold text-white flex items-center gap-2 mb-4 uppercase tracking-wider pl-1 border-l-2 border-{{ $color }}-500">
                                        @if($icon == 'flag-icon') <span class="text-lg">🇧🇷</span> @else <i class="{{ $icon }} text-{{ $color }}-500 {{ $icon == 'fas fa-fire' ? 'animate-pulse' : '' }}"></i> @endif
                                        {{ $paket['nama'] }}
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($paket['layanan'] as $l)
                                        <div class="nominal-card rounded-xl p-4 cursor-pointer group product-list" data-price="{{ number_format($l['harga'], 0, ',', '.') }}" data-id="{{ $l['id'] }}">
                                            <div class="absolute top-2 right-2 check-icon z-20">
                                                <div class="bg-cyan-400 text-black rounded-full w-6 h-6 flex items-center justify-center shadow-[0_0_10px_rgba(34,211,238,0.8)]"><i class="fas fa-check text-xs font-bold"></i></div>
                                            </div>
                                            <div class="flex flex-col h-full justify-between relative z-10">
                                                <div>
                                                    @if($l['product_logo'])
                                                    <div class="text-[11px] font-bold text-gray-400 mb-1 flex items-center gap-1"><img alt="logo" class="flag-icon" src="{{ $l['product_logo'] }}" /></div>
                                                    @endif
                                                    <div class="text-sm font-bold text-white leading-tight group-hover:text-cyan-300 transition-colors uppercase tracking-tight">{{ $l['layanan'] }}</div>
                                                </div>
                                                <div class="mt-4 flex items-end justify-between">
                                                    <div class="text-base text-cyan-400 font-display font-bold">Rp {{ number_format($l['harga'], 0, ',', '.') }}</div>
                                                    <img alt="diamond" class="w-8 h-8 opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpft-fJt1wqi4E8FgtF5YzeyMCtVJRkuWr9QSvBi3gqcc2F0jD7c8valfOsBnX_vBnvaTPAfFo6oEr-NSJ6-nUTcX9Vzpy6GK5JFiFVUR-DCQF3QWQKSqhSg7z_M_xW5IXDEgb_wgCENUKUZHO6AV8zG-LhzbcdbeSjMOIG-ukMVJwTAVYaHDzndeGO8q_chdqzQf91FRguSjWbEvKFbDorTWP1hHdluUVs9sbFb5HJ1evcCy-OYKLjTBmmRdZYVhbP-8Q2gQulyoR" />
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: PILIH METODE PEMBAYARAN -->
                    <div class="glass-panel rounded-2xl overflow-hidden">
                        <div class="section-header py-4 px-6 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(6,182,212,0.5)]">3</div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Pilih Metode Pembayaran</h3>
                        </div>
                        <div class="p-6 md:p-8 space-y-4" x-data="{ openGroup: 'virtual-account' }">
                            @php
                                $hot_methods = $pay_method->where('keterangan', 'HOT');
                                $qris_methods = $pay_method->where('tipe', 'qris');
                                $other_methods = $pay_method->where('keterangan', '!=', 'HOT')->where('tipe', '!=', 'qris')->groupBy('tipe');
                            @endphp
                            @foreach($hot_methods as $p)
                                <div class="method-list group bg-[#0f172a] rounded-xl p-3 relative cursor-pointer border-2 border-gray-700 hover:border-cyan-500 hover:shadow-[0_0_15px_rgba(6,182,212,0.3)] transition-all flex items-center justify-between overflow-hidden" method-id="{{ $p->code }}">
                                    <div class="absolute -top-3 -right-3 bg-red-600 text-white text-[9px] font-bold px-3 py-1 transform rotate-12 shadow-lg z-10 border border-white rounded-sm">HOT</div>
                                    <div class="flex flex-col gap-0.5"><div class="font-bold text-xs text-white">{{ $p->name }}</div><div class="text-[10px] text-gray-500">Proses Otomatis</div><div class="text-xs font-bold text-cyan-400 mt-0.5 hargapembayaran" id="{{ $p->code }}">Rp 0</div></div>
                                    <div class="w-12 h-8 bg-white/10 rounded-lg p-1 flex items-center justify-center flex-shrink-0"><img alt="{{ $p->name }}" class="h-5 object-contain" src="{{ $p->images }}" /></div>
                                </div>
                            @endforeach
                            @if($qris_methods->count() > 0)
                                @foreach($qris_methods as $p)
                                <div class="method-list relative bg-slate-200 rounded-xl p-4 cursor-pointer border-2 border-transparent hover:shadow-lg transition-all" method-id="{{ $p->code }}">
                                    <div class="absolute top-0 right-0 bg-green-600 text-white text-[9px] font-black px-3 py-1 rounded-bl-xl rounded-tr-xl tracking-tighter shadow-sm z-10">PROSES OTOMATIS</div>
                                    <div class="flex items-center gap-3"><img alt="{{ $p->name }}" class="h-6 object-contain mix-blend-multiply" src="{{ $p->images }}" /><div class="text-gray-900 font-bold text-base truncate">{{ $p->name }}</div></div>
                                    <div class="mt-1 text-gray-500 font-medium text-sm hargapembayaran" id="{{ $p->code }}">Rp 0</div>
                                </div>
                                @endforeach
                            @endif
                            @foreach($other_methods as $tipe => $methods)
                            <div class="bg-[#1e293b] rounded-xl overflow-hidden shadow-sm">
                                <button @click="openGroup = (openGroup === '{{ $tipe }}' ? null : '{{ $tipe }}')" class="w-full p-4 flex items-center justify-between cursor-pointer hover:bg-[#334155] transition-colors"><h3 class="text-white font-bold text-lg uppercase tracking-wider">{{ str_replace('-', ' ', $tipe) }}</h3><i class="fas fa-chevron-down text-white transition-transform duration-300" :class="{ 'rotate-180': openGroup === '{{ $tipe }}' }"></i></button>
                                <div x-show="openGroup === '{{ $tipe }}'" x-transition>
                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($methods as $p)
                                        <div class="method-list bg-slate-100 rounded-lg p-3 relative h-22 cursor-pointer border-2 border-transparent hover:shadow-lg hover:bg-white transition-all" method-id="{{ $p->code }}">
                                            <div class="text-gray-800 font-bold text-[11px] mb-0.5 leading-tight truncate-2-lines h-8">{{ $p->name }}</div>
                                            <div class="text-[10px] font-bold text-gray-500 border-t border-gray-300 pt-1 mt-1">Dicek Otomatis</div>
                                            <div class="text-xs font-bold text-blue-600 mt-1 hargapembayaran" id="{{ $p->code }}">Rp 0</div>
                                            <div class="absolute bottom-2 right-2 flex items-center gap-1 opacity-60"><img alt="{{ $p->name }}" class="h-4 object-contain" src="{{ $p->images }}" /></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 4 & 5: PROMO & WHATSAPP -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="glass-panel rounded-2xl overflow-hidden">
                            <div class="section-header py-4 px-6 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-cyan-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(6,182,212,0.5)]">4</div>
                                <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Kode Promo</h3>
                            </div>
                            <div class="p-6"><div class="flex gap-2"><input id="voucher" class="flex-grow bg-[#0f172a] border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm placeholder-gray-600 transition-all font-mono" placeholder="Punya kode?" type="text" /><button class="bg-white text-black px-4 py-2.5 rounded-lg text-sm font-black hover:bg-gray-200 transition-colors shadow-lg uppercase">Gunakan</button></div></div>
                        </div>
                        <div class="glass-panel rounded-2xl overflow-hidden">
                            <div class="section-header py-4 px-6 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-cyan-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(6,182,212,0.5)]">5</div>
                                <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Whatsapp</h3>
                            </div>
                            <div class="p-6"><input id="nomor" class="w-full bg-[#0f172a] border border-gray-700 text-white rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-cyan-500 focus:border-cyan-500 text-sm placeholder-gray-600 mb-2 transition-all font-mono" placeholder="08xxxxxxxxxx" type="tel" /><p class="text-[10px] text-gray-500 font-bold italic">*Bukti transaksi akan dikirim ke nomor ini.</p></div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Keterangan content (Information) -->
                <div x-show="activeTab === 'keterangan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                    <!-- Cara Topup Card -->
                    <div class="glass-panel rounded-2xl overflow-hidden relative border-l-4 border-l-cyan-500">
                        <div class="section-header py-4 px-6 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-cyan-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(6,182,212,0.5)]">
                                <i class="fas fa-terminal text-sm"></i>
                            </div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Cara Topup</h3>
                        </div>
                        <div class="p-6 md:p-8">
                            <div class="mb-6 p-4 rounded-xl bg-cyan-500/10 border border-cyan-500/20">
                                <p class="text-sm font-bold text-cyan-300 leading-snug">
                                    <i class="fas fa-user-plus mr-1"></i> Segera daftarkan dirimu jadi member kami untuk dapatkan harga yang lebih murah
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4 relative">
                                    <div class="absolute left-[11px] top-2 bottom-2 w-[2px] bg-gray-800"></div>
                                    @foreach(['Masukkan Player ID & Server','Pilih Nominal Topup','Pilih Metode Pembayaran','Tulis Kode Promo (Jika ada)','Masukkan No. Whatsapp','Klik Pesan Sekarang & Pilih Bayar','Selesai'] as $index => $step)
                                        <div class="flex gap-4 relative z-10 group/step">
                                            <div class="{{ $index == 6 ? 'bg-cyan-500 text-white shadow-[0_0_10px_rgba(6,182,212,0.5)]' : 'bg-slate-900 border-cyan-500/30 text-cyan-400' }} w-6 h-6 rounded-full border flex items-center justify-center text-[10px] font-black flex-shrink-0 transition-all group-hover/step:scale-110">
                                                {{ $index + 1 }}
                                            </div>
                                            <p class="{{ $index == 6 ? 'text-white font-black italic' : 'text-gray-400' }} text-sm leading-tight pt-0.5">{{ $step }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="bg-black/20 rounded-2xl p-6 border border-white/5 self-start">
                                    <h4 class="text-xs font-black text-cyan-400 uppercase tracking-widest mb-4">Pesan Penting</h4>
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-receipt text-yellow-500 mt-1"></i>
                                        <p class="text-[12px] text-gray-400 leading-relaxed">
                                            Simpan invoice transaksi Anda untuk mempermudah pengecekan di menu <strong class="text-yellow-500">CEK TRANSAKSI</strong>. Proses otomatis 24 jam nonstop tanpa libur.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Card -->
                    <div class="glass-panel rounded-2xl overflow-hidden border-l-4 border-l-yellow-500 shadow-2xl rating-section" id="reviews-card">
                        <div class="section-header py-4 px-6 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-yellow-500 text-white flex items-center justify-center font-bold text-xl shadow-[0_0_15px_rgba(234,179,8,0.5)]">
                                <i class="fas fa-star text-sm"></i>
                            </div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Ulasan Pembeli</h3>
                        </div>
                        @php
                            $all_ratings = DB::table('ratings')->where('kategori_id', $kategori->id)->get();
                            $totalStars = $all_ratings->sum('bintang');
                            $totalReviews = $all_ratings->count();
                            $positiveReviews = $all_ratings->where('bintang', '>=', 4)->count();
                            $averageRating = $totalReviews > 0 ? $totalStars / $totalReviews : 0;
                            $satisfactionPercentage = $totalReviews > 0 ? ($positiveReviews / $totalReviews) * 100 : 0;
                            $ratingCounts = [5 => $all_ratings->where('bintang', 5)->count(), 4 => $all_ratings->where('bintang', 4)->count(), 3 => $all_ratings->where('bintang', 3)->count(), 2 => $all_ratings->where('bintang', 2)->count(), 1 => $all_ratings->where('bintang', 1)->count()];
                        @endphp
                        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                            <div class="md:col-span-5 flex flex-col items-center">
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="material-symbols-outlined text-6xl text-yellow-400 fill-1 drop-shadow-[0_0_20px_rgba(234,179,8,0.4)]">star</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-6xl font-black text-white score-count" data-target="{{ number_format($averageRating, 1) }}">0.0</span>
                                        <span class="text-xl text-gray-400 font-bold">/ 5.0</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-white mb-1"><span class="percent-count" data-target="{{ number_format($satisfactionPercentage, 0) }}">0</span>% merasa puas</p>
                                    <p class="text-xs text-gray-500 font-mono tracking-widest uppercase">Dari {{ $totalReviews }} Ulasan</p>
                                </div>
                            </div>
                            <div class="md:col-span-7 space-y-3">
                                @foreach([5,4,3,2,1] as $star)
                                @php
                                    $count = $ratingCounts[$star];
                                    $percent = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-3 group/star">
                                    <span class="text-xs font-black text-white w-4">{{ $star }}</span>
                                    <span class="material-symbols-outlined text-yellow-500 text-sm fill-1">star</span>
                                    <div class="flex-grow h-2 bg-gray-800 rounded-full overflow-hidden border border-white/5">
                                        <div class="h-full bg-gradient-to-r from-yellow-500 to-orange-500" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-mono text-gray-500 w-8 text-right">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Order Bar Container (Hidden initially) -->
    <div id="order-summary-floating" class="fixed bottom-0 left-0 right-0 w-full md:bottom-6 md:left-1/2 md:-translate-x-1/2 md:w-[95%] max-w-5xl z-[60] transition-all duration-500" style="opacity: 0; transform: translateY(100%); pointer-events: none;">
        <div class="bg-[#0f172a]/95 backdrop-blur-xl md:rounded-2xl shadow-[0_-10px_40px_rgba(0,0,0,0.5)] md:shadow-[0_20px_50px_rgba(0,0,0,0.7)] border-t md:border border-white/10 pointer-events-auto overflow-hidden">
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 md:gap-4 p-3 md:p-4">
                <!-- Product Info & Mobile Price Section -->
                <div class="flex items-center gap-3 md:gap-4 flex-1">
                    <div class="flex-shrink-0">
                        <img alt="{{ $kategori->nama }} Icon"
                            class="w-12 h-12 md:w-16 md:h-16 rounded-xl object-cover shadow-2xl border border-white/10"
                            src="{{ $kategori->thumbnail }}" />
                    </div>
                    <div class="flex flex-col min-w-0">
                        <h4 class="text-white font-display font-bold text-xs md:text-base tracking-wide uppercase leading-tight truncate">
                            {{ $kategori->nama }}</h4>
                        <!-- Price display (replaces old layout) -->
                        <div class="flex flex-wrap items-baseline gap-1 md:gap-2">
                            <span class="text-yellow-400 font-display font-black text-sm md:text-xl leading-none" id="summary-price">Rp 0</span>
                            <span class="text-gray-500 font-bold hidden md:inline">-</span>
                            <span class="text-[9px] md:text-[10px] font-bold italic text-gray-400 leading-none">**Instan</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Button -->
                <div class="flex-shrink-0">
                    <button id="order-check-floating"
                        class="w-full md:px-12 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-display font-black text-xs md:text-lg py-3 md:py-4 rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.3)] transform transition-all active:scale-[0.98] flex items-center justify-center gap-2 group">
                        <i class="fas fa-shopping-cart text-[10px] md:text-base group-hover:animate-pulse"></i>
                        <span>Pesan Sekarang!</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

    @include('components.user.footer')
    @push('custom_script')
    <script>
        // Card Selection Logic
        const cards = document.querySelectorAll('.nominal-card');
        const floatingBar = document.getElementById('order-summary-floating');
        const summaryPrice = document.getElementById('summary-price');

        cards.forEach(card => {
            card.addEventListener('click', () => {
                cards.forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                
                const price = card.getAttribute('data-price');
                const id = card.getAttribute('data-id');
                
                if (price) {
                    summaryPrice.textContent = price;
                }
                
                if (id) {
                    $("#nominal").val(id);
                    // Price update AJAX
                    $.ajax({
                        url: "{{ route('ajax.price') }}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nominal: id
                        },
                        success: function(res) {
                            if (res.harga !== undefined && !isNaN(res.harga)) {
                                updatePaymentPrices(res.harga);
                                
                                // Sync floating bar price if a method is already selected
                                if ($("#metode").val()) {
                                    let activeMethod = $(".method-list.active .hargapembayaran");
                                    if (activeMethod.length) {
                                        let finalPrice = activeMethod.text().replace("Rp", "").trim();
                                        $("#summary-price").text(finalPrice);
                                    }
                                }
                            }
                        }
                    });
                }

                // Show floating bar with animation
                floatingBar.style.opacity = '1';
                floatingBar.style.transform = 'translateY(0)';
                if (window.innerWidth >= 768) {
                    floatingBar.style.transform = 'translateX(-50%) translateY(0)';
                }
                floatingBar.style.pointerEvents = 'auto';

                card.style.transform = "scale(0.98)";
                setTimeout(() => {
                    card.style.transform = "";
                }, 100);
            });
        });

        function updatePaymentPrices(a) {
            $(".hargapembayaran").each(function() {
                let code = $(this).attr("id");
                let total = parseFloat(a);
                
                // Detailed fee calculation logic synced with order.blade.php
                if (code === "SALDO") {
                    total = total;
                } else if (code === "QRIS_CUSTOM") {
                    total = total + 250 + (0.007 * total);
                } else if (["DANA_CUSTOM", "OVO_CUSTOM", "SHOPEE_CUSTOM", "LINKAJA_CUSTOM"].includes(code)) {
                    total = total + (0.025 * total);
                } else if (code === "QRIS" || code === "QRIS2") {
                    total = total + 100 + (0.007 * total);
                } else if (code === "QRISREALTIME") {
                    total = total + (0.017 * total);
                } else if (code === "BCAVA") {
                    total = total + 4200;
                } else if (["ALFAMART", "ALFAMIDI", "INDOMARET"].includes(code)) {
                    total = total + 3000;
                } else if (["OVOPUSH", "DANA", "SHOPEEPAY"].includes(code)) {
                    total = total + (2.5 * total / 100);
                } else if (["GOPAY", "LINKAJA"].includes(code)) {
                    total = total + (3 * total / 100);
                } else if (["BNIVA", "MYBVA", "PERMATAVA", "BRIVA", "MANDIRIVA", "SMSVA", "MUAMALATVA", "CIMBVA", "SAMPOERNAVA", "BSIVA"].includes(code)) {
                    total = total + 3500;
                } else if (code === "TELKOMSEL") {
                    total = total + (32 * total / 100);
                } else if (["AXIS", "XL", "TRI", "SMARTFREN"].includes(code)) {
                    total = total + (25 * total / 100);
                }
                
                let formattedPrice = formatToRupiah(Math.ceil(total));
                $(this).text(formattedPrice);

                // Update floating bar if this is the active method
                if ($(this).closest(".method-list").hasClass("active")) {
                    $("#summary-price").text(formattedPrice.replace(/Rp\s*/g, "").trim());
                }
            });
        }

        function formatToRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function showToast(message, type = "error") {
            const container = document.getElementById('react-notif');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'}"></i>
                <span class="text-sm font-medium transition-all">${message}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        $(".method-list").click(function(){
            let nominal = $("#nominal").val();
            if (!nominal) {
                showToast("Isi nominal terlebih dahulu");
                return;
            }
            let id = $(this).attr("method-id");
            $(".method-list").removeClass("active border-cyan-500 bg-cyan-500/10 shadow-[0_0_15px_rgba(6,182,212,0.3)]");
            $(".method-list").addClass("border-transparent"); // Reset borders
            
            $(this).removeClass("border-transparent");
            $(this).addClass("active border-cyan-500 bg-cyan-500/10 shadow-[0_0_15px_rgba(6,182,212,0.3)]");
            $("#metode").val(id);

            // Update floating bar price
            let paymentPrice = $(this).find(".hargapembayaran").text();
            if (paymentPrice) {
                $("#summary-price").text(paymentPrice.replace(/Rp\s*/g, "").trim());
            }
        });

        $("#order-check, #order-check-floating").on("click", function () {
            var userId = $("#user_id").val();
            var zoneId = $("#zone_id").val();
            var service = $("#nominal").val();
            var method = $("#metode").val();
            var wa = $("#nomor").val();
            var voucher = $("#voucher").val();
            var ktg_tipe = $("#ktg_tipe").val();

            if (!userId) {
                showToast("Mohon isi Player ID");
                return;
            }
            if (!service) {
                showToast("Mohon pilih nominal terlebih dahulu");
                return;
            }
            if (!method) {
                showToast("Mohon pilih metode pembayaran");
                return;
            }
            if (!wa) {
                showToast("Mohon isi nomor WhatsApp");
                return;
            }

            $.ajax({
                url: "{{ route('ajax.confirmation') }}",
                dataType: "JSON",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    uid: userId,
                    zone: zoneId,
                    service: service,
                    payment_method: method,
                    nomor: wa,
                    voucher: voucher,
                    ktg_tipe: ktg_tipe
                },
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            html: res.data,
                            showConfirmButton: true,
                            showCancelButton: true,
                            background: 'transparent',
                            padding: 0,
                            customClass: {
                                popup: "bg-transparent shadow-none w-auto max-w-none overflow-visible",
                                htmlContainer: "p-0 m-0 overflow-visible",
                                actions: "hidden"
                            }
                        }).then(result => {
                            if (result.isConfirmed) {
                                var nick = $("#nick").text();
                                $.ajax({
                                    url: "{{ route('ordered') }}",
                                    dataType: "JSON",
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        nickname: nick,
                                        uid: userId,
                                        zone: zoneId,
                                        service: service,
                                        payment_method: method,
                                        nomor: wa,
                                        voucher: voucher,
                                        ktg_tipe: ktg_tipe
                                    },
                                    success: function (orderRes) {
                                        if (orderRes.status) {
                                            showToast("Berhasil membuat pesanan!", "success");
                                            window.location = `/id/invoices/${orderRes.order_id}`;
                                        } else {
                                            showToast(orderRes.data || "Terdapat kesalahan!", "error");
                                        }
                                    },
                                    error: function () {
                                        showToast("Terjadi kesalahan sistem", "error");
                                    }
                                });
                            }
                        });
                    } else {
                        showToast(res.data, "error");
                    }
                },
                error: function () {
                    showToast("Terjadi kesalahan saat validasi data", "error");
                }
            });
        });
        // Rating Bar Animation Trigger and Count Up Logic
        function animateValue(obj, start, end, duration, decimals = 0) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const val = progress * (end - start) + start;
                obj.innerHTML = decimals > 0 ? val.toFixed(decimals) : Math.floor(val);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }
        const observerOptions = {
            threshold: 0.2
        };
        const reviewObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    // Count up numbers
                    const ratingNumbers = entry.target.querySelectorAll('.rating-number');
                    ratingNumbers.forEach(num => {
                        const target = parseInt(num.getAttribute('data-target'));
                        animateValue(num, 0, target, 1500);
                    });
                    const totalReviewCount = entry.target.querySelector('.review-total-count');
                    if (totalReviewCount) {
                        animateValue(totalReviewCount, 0, parseInt(totalReviewCount.getAttribute(
                            'data-target')), 1500);
                    }
                    const percentCount = entry.target.querySelector('.percent-count');
                    if (percentCount) {
                        animateValue(percentCount, 0, parseInt(percentCount.getAttribute('data-target')),
                            1500);
                    }
                    const scoreCount = entry.target.querySelector('.score-count');
                    if (scoreCount) {
                        animateValue(scoreCount, 0, parseFloat(scoreCount.getAttribute('data-target')),
                            1500, 1);
                    }
                    reviewObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);
        const reviewsCard = document.getElementById('reviews-card');
        if (reviewsCard) {
            reviewObserver.observe(reviewsCard);
        }

        // Circuit Board Animation Logic
        const canvas = document.getElementById('circuit-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];
        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }
        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.size = Math.random() * 2;
                this.color = `rgba(30, 64, 175, ${Math.random() * 0.3})`; // Darker Blue for subtle feel
            }
            update() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
            }
            draw() {
                ctx.fillStyle = this.color;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        function initParticles() {
            particles = [];
            for (let i = 0; i < 50; i++) {
                particles.push(new Particle());
            }
        }
        function drawLines() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 150) {
                            ctx.strokeStyle = `rgba(30, 64, 175, ${0.05 - dist/3000})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
        }
        function animate() {
            ctx.clearRect(0, 0, width, height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            drawLines();
            requestAnimationFrame(animate);
        }
        window.addEventListener('resize', () => {
            resize();
            initParticles();
        });
        resize();
        initParticles();
        animate();
        
        // Ensure body classes
        document.body.className = "text-gray-200 min-h-screen flex flex-col selection:bg-cyan-500 selection:text-white";
    </script>
    @endpush

    <div id="react-notif"></div>
@endsection
