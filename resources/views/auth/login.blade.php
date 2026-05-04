@extends('layouts.user')

@push('custom_script')
<script>
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>
@endpush

@section('custom_style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        background: #000;
        height: 100vh;
        margin: 0;
        font-family: 'Rajdhani', sans-serif;
        overflow: hidden;
    }

    #video-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .login-wrapper {
        position: relative;
        z-index: 2;
        display: flex;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(5px);
    }

    /* Desktop Mode */
    .desktop-side {
        display: none;
        flex: 1;
        flex-direction: column;
        justify-content: center;
        padding: 80px;
        background: linear-gradient(to right, rgba(0,0,0,0.8), transparent);
    }

    @media (min-width: 1024px) {
        .desktop-side {
            display: flex;
        }
        .form-side {
            width: 450px !important;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }
    }

    .form-side {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 40px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(20px);
    }

    .login-container {
        width: 100%;
        max-width: 360px;
        text-align: center;
    }

    .desktop-side h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 20px;
        line-height: 1.2;
        text-shadow: 0 0 20px rgba(0, 210, 255, 0.3);
    }

    .desktop-side p:not(.stat-label) {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.6);
        max-width: 500px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .stats-container {
        display: flex;
        gap: 40px;
        margin-top: 20px;
    }

    .stat-item .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        font-family: 'Orbitron', sans-serif;
        text-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
    }

    .stat-item .stat-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.4);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-top: 5px;
    }

    @media (min-width: 1200px) {
        .desktop-side h2 { font-size: 45px; }
        .desktop-side p:not(.stat-label) { font-size: 20px; }
    }

    /* Original Login Headers */
    .login-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
    }


    .register-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-shadow: 0 0 20px rgba(0, 210, 255, 0.5); /* Blue glow */
    }

    @media (min-width: 1024px) {
        .register-header h2 { font-size: 36px; margin-bottom: 10px; }
    }

    .input-group {
        position: relative;
        margin-bottom: 15px;
        display: block !important;
    }

    @media (min-width: 1024px) {
        .input-group { margin-bottom: 20px; }
    }

    .field-label {
        display: block;
        text-align: left;
        font-family: 'Orbitron', sans-serif;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 8px;
        padding-left: 2px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .input-group i:not(.toggle-password) {
        position: absolute;
        left: 20px;
        bottom: 12px;
        color: #00d2ff;
        font-size: 18px;
        pointer-events: none;
    }

    .input-group input {
        width: 100%;
        padding: 12px 15px 12px 50px;
        background: rgba(0, 0, 0, 0.3); /* Dark background */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        transition: all 0.3s ease;
        display: block;
    }

    /* Prevent white background on autofill */
    .input-group input:-webkit-autofill,
    .input-group input:-webkit-autofill:hover, 
    .input-group input:-webkit-autofill:focus {
        -webkit-text-fill-color: #fff;
        -webkit-box-shadow: 0 0 0px 1000px rgba(0, 0, 0, 0.8) inset;
        transition: background-color 5000s ease-in-out 0s;
    }

    .input-group input:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #00d2ff; /* Blue border */
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
        outline: none;
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        background: linear-gradient(45deg, #00d2ff, #3a7bd5); /* Blue gradient */
        border: none;
        border-radius: 12px;
        color: #fff;
        font-weight: 700;
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        letter-spacing: 2px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        box-shadow: 0 8px 16px rgba(0, 210, 255, 0.3);
        font-size: 14px;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 210, 255, 0.5);
        opacity: 1;
        color: #fff;
    }

    .auth-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .forgot-pass {
        color: #00d2ff;
        text-decoration: none;
        font-weight: 600;
    }

    .forgot-pass:hover {
        color: #ff416c;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        bottom: 12px;
        cursor: pointer;
        color: rgba(255,255,255,0.3);
        z-index: 5;
        font-size: 18px;
    }

    .register-section {
        margin-top: 15px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.4);
        text-align: center;
    }

    .register-section a {
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.3s;
    }

    .register-section a:hover {
        color: #00d2ff;
    }

    @media (min-width: 1024px) {
        .register-section { margin-top: 25px; }
    }

    #preloder, .footer, .header {
        display: none !important;
    }

    .brand-glow {
        color: #fff;
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        font-size: 24px;
        margin-bottom: 8px;
        text-shadow: 0 0 30px #00d2ff;
    }

    @media (min-width: 1024px) {
        .brand-glow { font-size: 36px; margin-bottom: 20px; }
    }
    .btn-google {
        width: 100%;
        padding: 12px;
        background: #fff;
        border: none;
        border-radius: 12px;
        color: #000;
        font-weight: 700;
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 14px;
        box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
    }

    .btn-google:hover {
        background: #f1f1f1;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.2);
    }

    .btn-google img {
        width: 20px;
        height: 20px;
    }

    .or-divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 15px 0;
        color: rgba(255, 255, 255, 0.3);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .or-divider::before,
    .or-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .or-divider:not(:empty)::before { margin-right: 15px; }
    .or-divider:not(:empty)::after { margin-left: 15px; }
