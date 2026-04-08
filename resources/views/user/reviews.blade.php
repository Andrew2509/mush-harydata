@extends('layouts.user')

@section('custom_style')
    <script src="{{ asset('js/tailwind-cdn.js') }}"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
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
                    },
                    fontFamily: {
                        display: ["Orbitron", "sans-serif"],
                        body: ["Rajdhani", "sans-serif"],
                    },
                    animation: {
                        'scroll': 'scroll 30s linear infinite',
                        'rain-rise-slow': 'rain-rise 15s linear infinite',
                        'rain-rise-medium': 'rain-rise 10s linear infinite',
                        'rain-rise-fast': 'rain-rise 7s linear infinite',
                    },
                    keyframes: {
                        scroll: {
                            '0%': { transform: 'translateX(0)' },
                            '100%': { transform: 'translateX(-50%)' },
                        },
                        'rain-rise': {
                            '0%': { transform: 'translateY(100vh) rotate(12deg)', opacity: '0' },
                            '10%': { opacity: '0.8' },
                            '90%': { opacity: '0.8' },
                            '100%': { transform: 'translateY(-20vh) rotate(12deg)', opacity: '0' },
                        }
                    }
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        /* Global Navbar Styles */
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
            z-index: 60;
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

        /* Reviews Page Specific Styles */
        .circuit-pattern {
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                linear-gradient(rgba(5, 11, 20, 0.9), rgba(5, 11, 20, 0.95)),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2' fill='%2306b6d4' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card:hover {
            background: rgba(30, 41, 59, 0.6);
            border-color: rgba(6, 182, 212, 0.3);
            transform: translateY(-5px);
            box-shadow: 0 10px 40px -10px rgba(6, 182, 212, 0.2);
        }

        #app, main, body {
            background: none !important;
        }
    </style>
@endsection

@section('content')
    <div class="circuit-pattern min-h-screen relative overflow-x-hidden flex flex-col bg-background-dark">
        {{-- Floating Background Orbs --}}
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0 overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px]"></div>
        </div>

        @include('components.user.navbar')

        <main class="flex-grow relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex flex-col gap-16">
            <section class="w-full relative group">
                {{-- Decorative Vertical Elements (Rain Style) --}}
                <div class="fixed top-0 left-0 w-full h-full pointer-events-none overflow-hidden z-0">
                    <div class="absolute top-0 left-[10%] w-32 h-32 bg-slate-600/20 border border-white/10 rounded-3xl backdrop-blur-[1px] animate-rain-rise-slow flex items-center justify-center overflow-hidden">
                        <i class="fas fa-shield-alt text-white/20 text-6xl transform -rotate-[12deg] drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]"></i>
                    </div>
                    <div class="absolute top-0 right-[25%] w-24 h-24 bg-slate-500/20 border border-white/10 rounded-2xl backdrop-blur-[1px] animate-rain-rise-medium delay-1000 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-gem text-primary/40 text-5xl transform -rotate-[12deg] drop-shadow-[0_0_15px_rgba(6,182,212,0.4)]"></i>
                    </div>
                    <div class="absolute top-0 left-[45%] w-20 h-20 bg-slate-700/20 border border-white/10 rounded-2xl backdrop-blur-[1px] animate-rain-rise-fast delay-2000 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-bolt text-yellow-400/30 text-4xl transform -rotate-[12deg] drop-shadow-[0_0_10px_rgba(250,204,21,0.4)]"></i>
                    </div>
                    <div class="absolute top-0 right-[5%] w-40 h-40 bg-slate-800/20 border border-white/10 rounded-[2rem] backdrop-blur-[1px] animate-rain-rise-slow delay-3000 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-gamepad text-secondary/30 text-8xl transform -rotate-[12deg] drop-shadow-[0_0_20px_rgba(15,23,42,0.5)]"></i>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6 relative z-10">
                    <div class="max-w-2xl relative z-10">
                        <h2
                            class="text-4xl md:text-5xl font-display font-black text-white mb-4 drop-shadow-[0_0_10px_rgba(14,165,233,0.5)] uppercase">
                            TESTIMONIALS
                            <span class="inline-block w-3 h-3 bg-primary rounded-full ml-1 animate-pulse"></span>
                        </h2>
                        <p class="text-slate-400 text-lg leading-relaxed font-body font-medium">
                            Terima kasih untuk semua pelanggan yang memberi kami ulasan dan peringkat. Kepercayaan Anda adalah
                            prioritas utama kami dalam menyediakan layanan top-up terbaik.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary/20 hover:border-primary/50 transition-all">
                            <span class="material-icons text-white">chevron_left</span>
                        </button>
                        <button class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary/20 hover:border-primary/50 transition-all">
                            <span class="material-icons text-white">chevron_right</span>
                        </button>
                    </div>
                </div>

                <div class="w-full overflow-hidden relative z-10 py-10">
                    <div class="absolute top-0 left-0 w-24 h-full bg-gradient-to-r from-background-dark to-transparent z-10 pointer-events-none"></div>
                    <div class="absolute top-0 right-0 w-24 h-full bg-gradient-to-l from-background-dark to-transparent z-10 pointer-events-none"></div>
                    
                    <div class="flex w-max animate-scroll hover:[animation-play-state:paused] gap-8">
                        @foreach ($ratings as $rating)
                            @include('partials.testimonial_card', ['rating' => $rating])
                        @endforeach
                        
                        {{-- Second set for loop --}}
                        @foreach ($ratings as $rating)
                            @include('partials.testimonial_card', ['rating' => $rating])
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        @include('components.user.footer')
    </div>
@endsection
