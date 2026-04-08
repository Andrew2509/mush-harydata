@extends('layouts.user')

@section('custom_style')
<style>
    body {
        background-color: #060B18 !important;
        color: #cbd5e1;
        font-family: 'Outfit', sans-serif;
        overflow-x: hidden;
    }
    ::selection {
        background-color: #06b6d4;
        color: white;
    }
    .animated-circuit-bg {
        position: fixed;
        inset: 0;
        z-index: -1;
        background: radial-gradient(circle at 50% 50%, #0a1628 0%, #060b18 100%);
        overflow: hidden;
    }
</style>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#06b6d4",
                    "background-dark": "#0B1120",
                    "surface-dark": "#1e293b",
                    "deep-navy": "#0B1120",
                    "card-dark": "rgba(30, 41, 59, 0.4)",
                },
                fontFamily: {
                    display: ["Rajdhani", "sans-serif"],
                    body: ["Outfit", "sans-serif"],
                },
            },
        },
    };
</script>
<style type="text/tailwindcss">
    @layer components {
        .glass-panel {
            @apply bg-slate-800/40 backdrop-blur-md border border-white/5;
        }
        .neon-text {
            text-shadow: 0 0 8px rgba(34, 211, 238, 0.5);
        }
        .status-pill {
            @apply inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border;
        }
        .status-success { @apply bg-green-500/10 text-green-400 border-green-500/20; }
        .status-pending { @apply bg-yellow-500/10 text-yellow-400 border-yellow-500/20; }
        .status-cancelled { @apply bg-red-500/10 text-red-400 border-red-500/20; }
        .status-processing { @apply bg-blue-500/10 text-blue-400 border-blue-500/20; }
    }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #0B1120; }
    ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    .scrolling-text {
        display: inline-block;
        white-space: nowrap;
        animation: scrollDash 30s linear infinite;
    }
    @keyframes scrollDash {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>
@endsection


@section('content')

@include('components.user.navbar')

{{-- Background Effects (same as Beranda) --}}
<div class="animated-circuit-bg"></div>
<div class="fixed inset-0 z-0 pointer-events-none">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-900/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-900/10 rounded-full blur-[120px]"></div>
</div>

{{-- Announcement Bar --}}
<div class="relative z-40 bg-slate-900/80 border-b border-white/5 overflow-hidden py-2">
    <div class="max-w-7xl mx-auto px-4 overflow-hidden relative">
        <div class="scrolling-text text-cyan-300 font-semibold text-xs tracking-wide">
            PENGUMUMAN: Selamat datang di sistem Member terbaru {{ ENV('APP_NAME') }}! Nikmati harga lebih murah dengan meningkatkan level keanggotaan Anda. Hubungi Admin untuk info lebih lanjut mengenai Top Up Saldo Otomatis 24 Jam.
        </div>
    </div>
</div>

{{-- Main Content --}}
<main class="relative z-10 flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 md:py-8 pb-24 md:pb-8 flex flex-col md:flex-row gap-6 md:gap-8">

    @include('components.user.sidebar')

    {{-- Content Area --}}
    <section class="flex-grow space-y-4 md:space-y-6 min-w-0">
        {{-- Alert Notice --}}
        <div class="glass-panel rounded-xl p-4 md:p-6 border-l-4 border-orange-500">
            <div class="flex items-start gap-3 md:gap-4">
                <div class="p-1.5 md:p-2 bg-orange-500/10 rounded-lg flex-shrink-0">
                    <span class="material-symbols-outlined text-orange-500 text-lg md:text-2xl">warning</span>
                </div>
                <div class="min-w-0">
                    <h3 class="text-white font-bold text-sm mb-1">Perhatian.</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Hai Bro, saat ini {{ ENV('APP_NAME') }} menerapkan sistem level akun yang dimana jika kamu mendaftar, kamu sudah mendapatkan harga yang lebih murah dari harga normal.
                    </p>
                    <div class="flex items-start md:items-center gap-2 mt-2 text-xs text-green-400 font-medium">
                        <span class="material-symbols-outlined text-sm flex-shrink-0 mt-0.5 md:mt-0">check_box</span>
                        <span>Tidak ada pengisian saldo di sistem kami karena setelah mendaftar kamu otomatis mendapatkan harga yg lebih terjangkau.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile & Status Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4">
            {{-- User Profile Card --}}
            <div class="glass-panel rounded-xl p-4 md:p-6 flex items-center justify-between">
                <div class="flex items-center gap-3 md:gap-4 min-w-0">
                    <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm md:text-lg flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-white font-bold text-sm md:text-lg leading-tight truncate">{{ Str::title(Auth::user()->name) }} ({{ Str::title(Auth::user()->username) }})</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-blue-600/20 text-blue-400 text-[10px] px-2 py-0.5 rounded border border-blue-500/30 font-bold uppercase">{{ Str::title(Auth::user()->role) }}</span>
                        </div>
                        <div class="text-slate-500 text-[11px] md:text-xs mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">call</span> +{{ Auth::user()->no_wa }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('editProfile') }}" class="p-2 bg-slate-800 rounded-lg border border-slate-700 text-slate-400 hover:text-white transition-colors flex-shrink-0">
                    <span class="material-symbols-outlined text-lg md:text-xl">settings</span>
                </a>
            </div>

            {{-- Balance / Deposit Card --}}
            <div class="glass-panel rounded-xl p-4 md:p-6 flex items-center justify-between">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="p-2 md:p-3 bg-slate-800 rounded-xl text-cyan-400 flex-shrink-0">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 font-display tracking-wider uppercase">Saldo Akun</p>
                        <p class="text-xl md:text-2xl font-display font-bold text-white">Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</p>
                    </div>
                </div>
                <a href="{{ route('reload') }}" class="p-2 bg-slate-800 rounded-lg border border-slate-700 text-slate-400 hover:text-cyan-400 transition-colors flex-shrink-0">
                    <span class="material-symbols-outlined text-lg md:text-xl">schedule</span>
                </a>
            </div>
        </div>

        {{-- Transaction Statistics --}}
        <div class="space-y-3 md:space-y-4">
            <h3 class="text-white font-display font-bold text-base md:text-lg uppercase tracking-wider">Jumlah Transaksi Hari Ini</h3>
            <div class="grid grid-cols-2 md:grid-cols-2 gap-3 md:gap-4">
                <div class="glass-panel rounded-xl p-4 md:p-8 text-center border-b-2 border-slate-700">
                    <div class="text-2xl md:text-4xl font-display font-bold text-white mb-1 md:mb-2">{{ $banyak_pembelian }}</div>
                    <div class="text-[9px] md:text-xs text-slate-500 font-medium uppercase tracking-widest">Total Transaksi</div>
                </div>
                <div class="glass-panel rounded-xl p-4 md:p-8 text-center border-b-2 border-slate-700">
                    <div class="text-lg md:text-4xl font-display font-bold text-white mb-1 md:mb-2">Rp {{ number_format($total_pembelian, 0, ',', '.') }}</div>
                    <div class="text-[9px] md:text-xs text-slate-500 font-medium uppercase tracking-widest">Total Penjualan</div>
                </div>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                <div class="bg-yellow-500 rounded-xl p-4 md:p-8 text-center shadow-lg shadow-yellow-500/20">
                    <div class="text-2xl md:text-4xl font-display font-bold text-white mb-1 md:mb-2">{{ $banyak_pembelian_pending }}</div>
                    <div class="text-[9px] md:text-[10px] text-white/80 font-bold uppercase tracking-widest">Menunggu</div>
                </div>
                <div class="bg-blue-500 rounded-xl p-4 md:p-8 text-center shadow-lg shadow-blue-500/20">
                    <div class="text-2xl md:text-4xl font-display font-bold text-white mb-1 md:mb-2">{{ $banyak_pembelian - $banyak_pembelian_success - $banyak_pembelian_batal - $banyak_pembelian_pending }}</div>
                    <div class="text-[9px] md:text-[10px] text-white/80 font-bold uppercase tracking-widest">Dalam Proses</div>
                </div>
                <div class="bg-emerald-500 rounded-xl p-4 md:p-8 text-center shadow-lg shadow-emerald-500/20">
                    <div class="text-2xl md:text-4xl font-display font-bold text-white mb-1 md:mb-2">{{ $banyak_pembelian_success }}</div>
                    <div class="text-[9px] md:text-[10px] text-white/80 font-bold uppercase tracking-widest">Sukses</div>
                </div>
                <div class="bg-red-500 rounded-xl p-4 md:p-8 text-center shadow-lg shadow-red-500/20">
                    <div class="text-2xl md:text-4xl font-display font-bold text-white mb-1 md:mb-2">{{ $banyak_pembelian_batal }}</div>
                    <div class="text-[9px] md:text-[10px] text-white/80 font-bold uppercase tracking-widest">Gagal</div>
                </div>
            </div>
        </div>

        {{-- Transaction History --}}
        <div class="space-y-3 md:space-y-4 pt-2 md:pt-4">
            <h3 class="text-white font-display font-bold text-base md:text-lg uppercase tracking-wider">Riwayat Transaksi Terbaru</h3>

            {{-- Desktop Table (hidden on mobile) --}}
            <div class="hidden md:block glass-panel rounded-2xl border border-cyan-500/30 overflow-hidden shadow-2xl relative">
                <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-cyan-500 to-transparent opacity-50"></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] text-cyan-300 uppercase bg-slate-900/60 font-display tracking-widest border-b border-white/5">
                            <tr>
                                <th class="px-6 py-4 font-semibold" scope="col">Nomor Invoice</th>
                                <th class="px-6 py-4 font-semibold" scope="col">ID Trx</th>
                                <th class="px-6 py-4 font-semibold" scope="col">Item</th>
                                <th class="px-6 py-4 font-semibold" scope="col">Inputan / ID</th>
                                <th class="px-6 py-4 font-semibold" scope="col">Harga</th>
                                <th class="px-6 py-4 font-semibold" scope="col">Tanggal</th>
                                <th class="px-6 py-4 font-semibold text-right" scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-slate-300 bg-slate-800/20">
                            @if(count($data) > 0)
                                @foreach($data as $pesanan)
                                    @if($pesanan->tipe_transaksi !== 'joki')
                                        @php
                                            $zone = $pesanan->zone != null ? "-".$pesanan->zone : "";
                                            $status = $pesanan->status;
                                            $statusClass = '';
                                            if ($status == 'Success' || $status == 'Sukses') { $statusClass = 'status-success'; $status = 'Success'; }
                                            elseif ($status == 'Pending' || $status == 'pending') { $statusClass = 'status-pending'; $status = 'Pending'; }
                                            elseif ($status == 'Proses' || $status == 'Process') { $statusClass = 'status-processing'; $status = 'Processing'; }
                                            else { $statusClass = 'status-cancelled'; $status = 'Cancelled'; }
                                        @endphp
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="px-6 py-4 font-medium text-white whitespace-nowrap">
                                                <a href="{{ ENV('APP_URL') }}/id/invoices/{{ $pesanan->order_id }}" class="text-cyan-400 hover:text-cyan-300 transition-colors">{{ $pesanan->order_id }}</a>
                                            </td>
                                            <td class="px-6 py-4 font-mono text-[11px] text-slate-400">n/a</td>
                                            <td class="px-6 py-4 text-xs">{{ $pesanan->layanan }}</td>
                                            <td class="px-6 py-4 font-mono text-[11px]">{{ $pesanan->user_id }}{{ $zone }}</td>
                                            <td class="px-6 py-4 font-bold text-white">Rp&nbsp;{{ number_format($pesanan->harga, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-[11px] text-slate-500">{{ $pesanan->created_at }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="status-pill {{ $statusClass }}">{{ $status }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-slate-600">inbox</span>
                                            <p class="text-slate-500 font-semibold text-sm">Data tidak ditemukan!</p>
                                            <p class="text-slate-600 text-xs">Tidak ada aktivitas transaksi hari ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card List (hidden on desktop) --}}
            <div class="md:hidden space-y-3">
                @if(count($data) > 0)
                    @foreach($data as $pesanan)
                        @if($pesanan->tipe_transaksi !== 'joki')
                            @php
                                $zone = $pesanan->zone != null ? "-".$pesanan->zone : "";
                                $status = $pesanan->status;
                                $statusClass = '';
                                if ($status == 'Success' || $status == 'Sukses') { $statusClass = 'status-success'; $status = 'Success'; }
                                elseif ($status == 'Pending' || $status == 'pending') { $statusClass = 'status-pending'; $status = 'Pending'; }
                                elseif ($status == 'Proses' || $status == 'Process') { $statusClass = 'status-processing'; $status = 'Processing'; }
                                else { $statusClass = 'status-cancelled'; $status = 'Cancelled'; }
                            @endphp
                            <a href="{{ ENV('APP_URL') }}/id/invoices/{{ $pesanan->order_id }}" class="block glass-panel rounded-xl p-4 border border-white/5 hover:border-cyan-500/30 transition-all">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-cyan-400 font-semibold text-xs truncate mr-2">{{ $pesanan->order_id }}</span>
                                    <span class="status-pill {{ $statusClass }} flex-shrink-0">{{ $status }}</span>
                                </div>
                                <p class="text-white font-medium text-sm mb-2 truncate">{{ $pesanan->layanan }}</p>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500">{{ $pesanan->user_id }}{{ $zone }}</span>
                                    <span class="font-bold text-white">Rp {{ number_format($pesanan->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-[10px] text-slate-600 mt-2">{{ $pesanan->created_at }}</div>
                            </a>
                        @endif
                    @endforeach
                @else
                    <div class="glass-panel rounded-xl p-8 text-center">
                        <span class="material-symbols-outlined text-4xl text-slate-600">inbox</span>
                        <p class="text-slate-500 font-semibold text-sm mt-3">Data tidak ditemukan!</p>
                        <p class="text-slate-600 text-xs mt-1">Tidak ada aktivitas transaksi hari ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</main>



@include('components.user.footer')

@push('custom_script')

@endpush

@endsection
