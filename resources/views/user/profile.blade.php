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
    @keyframes pulse-action {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(6, 182, 212, 0.4); }
        50% { transform: scale(1.02); box-shadow: 0 0 25px 10px rgba(6, 182, 212, 0.2); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(6, 182, 212, 0); }
    }
    .btn-pulse { animation: pulse-action 2.5s infinite ease-in-out; }
</style>
<script src="{{ asset('js/tailwind-cdn.js') }}"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "#06b6d4",
                    "deep-navy": "#0B1120",
                    "slate-panel": "rgba(30, 41, 59, 0.4)",
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
        .glow-cyan {
            @apply border-cyan-500/30 shadow-[0_0_15px_rgba(6,182,212,0.1)];
        }
        .input-field {
            @apply w-full bg-slate-200/90 border-0 rounded-md px-4 py-2.5 text-slate-800 placeholder-slate-500 focus:ring-2 focus:ring-cyan-500 outline-none transition-all text-sm;
        }
    }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #0B1120; }
    ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
</style>
@endsection


@section('content')

@include('components.user.navbar')

{{-- Background Effects --}}
<div class="animated-circuit-bg"></div>
<div class="fixed inset-0 z-0 pointer-events-none">
    <div class="rain-drop" style="left: 10%; animation-duration: 2s; animation-delay: 0s;"></div>
    <div class="rain-drop" style="left: 30%; animation-duration: 1.5s; animation-delay: 0.5s;"></div>
    <div class="rain-drop" style="left: 50%; animation-duration: 2.5s; animation-delay: 1s;"></div>
    <div class="rain-drop" style="left: 70%; animation-duration: 1.8s; animation-delay: 0.2s;"></div>
    <div class="rain-drop" style="left: 90%; animation-duration: 2.2s; animation-delay: 0.7s;"></div>
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-900/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-900/10 rounded-full blur-[120px]"></div>
</div>

{{-- Main Content --}}
<main class="relative z-10 flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 md:py-8 pb-24 md:pb-8 flex flex-col md:flex-row gap-6 md:gap-8">

    @include('components.user.sidebar')

    {{-- Content Area --}}
    <section class="flex-grow max-w-3xl min-w-0">
        {{-- Breadcrumb --}}
        <a class="inline-flex items-center gap-2 text-cyan-400 text-sm mb-6 md:mb-8 hover:opacity-80 transition-opacity" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            <span class="font-medium">Dashboard</span>
        </a>

        {{-- Page Header --}}
        <div class="mb-6 md:mb-10">
            <h1 class="text-xl md:text-2xl font-bold text-white mb-2">Profil</h1>
            <p class="text-slate-400 text-xs md:text-sm">Informasi ini bersifat rahasia, jadi berhati-hatilah dengan apa yang Anda bagikan.</p>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="glass-panel rounded-xl p-4 border-l-4 border-red-500 mb-6">
                <ul class="text-xs text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm">error</span> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6">
                <div class="text-cyan-400 font-medium text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Profile Form --}}
        <form action="{{ route('saveEditProfile') }}" method="POST" class="space-y-5 md:space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-white">Nama Anda</label>
                    <input class="input-field" type="text" name="name" id="nama" autocomplete="name" placeholder="Nama Anda" value="{{ Auth()->user()->name }}" required/>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-white">Username</label>
                    <input class="input-field" type="text" name="username" autocomplete="username" placeholder="Username" value="{{ Auth()->user()->username }}" required/>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-white">Alamat Email</label>
                <input class="input-field opacity-60 cursor-not-allowed" type="email" name="email" id="email" autocomplete="email" placeholder="Alamat Email" value="{{ Auth()->user()->email }}" disabled/>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-white">No. Handphone</label>
                <input class="input-field" type="text" name="no_wa" id="no" autocomplete="tel" placeholder="No. Handphone" value="{{ Auth()->user()->no_wa }}" required/>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-white">Masukan Password Untuk Merubah</label>
                <input class="input-field" type="password" name="password" autocomplete="off" placeholder="(Enter if want to changed)"/>
            </div>

            <div class="pt-2 md:pt-4">
                <button type="button" onclick="handleProfileUpdate()" class="bg-black text-white font-bold py-3 px-8 rounded-md transition-all border border-white/10 shadow-lg hover:bg-slate-900 btn-pulse text-sm md:text-base">
                    Ubah Profil
                </button>
            </div>
            <button type="submit" id="submit-form" class="hidden"></button>
        </form>
    </section>
</main>

