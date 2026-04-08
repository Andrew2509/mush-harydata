@extends('layouts.user')

@section('custom_style')
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#F5C754",
                        secondary: "#0EA5E9",
                        "background-dark": "#060B18",
                        "card-dark": "#111827",
                        "accent-cyan": "#22D3EE"
                    },
                    fontFamily: {
                        display: ["Plus Jakarta Sans", "sans-serif"],
                        body: ["Plus Jakarta Sans", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                    },
                },
            },
        };
    </script>
    <style type="text/tailwindcss">
        :root {
            --bg-deep: #060B18;
            --cyan-glow: rgba(34, 211, 238, 0.6);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-deep) !important;
            overflow-x: hidden;
        }

        .text-glow-accent {
            text-shadow: 0 0 20px rgba(34, 211, 238, 0.4);
        }

        .glow-cyan {
            text-shadow: 0 0 10px rgba(0, 229, 255, 0.5);
        }

        .interactive-card:hover {
            transform: scale(1.02);
            border-color: rgba(34, 211, 238, 0.4);
            box-shadow: 0 0 30px rgba(34, 211, 238, 0.1);
        }

        .glass-nav {
            background: rgba(6, 11, 24, 0.8);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dropdown-content {
            @apply absolute top-full right-0 mt-2 w-56 bg-slate-900/95 backdrop-blur-xl border border-white/10 rounded-2xl p-2 shadow-2xl opacity-0 invisible translate-y-4 transition-all duration-300;
        }

        .dropdown:hover .dropdown-content {
            @apply opacity-100 visible translate-y-0;
        }

        .interactive-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .interactive-card:hover {
            transform: scale(1.02) translateY(-2px);
            z-index: 10;
            border-color: #22d3ee;
            box-shadow: 0 0 25px var(--cyan-glow);
        }

        .badge-pending {
            background-color: rgba(253, 224, 71, 0.1);
            color: #fde047;
            border: 1px solid rgba(253, 224, 71, 0.2);
        }

        .badge-success {
            background-color: rgba(134, 239, 172, 0.1);
            color: #86efac;
            border: 1px solid rgba(134, 239, 172, 0.2);
        }

        .badge-cancelled {
            background-color: rgba(248, 113, 113, 0.1);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, 0.2);
        }

        .badge {
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            text-align: center;
            min-width: 90px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .shadow-blue-glow {
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.4);
        }
    </style>
@endsection

