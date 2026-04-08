@extends('layouts.user')

@section('custom_style')
<style>
    body {
        background-color: #060B18 !important;
        color: #cbd5e1;
        font-family: 'Outfit', sans-serif;
        overflow-x: hidden;
    }
    ::selection { background-color: #06b6d4; color: white; }
    .animated-circuit-bg {
        position: fixed; inset: 0; z-index: -1;
        background: radial-gradient(circle at 50% 50%, #0a1628 0%, #060b18 100%);
        overflow: hidden;
    }
    .rain-drop {
        position: fixed; width: 1px; height: 50px;
        background: linear-gradient(to bottom, transparent, rgba(34, 211, 238, 0.2));
        animation: rain linear infinite;
    }
    @keyframes rain {
        0% { transform: translateY(-100vh); }
        100% { transform: translateY(100vh); }
    }
    .glass-panel {
        background: rgba(30, 41, 59, 0.4);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
    }
    .glow-cyan {
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.1);
    }
    .step-badge {
        background: #06b6d4;
        color: white;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.25rem;
        border-radius: 0.75rem;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
    }
    .section-header {
        background: rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .method-list:hover {
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
        background: rgba(6, 182, 212, 0.05);
    }
    .method-list.selected {
        border-color: #06b6d4;
        background: rgba(6, 182, 212, 0.1);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
    }
</style>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: "#06b6d4",
                    "deep-navy": "#0B1120",
                },
                fontFamily: {
                    display: ["Rajdhani", "sans-serif"],
                    body: ["Outfit", "sans-serif"],
                },
            },
        },
    };
</script>
@endsection

@section('content')

@include('components.user.navbar')

{{-- Background Effects --}}
<div class="animated-circuit-bg"></div>
<div class="fixed inset-0 z-0 pointer-events-none">
    <div class="rain-drop" style="left: 10%; animation-duration: 2s;"></div>
    <div class="rain-drop" style="left: 30%; animation-duration: 1.5s;"></div>
    <div class="rain-drop" style="left: 50%; animation-duration: 2.5s;"></div>
    <div class="rain-drop" style="left: 70%; animation-duration: 1.8s;"></div>
    <div class="rain-drop" style="left: 90%; animation-duration: 2.2s;"></div>
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-900/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-900/10 rounded-full blur-[120px]"></div>
</div>

