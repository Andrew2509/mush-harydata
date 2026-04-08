@extends('layouts.user')

@section('custom_style')
<!-- Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<script src="{{ asset('js/tailwind-cdn.js') }}"></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#3b82f6",
                    secondary: "#f97316",
                    "brand-blue": "#EBF5FF",
                    "brand-dark": "#1e293b",
                },
                fontFamily: {
                    sans: ["Inter", "Poppins", "sans-serif"],
                    mono: ["JetBrains Mono", "monospace"],
                },
            },
        },
    };
</script>
<style>
    body {
        background-color: #f8fafc;
        color: #1e293b;
        font-family: 'Inter', sans-serif;
    }
    .payment-card {
        background: white;
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    .sidebar-blue {
        background-color: #EBF5FF;
    }
    .btn-outline-blue {
        border: 1px solid #3b82f6;
        color: #3b82f6;
        background: transparent;
    }
    .btn-outline-blue:hover {
        background: #eff6ff;
    }
    .btn-filled-blue {
        background-color: #3b82f6;
        color: white;
    }
    .btn-filled-blue:hover {
        background-color: #2563eb;
    }
    .countdown-timer {
        color: #f97316;
        font-weight: 700;
        font-size: 3.5rem;
        line-height: 1;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen py-12 px-4 flex items-center justify-center">
    <div class="payment-card w-full max-w-6xl flex flex-col md:flex-row min-h-[600px]">
        
        <!-- Left Column: Payment Status & Actions -->
        <div class="sidebar-blue w-full md:w-[40%] p-8 flex flex-col">
            <!-- Header -->
            <div class="flex items-center gap-3 text-slate-600 mb-12">
                <a href="{{ route('riwayat') }}" class="flex items-center gap-2 hover:text-primary transition-colors">
                    <span class="material-icons-round">arrow_back</span>
                    <span class="font-semibold">Pembayaran</span>
                </a>
            </div>

            <!-- Countdown Section -->
            <div class="flex flex-col items-center text-center mb-10">
                <p class="text-slate-500 font-medium mb-4">Selesaikan pembayaran dalam</p>
                <div id="countdown" class="countdown-timer mb-4">--:--:--</div>
                <div class="text-slate-500 text-sm">
                    <p>Batas akhir pembayaran</p>
                    <p class="font-bold text-slate-700 mt-1">{{ \Carbon\Carbon::parse($expired)->translatedFormat('l, d F Y H:i') }} WIB</p>
                </div>
            </div>

            <!-- Separator -->
            <hr class="border-slate-200 mb-8" />

            <!-- Payment Method & Amount -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">{{ $metode_name }}</span>
                    <!-- Logo placeholder -->
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        @if(str_contains(strtolower($metode_name), 'shopeepay'))
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/ShopeePay_logo.svg/1024px-ShopeePay_logo.svg.png" class="h-6" alt="ShopeePay">
                        @elseif(str_contains(strtolower($metode_name), 'gopay'))
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/1024px-Gopay_logo.svg.png" class="h-6" alt="Gopay">
                        @elseif(str_contains(strtolower($metode_name), 'dana'))
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/1024px-Logo_dana_blue.svg.png" class="h-6" alt="DANA">
                        @elseif(str_contains(strtolower($metode_name), 'qris'))
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png" class="h-6" alt="QRIS">
                        @else
                            <span class="material-icons-round text-primary">payments</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-slate-500 text-sm font-medium">Jumlah Bayar</p>
                    <div class="flex items-baseline justify-between">
                        <span class="text-2xl font-bold text-slate-900">Rp{{ number_format($data->harga_pembayaran, 0, ',', '.') }}</span>
                        <button onclick="copyToClipboardText('{{ $data->harga_pembayaran }}')" class="text-primary text-sm font-bold hover:underline flex items-center gap-1">
                            Salin <span class="material-icons-round text-sm">content_copy</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-auto pt-10 grid grid-cols-2 gap-4">
                <button onclick="Swal.fire('Informasi', 'Silakan ikuti instruksi yang dikirimkan ke nomor WhatsApp Anda atau hubungi CS kami untuk panduan pembayaran.', 'info')" class="btn-outline-blue py-3 rounded-xl font-bold text-sm transition-all">Cara Pembayaran</button>
                @if($data->status_pembayaran == "Belum Lunas")
                    @php $metode = Str::upper($data->metode_pembayaran); @endphp
                    @if($metode == "QRIS" || $metode == "QRISREALTIME" || $metode == "QRISC" || $metode == "11" || $metode == "QRISOP" || $metode == "23" || $metode == "QRIS_CUSTOM" || $metode == "QRIS2" || str_contains($data->no_pembayaran, 'qrcode.php') || str_contains($data->no_pembayaran, 'static.tripay.co.id/qr/'))
                        <button onclick="showQR()" class="btn-filled-blue py-3 rounded-xl font-bold text-sm transition-all">Lanjutkan Pembayaran</button>
                    @else
                        <a href="{{ $data->no_pembayaran }}" class="btn-filled-blue py-3 rounded-xl font-bold text-sm transition-all text-center">Lanjutkan Pembayaran</a>
                    @endif
                @else
                    <button class="bg-green-500 text-white py-3 rounded-xl font-bold text-sm cursor-default">Pembayaran Berhasil</button>
                @endif
            </div>

            <!-- Attribution -->
            <div class="mt-8 flex flex-col items-center">
                <p class="text-xs text-slate-400 mb-2">Secured Payment by</p>
                @if(isset($config) && $config->logo_footer)
                    <img src="{{ asset($config->logo_footer) }}" class="h-6 object-contain" alt="Logo Footer">
                @else
                    <img src="https://tripay.co.id/images/logo-dark-v2.png" class="h-5 opacity-60" alt="Tripay">
                @endif
            </div>
        </div>

        <!-- Right Column: Invoice Details -->
        <div class="w-full md:w-[60%] p-10 bg-white flex flex-col">
            <!-- Brand Logo -->
            <div class="flex justify-center mb-8">
                @if (isset($config) && $config->logo_header)
                    <img src="{{ asset($config->logo_header) }}" alt="Logo" class="h-12 w-auto">
                @else
                    <div class="w-12 h-12 bg-primary flex items-center justify-center rounded-lg shadow-lg shadow-primary/20">
                        <span class="text-white font-black text-xl">{{ substr(env('APP_NAME', 'H'), 0, 1) }}</span>
                    </div>
                @endif
            </div>

            <p class="text-slate-500 text-sm text-center max-w-md mx-auto mb-10">
                Pastikan anda melakukan pembayaran sebelum melewati batas pembayaran dan dengan nominal yang tepat
            </p>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 gap-y-6 gap-x-8 mb-10">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Merchant</p>
                    <p class="text-slate-600 font-medium">ABC Store</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Pelanggan</p>
                    <p class="text-slate-600 font-medium">{{ $data->nickname ?? 'John Doe' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Invoice</p>
                    <p class="text-slate-600 font-medium">{{ $data->id_pembelian }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor HP</p>
                    <p class="text-slate-600 font-medium">{{ $data->user_id }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Referensi</p>
                    <p class="text-slate-600 font-medium truncate" title="{{ $data->reference }}">{{ Str::limit($data->reference, 15) }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Email</p>
                    <p class="text-slate-600 font-medium">@if(isset($data->email)) {{ $data->email }} @else - @endif</p>
                </div>
            </div>

            <!-- Payment Details Table -->
            <div class="flex-grow">
                <p class="font-bold text-slate-800 mb-4">Rincian Pembayaran</p>
                <div class="overflow-hidden border-t border-b border-slate-100 mb-6">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-50">
                            <tr>
                                <td class="py-4 text-slate-600">{{ $namas }} - {{ $data->layanan }}</td>
                                <td class="py-4 text-center text-slate-400">1x</td>
                                <td class="py-4 text-right font-medium text-slate-700">Rp{{ number_format($data->harga_pembayaran, 0, ',', '.') }}</td>
                            </tr>
                            <!-- You can add fee here if available -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Footer -->
            <div class="pt-6 border-t border-slate-200 mt-auto">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-slate-800">Total</span>
                    <span class="text-xl font-bold text-slate-900">Rp{{ number_format($data->harga_pembayaran, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal QRIS (Optional based on logic) -->
<div id="qrisModal" class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl animate-in fade-in zoom-in duration-300">
        <h3 class="font-bold text-xl text-slate-800 mb-6 font-display uppercase tracking-widest">Scan QR Code</h3>
        <div class="bg-white p-4 rounded-2xl shadow-inner border border-slate-100 mb-6 flex justify-center">
            <img id="qrisImage" src="{{ $data->no_pembayaran }}" alt="QRIS" class="w-64 h-64 object-contain">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <button onclick="downloadQR()" class="flex items-center justify-center gap-2 text-primary font-bold py-3 border border-primary/20 rounded-xl hover:bg-primary/5">
                <span class="material-icons-round text-sm">download</span> Unduh
            </button>
            <button onclick="closeQR()" class="bg-primary text-white font-bold py-3 rounded-xl hover:bg-primary/90">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('custom_script')
<script>
    function copyToClipboardText(text) {
        navigator.clipboard.writeText(text).then(() => {
            toastr.success('Teks disalin: ' + text);
        });
    }

    function showQR() {
        document.getElementById('qrisModal').classList.remove('hidden');
        document.getElementById('qrisModal').classList.add('flex');
    }

    function closeQR() {
        document.getElementById('qrisModal').classList.add('hidden');
        document.getElementById('qrisModal').classList.remove('flex');
    }

    function downloadQR() {
        const qrUrl = document.getElementById('qrisImage').src;
        const link = document.createElement('a');
        link.href = qrUrl;
        link.download = "qris-{{ $data->id_pembelian }}.png";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Countdown Logic
    const expiredDate = new Date("{{ $expired }}").getTime();
    const countdownElement = document.getElementById('countdown');

    const updateCountdown = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredDate - now;

        if (distance < 0) {
            clearInterval(updateCountdown);
            countdownElement.innerHTML = "EXPIRED";
            countdownElement.style.color = "#ef4444";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        const formattedTime = 
            (hours < 10 ? "0" + hours : hours) + ":" + 
            (minutes < 10 ? "0" + minutes : minutes) + ":" + 
            (seconds < 10 ? "0" + seconds : seconds);

        countdownElement.innerHTML = formattedTime;
    }, 1000);

    // Auto Refresh Status
    @if($data->status_pembayaran == 'Belum Lunas')
    const checkStatus = setInterval(function() {
        fetch("{{ route('pembelian.status', ['order' => $data->id_pembelian]) }}")
        .then(response => response.json())
        .then(data => {
            if (data.status_pembayaran === 'Lunas' || data.status_pembayaran === 'Success') {
                clearInterval(checkStatus);
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    text: 'Pesanan Anda sedang diproses.',
                    icon: 'success',
                    confirmButtonText: 'Oke'
                }).then(() => {
                    location.reload();
                });
            }
        })
        .catch(err => console.error('Status check failed', err));
    }, 5000);
    @endif
</script>
@endpush