@section('content')
    @include('components.user.navbar')

    <!-- Video Background Container -->
    <div class="fixed inset-0 z-[-2] overflow-hidden bg-slate-950 pointer-events-none">
        <iframe 
            class="absolute top-1/2 left-1/2 w-[100vw] h-[56.25vw] min-h-[100vh] min-w-[177.78vh] -translate-x-1/2 -translate-y-1/2 pointer-events-none"
            src="https://www.youtube.com/embed/U80ib5a7T14?si=amxV1hWwdxxNMCdW&amp;controls=0&amp;start=38&amp;autoplay=1&amp;mute=1&amp;loop=1&amp;playlist=U80ib5a7T14" 
            title="YouTube video player" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            referrerpolicy="strict-origin-when-cross-origin" 
            allowfullscreen>
        </iframe>
    </div>
    <!-- Dark overlay for readability -->
    <div class="fixed inset-0 z-[-1] pointer-events-none bg-background-dark/80 backdrop-blur-[2px]"></div>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10 min-h-screen relative z-10">
        <!-- Header Section -->
        <div class="text-center space-y-3 pt-2 md:pt-6">
            <div class="flex justify-center mb-6">
                @if ($config->logo_header)
                    <img alt="Logo" class="relative w-32 h-auto mx-auto drop-shadow-[0_0_20px_rgba(0,229,255,0.4)]" src="{{ url('') }}{{ $config->logo_header }}"/>
                @else
                    <div
                        class="w-24 h-24 md:h-32 md:w-32 bg-primary flex items-center justify-center rounded-[2rem] shadow-2xl shadow-primary/20">
                        <span
                            class="text-background-dark font-black text-4xl md:text-6xl">{{ substr(ENV('APP_NAME'), 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3 glow-cyan tracking-tight">
                Lacak Pesananmu
            </h1>
            <p class="text-slate-400 text-xs md:text-sm max-w-md mx-auto font-medium leading-relaxed">
                Masukkan nomor invoice Anda untuk melihat status transaksi terkini. Pastikan nomor invoice yang dimasukkan telah benar.
            </p>
        </div>

        <!-- Search Bar Section -->
        <div class="w-full max-w-3xl mx-auto px-4">
            <form action="{{ route('cari') }}" method="POST"
                class="relative flex items-center bg-slate-900/40 backdrop-blur-xl border border-white/10 rounded-xl h-14 md:h-16 shadow-2xl overflow-hidden group focus-within:ring-2 focus-within:ring-secondary/50 transition-all">
                @csrf
                <div class="pl-6 md:pl-8 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-slate-400">receipt_long</span>
                </div>
                <input
                    class="w-full h-full bg-transparent border-0 focus:ring-0 text-white placeholder-slate-500 px-4 md:px-6 text-sm md:text-base font-medium"
                    placeholder="Masukkan Nomor Invoice (INV-xxxxxxx)" type="text" name="id" id="invoice" required />
                <div class="h-full p-2 md:p-3">
                    <button type="submit"
                        class="h-full px-6 md:px-10 bg-secondary rounded-lg text-white font-black flex items-center gap-2 hover:brightness-110 transition-all tracking-wider text-xs whitespace-nowrap shadow-blue-glow">
                        <span class="material-symbols-outlined text-xs hidden sm:block">search</span> Lacak
                    </button>
                </div>
            </form>
        </div>

        <!-- History Transactions Table -->
        <div class="w-full max-w-5xl mx-auto pb-16">
            <div class="bg-slate-900/40 backdrop-blur-xl border border-white/10 rounded-[1.5rem] overflow-hidden shadow-2xl">
                <div class="p-4 md:p-6 border-b border-white/5 flex items-center justify-between bg-white/5">
                    <h2 class="text-lg md:text-xl font-bold text-white flex items-center gap-3">
                        <span class="material-symbols-outlined text-secondary">history</span>
                        10 Pesanan Terakhir Anda
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-950/60 text-slate-400 text-[10px] uppercase tracking-widest font-bold">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">No. Invoice</th>
                                <th class="py-4 px-6">Item</th>
                                <th class="py-4 px-6">Harga</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-medium text-slate-300 divide-y divide-white/5">
                            @forelse ($pembelians as $pembelian)
                                @php
                                    $status = $pembelian->status;
                                    $badgeClass = 'badge-pending';
                                    if ($status == 'Success' || $status == 'Sukses') {
                                        $badgeClass = 'badge-success';
                                        $status = 'Success';
                                    } elseif (
                                        $status == 'Batal' ||
                                        $status == 'Gagal' ||
                                        $status == 'Cancelled'
                                    ) {
                                        $badgeClass = 'badge-cancelled';
                                        $status = 'Cancelled';
                                    }
                                @endphp
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="py-4 px-6 whitespace-nowrap">{{ $pembelian->created_at }}</td>
                                    <td class="py-4 px-6 font-mono text-secondary group-hover:text-cyan-400 transition-colors">
                                        {{ substr($pembelian->order_id, 0, 2) . '******' . substr($pembelian->order_id, -3) }}
                                    </td>
                                    <td class="py-4 px-6 text-white font-bold">{{ $pembelian->layanan }}</td>
                                    <td class="py-4 px-6 font-bold text-white">Rp
                                        {{ number_format($pembelian->harga, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-24 text-center" colspan="5">
                                        <div class="flex flex-col items-center justify-center space-y-5">
                                            <div class="w-24 h-24 rounded-full bg-slate-800/50 border border-white/5 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-outlined text-5xl text-slate-500">search_off</span>
                                            </div>
                                            <div>
                                                <p class="text-xl font-bold text-white">Belum ada transaksi</p>
                                                <p class="text-base text-slate-500 mt-2">Lacak pesanan Anda menggunakan kolom pecarian di atas</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    @include('components.user.footer')
@endsection