<main class="relative z-10 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">
    @include('components.user.sidebar')

    <section class="flex-grow space-y-6 min-w-0">
        {{-- Header Area --}}
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-display font-bold text-white tracking-tight flex items-center gap-3">
                    <i class="fas fa-wallet text-cyan-500"></i> DEPOSIT SALDO
                </h1>
                <p class="text-gray-400 text-sm font-medium">Isi saldo akunmu untuk transaksi lebih cepat.</p>
            </div>
            <a href="{{ route('reload') }}" class="glass-panel px-4 py-2 text-xs font-bold text-cyan-400 flex items-center gap-2 hover:bg-white/5 transition-all">
                <i class="fas fa-history"></i> RIWAYAT
            </a>
        </div>

        <form action="{{ route('deposit.store') }}" method="POST" id="topup-form" x-data="{ selectedMethod: null }">
            @csrf
            <input type="hidden" id="selected_method" name="metode" :value="selectedMethod">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Side: Amount & Payment --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    @if ($errors->any())
                        <div class="glass-panel p-4 border-l-4 border-red-500 bg-red-500/10">
                            <ul class="text-sm text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="glass-panel p-4 border-l-4 border-green-500 bg-green-500/10">
                            <p class="text-sm text-green-400"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Step 1: Nominal & Contact -->
                    <div class="glass-panel overflow-hidden glow-cyan">
                        <div class="section-header">
                            <div class="step-badge">1</div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Masukkan Data</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-cyan-400 uppercase tracking-widest">Jumlah Deposit</label>
                                <div class="relative group">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-300"></div>
                                    <input class="relative w-full bg-[#0f172a] border border-gray-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-cyan-500 placeholder-gray-600 font-mono transition-all"
                                        type="number" name="jumlah" id="jumlah_input" placeholder="Min. Rp 1.000" min="1" required/>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-cyan-400 uppercase tracking-widest">No. WhatsApp</label>
                                <div class="relative group">
                                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl blur opacity-25 group-hover:opacity-50 transition duration-300"></div>
                                    <input class="relative w-full bg-[#0f172a] border border-gray-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-cyan-500 placeholder-gray-600 font-mono transition-all"
                                        type="tel" name="no_telfon" placeholder="Contoh: 08xxxxxxxxxx" required/>
                                </div>
                                <p class="text-[10px] text-gray-500 italic font-medium px-1">*Info login & tagihan akan dikirim ke No WA ini.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Payment Method Selection -->
                    <div class="glass-panel overflow-hidden glow-cyan" x-data="{ openGroup: 'virtual-account' }">
                        <div class="section-header">
                            <div class="step-badge">2</div>
                            <h3 class="font-display font-bold text-xl text-white tracking-wide uppercase">Pilih Pembayaran</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @php
                                $hot_methods = $pay_method->where('keterangan', 'HOT');
                                $qris_methods = $pay_method->where('tipe', 'qris');
                                $other_methods = $pay_method->where('keterangan', '!=', 'HOT')->where('tipe', '!=', 'qris')->groupBy('tipe');
                            @endphp

                            <!-- Hot Methods -->
                            @if($hot_methods->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($hot_methods as $p)
                                <div @click="selectedMethod = '{{ $p->code }}'" 
                                     :class="{ 'selected': selectedMethod === '{{ $p->code }}' }"
                                     class="method-list group bg-[#0f172a] rounded-xl p-4 relative cursor-pointer border-2 border-gray-700/50 transition-all flex items-center justify-between overflow-hidden">
                                    <div class="absolute -top-3 -right-3 bg-red-600 text-white text-[9px] font-black px-4 py-2 transform rotate-12 shadow-lg z-10 border border-white/20">HOT</div>
                                    <div class="flex flex-col gap-1">
                                        <div class="font-black text-xs text-white uppercase tracking-tight">{{ $p->name }}</div>
                                        <div class="text-[10px] text-gray-500 font-bold">PROSES OTOMATIS</div>
                                        <div class="text-sm font-black text-cyan-400 mt-1 hargapembayaran" id="{{ $p->code }}">Rp 0</div>
                                    </div>
                                    <div class="w-14 h-10 bg-white/5 rounded-lg p-1.5 flex items-center justify-center flex-shrink-0 group-hover:bg-white/10 transition-colors">
                                        <img alt="{{ $p->name }}" class="h-6 w-full object-contain" src="{{ $p->images }}" />
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- QRIS Section -->
                            @if($qris_methods->count() > 0)
                                @foreach($qris_methods as $p)
                                <div @click="selectedMethod = '{{ $p->code }}'"
                                     :class="{ 'selected': selectedMethod === '{{ $p->code }}' }"
                                     class="method-list relative bg-gradient-to-r from-white/10 to-white/5 rounded-xl p-4 cursor-pointer border-2 border-transparent hover:shadow-xl transition-all">
                                    <div class="absolute top-0 right-0 bg-green-600 text-white text-[9px] font-black px-3 py-1 rounded-bl-xl tracking-widest shadow-lg z-10 uppercase">Otomatis</div>
                                    <div class="flex items-center gap-4">
                                        <div class="bg-white p-2 rounded-lg"><img alt="{{ $p->name }}" class="h-6 object-contain" src="{{ $p->images }}" /></div>
                                        <div>
                                            <div class="text-white font-black text-base tracking-tight uppercase">{{ $p->name }}</div>
                                            <div class="text-xs font-bold text-cyan-400 mt-0.5 hargapembayaran" id="{{ $p->code }}">Rp 0</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            <!-- Grouped Methods (VA, E-Wallet, Retail) -->
                            @foreach($other_methods as $tipe => $methods)
                            <div class="bg-black/20 rounded-xl overflow-hidden border border-white/5 shadow-inner">
                                <button type="button" @click="openGroup = (openGroup === '{{ $tipe }}' ? null : '{{ $tipe }}')" 
                                        class="w-full p-4 flex items-center justify-between cursor-pointer hover:bg-white/5 transition-colors">
                                    <h3 class="text-white font-black text-sm uppercase tracking-[0.2em] opacity-80">{{ str_replace('-', ' ', $tipe) }}</h3>
                                    <i class="fas fa-chevron-down text-cyan-500 transition-transform duration-300" :class="{ 'rotate-180': openGroup === '{{ $tipe }}' }"></i>
                                </button>
                                <div x-show="openGroup === '{{ $tipe }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-white/5">
                                        @foreach($methods as $p)
                                        <div @click="selectedMethod = '{{ $p->code }}'"
                                             :class="{ 'selected': selectedMethod === '{{ $p->code }}' }"
                                             class="method-list group bg-[#0f172a]/50 rounded-xl p-4 relative cursor-pointer border-2 border-gray-700/30 transition-all flex items-center justify-between overflow-hidden">
                                            <div class="flex flex-col gap-1">
                                                <div class="font-bold text-[11px] text-white/90 uppercase truncate w-24 md:w-32">{{ $p->name }}</div>
                                                <div class="text-sm font-black text-cyan-400 hargapembayaran" id="{{ $p->code }}">Rp 0</div>
                                            </div>
                                            <div class="w-12 h-8 bg-white/5 rounded-lg p-1.5 flex items-center justify-center flex-shrink-0">
                                                <img alt="{{ $p->name }}" class="h-5 w-full object-contain filter {{ $p->code == 'BCA' ? '' : 'brightness-125' }}" src="{{ $p->images }}" />
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Side: Summary --}}
                <div class="space-y-6">
                    <!-- Balance Card -->
                    <div class="glass-panel p-6 glow-cyan relative overflow-hidden group">
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
                        <div class="relative z-10 flex flex-col gap-2">
                            <span class="text-[10px] font-black text-cyan-400 uppercase tracking-[0.3em]">Saldo Saat Ini</span>
                            <div class="text-3xl font-display font-black text-white tracking-widest">
                                Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}
                            </div>
                        </div>
                        <i class="fas fa-wallet absolute bottom-4 right-4 text-4xl text-white/5 -rotate-12 group-hover:rotate-0 transition-transform"></i>
                    </div>

                    <!-- Payment Summary (Floating) -->
                    <div class="glass-panel p-1 border-white/10 sticky top-24">
                        <div class="bg-black/40 rounded-[calc(1rem-4px)] p-6 space-y-6">
                            <h4 class="text-xs font-black text-white uppercase tracking-[0.2em] border-b border-white/5 pb-4">Ringkasan Deposit</h4>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/5">
                                    <span class="text-xs font-bold text-gray-400">Total Tagihan</span>
                                    <span class="text-lg font-black text-cyan-400" id="total_pembayaran_summary">Rp 0</span>
                                </div>
                                
                                <div class="p-4 rounded-xl bg-yellow-500/5 border border-yellow-500/10 space-y-2">
                                    <div class="flex items-center gap-2 text-yellow-500 font-bold text-[10px] uppercase tracking-wider">
                                        <i class="fas fa-shield-alt"></i> Transaksi Aman
                                    </div>
                                    <p class="text-[11px] text-gray-500 leading-relaxed font-medium">
                                        Pembayaran akan diverifikasi secara otomatis oleh sistem dalam hitungan detik setelah dana diterima.
                                    </p>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-cyan-600 to-blue-700 text-white font-black py-4 rounded-xl shadow-[0_4px_20px_rgba(6,182,212,0.3)] hover:shadow-[0_4px_25px_rgba(6,182,212,0.5)] hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Konfirmasi Deposit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>