{{-- OTP Modal --}}
<div id="otp-modal" class="fixed inset-0 z-[60] hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeOtpModal()"></div>
    <div class="relative w-full max-w-md glass-panel rounded-2xl overflow-hidden shadow-2xl glow-cyan animate-in fade-in zoom-in duration-300">
        <div class="bg-gradient-to-r from-cyan-600 to-cyan-400 p-4 flex items-center justify-between">
            <h3 class="text-white font-bold font-display uppercase tracking-wider">Verifikasi Keamanan</h3>
            <button onclick="closeOtpModal()" class="text-white/80 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-slate-300">Untuk mengganti password, silakan verifikasi nomor WhatsApp Anda terlebih dahulu.</p>
            
            <div id="otp-send-section" class="space-y-4">
                <div class="bg-slate-900/60 p-3 rounded-lg border border-white/5 flex items-center gap-3">
                    <span class="material-symbols-outlined text-cyan-400">whatsapp</span>
                    <div class="text-xs">
                        <div class="text-slate-500 uppercase font-semibold">Kirim ke</div>
                        <div class="text-white font-bold">{{ substr(Auth::user()->no_wa, 0, 4) . '****' . substr(Auth::user()->no_wa, -4) }}</div>
                    </div>
                </div>
                <button type="button" id="send-otp-btn" onclick="sendOtp()" class="w-full bg-cyan-500 hover:bg-cyan-400 text-white font-bold py-3 rounded-lg transition-all shadow-lg flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">send</span>
                    Kirim Kode OTP
                </button>
            </div>

            <div id="otp-verify-section" class="hidden space-y-4">
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Masukkan Kode OTP</label>
                    <input type="text" id="otp-input" maxlength="6" class="w-full bg-slate-900/60 border border-slate-700 rounded-lg px-4 py-3 text-white text-center text-2xl font-bold tracking-[0.5em] focus:ring-2 focus:ring-cyan-500 outline-none transition-all" placeholder="******">
                    <p class="text-[10px] text-slate-500 text-center">Kode telah dikirim ke WhatsApp Anda.</p>
                </div>
                <button type="button" id="verify-otp-btn" onclick="verifyOtp()" class="w-full bg-cyan-500 hover:bg-cyan-400 text-white font-bold py-3 rounded-lg transition-all shadow-lg">
                    Verifikasi & Simpan
                </button>
                <div class="text-center">
                    <button type="button" onclick="sendOtp()" id="resend-otp-btn" class="text-xs text-cyan-400 hover:underline">Kirim ulang kode</button>
                </div>
            </div>
            
            <div id="otp-error" class="hidden text-xs text-red-400 text-center flex items-center justify-center gap-1 mt-2">
                <span class="material-symbols-outlined text-sm">error</span>
                <span id="otp-error-msg"></span>
            </div>
        </div>
    </div>
</div>



@include('components.user.footer')

@push('custom_script')
<script>
    const otpModal = document.getElementById('otp-modal');
    const otpInput = document.getElementById('otp-input');
    const otpError = document.getElementById('otp-error');
    const otpErrorMsg = document.getElementById('otp-error-msg');
    const sendSection = document.getElementById('otp-send-section');
    const verifySection = document.getElementById('otp-verify-section');
    const profileForm = document.querySelector('form[action="{{ route('saveEditProfile') }}"]');
    const passwordInput = document.querySelector('input[name="password"]');

    let otpVerified = false;

    function handleProfileUpdate() {
        if (passwordInput.value.length > 0 && !otpVerified) {
            otpModal.classList.remove('hidden');
            return;
        }
        document.getElementById('submit-form').click();
    }

    function closeOtpModal() {
        otpModal.classList.add('hidden');
    }

    async function sendOtp() {
        const btn = document.getElementById('send-otp-btn');
        const resendBtn = document.getElementById('resend-otp-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="animate-spin material-symbols-outlined text-sm">progress_activity</span> Mengirim...';
        if (resendBtn) resendBtn.disabled = true;

        try {
            const response = await fetch('{{ route('profile.send_otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();

            if (data.success) {
                sendSection.classList.add('hidden');
                verifySection.classList.remove('hidden');
                otpError.classList.add('hidden');
            } else {
                showOtpError(data.message || 'Gagal mengirim OTP');
            }
        } catch (error) {
            showOtpError('Terjadi kesalahan koneksi');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">send</span> Kirim Kode OTP';
            if (resendBtn) resendBtn.disabled = false;
        }
    }

    async function verifyOtp() {
        if (otpInput.value.length < 6) {
            showOtpError('Masukkan 6 digit kode OTP');
            return;
        }

        const btn = document.getElementById('verify-otp-btn');
        btn.disabled = true;
        btn.textContent = 'Memverifikasi...';

        try {
            const response = await fetch('{{ route('profile.verify_otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ otp: otpInput.value })
            });
            const data = await response.json();

            if (data.success) {
                otpVerified = true;
                closeOtpModal();
                document.getElementById('submit-form').click();
            } else {
                showOtpError(data.message || 'Kode OTP salah');
            }
        } catch (error) {
            showOtpError('Terjadi kesalahan koneksi');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Verifikasi & Simpan';
        }
    }

    function showOtpError(msg) {
        otpErrorMsg.textContent = msg;
        otpError.classList.remove('hidden');
    }
</script>
@endpush

@endsection