</style>
@endsection

@section('content')
<video id="video-bg" autoplay loop muted playsinline preload="auto" src="{{ asset('video/3e46bc5d17b2e798fb438a720f5fcd58.mp4') }}"></video>

<div class="login-wrapper">
    <!-- Desktop Exclusive Side -->
    <div class="desktop-side">
        <div class="mb-8">
            @if(isset($config->logo_header))
                <img src="{{ asset($config->logo_header) }}" alt="Logo" class="h-12 w-auto">
            @else
                <h1 class="brand-glow">{{ ENV('APP_NAME') }}</h1>
            @endif
        </div>
        <h2>Rasakan Masa Depan <br> Topup Game</h2>
        <p>Bergabunglah dengan ribuan gamer yang mempercayai kami untuk layanan top-up tercepat dan teraman. Fitur premium, pengiriman instan.</p>
        
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-value">100rb+</div>
                <div class="stat-label">Pengguna Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">24/7</div>
                <div class="stat-label">Tim Dukungan</div>
            </div>
        </div>
    </div>

    <!-- Form Side (Both Mobile & Desktop) -->
    <div class="form-side">
        <div class="login-container">
            <!-- Mobile Logo removed as per user request -->

            <div class="login-header">
                <h2>Masuk</h2>
                <p>Masuk dengan akun yang telah Anda daftarkan</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger mb-4" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #fff; border-radius: 12px;">
                    {{ session('error') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger mb-4" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #fff; border-radius: 12px;">
                    <ul class="m-0 p-0" style="list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label class="field-label">Username / Email</label>
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Masukkan Email atau Username" required value="{{ old('username') }}">
                </div>
                <div class="input-group">
                    <label class="field-label">Kata Sandi</label>
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required>
                    <i class="fa fa-eye toggle-password" data-target="password"></i>
                </div>

                <div class="auth-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        Ingat akun ku
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-pass">Lupa kata sandi mu?</a>
                </div>

                <button type="submit" class="btn-login">Mulai Sesi</button>
                
                <div class="or-divider">Atau</div>

                <button type="button" class="btn-google" id="btn-google-login">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    Masuk dengan Google
                </button>
            </form>


            <div class="register-section">
                Baru di platform kami? <a href="{{ route('register') }}">DAFTAR</a>
            </div>
        </div>
    </div>
</div>

<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>

<script>
    // Firebase configuration - REPLACE WITH YOUR PROJECT CONFIG
    const firebaseConfig = {
        apiKey: "YOUR_API_KEY",
        authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
        projectId: "YOUR_PROJECT_ID",
        storageBucket: "YOUR_PROJECT_ID.appspot.com",
        messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
        appId: "YOUR_APP_ID"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();
    const provider = new firebase.auth.GoogleAuthProvider();

    document.getElementById('btn-google-login').addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menghubungkan...';

        auth.signInWithPopup(provider)
            .then((result) => {
                const user = result.user;
                
                // Send to backend
                $.ajax({
                    url: "{{ route('firebase.google.login') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: user.email,
                        name: user.displayName,
                        uid: user.uid
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            alert('Gagal masuk: ' + (response.message || 'Terjadi kesalahan'));
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Gagal masuk ke server. Silakan coba lagi.');
                        location.reload();
                    }
                });
            })
            .catch((error) => {
                console.error(error);
                alert('Gagal menghubungkan ke Google: ' + error.message);
                this.disabled = false;
                this.innerHTML = '<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"> Masuk dengan Google';
            });
    });

    const bgVideo = document.getElementById('video-bg');
    function forcePlay() {
        if (bgVideo && bgVideo.paused) {
            bgVideo.play().catch(() => {});
        }
    }
    forcePlay();
    setTimeout(forcePlay, 500);
    document.addEventListener('click', forcePlay, { once: true });
</script>
@endsection