@include('components.user.footer')

@push('custom_script')
<script>
    const jumlahInput = document.getElementById('jumlah_input');
    const hargaElements = document.querySelectorAll('.hargapembayaran');
    const summaryTotal = document.getElementById('total_pembayaran_summary');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(angka);
    }

    function calculatePrices() {
        const amount = parseFloat(jumlahInput.value) || 0;
        
        hargaElements.forEach(el => {
            const code = el.id;
            let total = amount;

            if (amount > 0) {
                // Calculation Logic matches topup.blade.php / standard fees
                if (['DANA', 'OVOPUSH', 'SHOPEEPAY', 'LINKAJA', 'VIRGO', 'ASTRAPAY'].includes(code)) {
                    total = amount + (amount * 0.03);
                } else if (code.includes('QRIS')) {
                    total = amount + (amount * 0.01) + 100;
                } else if (['ALFAMART', 'INDOMARET'].includes(code)) {
                    total = amount + 3500;
                } else if (code.includes('VA') || ['BCA', 'BNI', 'BRI', 'MANDIRI', 'CIMB', 'PERMATA', 'BNC', 'MAYBANK', 'DANAMON', 'SINARMAS'].includes(code)) {
                    total = amount + 4500;
                }
            }

            el.textContent = formatRupiah(Math.ceil(total));
        });

        // Update summary based on selected method if any (simplified to show amount + max possible fee if none selected, or selected one)
        const selectedEl = document.querySelector('.method-list.selected .hargapembayaran');
        if (selectedEl) {
            summaryTotal.textContent = selectedEl.textContent;
        } else {
            summaryTotal.textContent = formatRupiah(amount);
        }
    }

    jumlahInput.addEventListener('input', calculatePrices);

    // Watch for selection changes (Alpine handles the state, but we need to trigger price update for summary)
    document.addEventListener('click', (e) => {
        if (e.target.closest('.method-list')) {
            setTimeout(calculatePrices, 10);
        }
    });
</script>
@endpush

@endsection
