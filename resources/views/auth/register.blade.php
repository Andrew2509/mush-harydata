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

    .register-wrapper {
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
            width: 550px !important;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }
    }

    .form-side {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 30px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(20px);
    }

    .register-container {
        width: 100%;
        max-width: 500px;
        text-align: center;
    }

    .register-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-shadow: 0 0 20px rgba(0, 210, 255, 0.5);
    }

    @media (min-width: 1024px) {
        .register-header h2 { font-size: 36px; margin-bottom: 10px; }
    }

    .register-header p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 15px;
    }

    @media (min-width: 1024px) {
        .register-header p { font-size: 16px; margin-bottom: 25px; }
    }

    .row-input {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    @media (max-width: 480px) {
        .row-input { flex-direction: column; gap: 15px; }
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

    .input-group {
        position: relative;
        flex: 1;
        margin-bottom: 0;
        display: block !important;
    }

    .input-group i:not(.toggle-password) {
        position: absolute;
        left: 15px;
        bottom: 12px; /* Fixed distance from bottom of input */
        color: #00d2ff;
        font-size: 18px;
        pointer-events: none;
        z-index: 5;
    }

    .input-group input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        background: rgba(0, 0, 0, 0.4); /* Deep dark background */
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
        transition: background-color 500s ease-in-out 0s;
    }

    .input-group input:focus {
        background: rgba(255, 255, 255, 0.08); /* Darker focus bg, no solid blue */
        border-color: #00d2ff;
        box-shadow: 0 0 15px rgba(0, 210, 255, 0.3);
        outline: none;
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

    .btn-otp {
        background: #00d2ff;
        color: #000;
        border: none;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        padding: 0 15px;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
        height: 42px;
    }

    .btn-otp:hover {
        background: #fff;
        box-shadow: 0 0 15px #00d2ff;
    }

    .btn-register {
        width: 100%;
        padding: 12px;
        background: linear-gradient(45deg, #00d2ff, #3a7bd5);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-weight: 700;
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        box-shadow: 0 8px 16px rgba(0, 210, 255, 0.2);
    }

    .btn-register:hover:not([disabled]) {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(0, 210, 255, 0.4);
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        text-align: left;
        margin-top: 10px;
        gap: 10px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.5);
    }

    .checkbox-group a {
        color: #00d2ff;
        text-decoration: none;
    }

    .login-link {
        margin-top: 15px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.4);
    }

    .login-link a {
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.3s;
    }

    .login-link a:hover {
        color: #00d2ff;
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
        text-shadow: 0 0 30px #ff416c;
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
        margin: 15px 0;
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

<div class="register-wrapper">
    <!-- Desktop Exclusive Side -->
    <div class="desktop-side">
        <div class="mb-8">
            @if(isset($config->logo_header))
                <img src="{{ asset($config->logo_header) }}" alt="Logo" class="h-12 w-auto">
            @else
                <h1 class="brand-glow">{{ ENV('APP_NAME') }}</h1>
            @endif
        </div>
        <h2>Bergabung dengan <br> Komunitas Elit Gamer</h2>
        <p>Buat akun Anda sekarang dan buka hadiah eksklusif, transaksi secepat kilat, dan dukungan premium 24/7.</p>
        
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-value">Instan</div>
                <div class="stat-label">Pengiriman</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">Aman</div>
                <div class="stat-label">Gateway</div>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="form-side">
        <div class="register-container">
            <!-- Mobile Logo removed as per user request -->

            <div class="register-header">
                <h2>Daftar</h2>
                <p>Masukkan informasi pendaftaran yang valid.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-3 p-2" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #fff; border-radius: 10px; font-size: 13px;">
                    <ul class="m-0 p-0" style="list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="row-input">
                    <div class="input-group">
                        <label class="field-label">Nama Lengkap</label>
                        <i class="fa fa-user"></i>
                        <input type="text" name="nama" placeholder="Contoh: John Doe" required value="{{ old('nama') }}">
                    </div>
                    <div class="input-group">
                        <label class="field-label">Username</label>
                        <i class="fa fa-at"></i>
                        <input type="text" name="username" placeholder="Username" required value="{{ old('username') }}">
                    </div>
                </div>

                <!-- Row 2: Alamat Email -->
                <div class="input-group" style="margin-bottom: 15px;">
                    <label class="field-label">Alamat Email</label>
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" placeholder="email@gmail.com" required value="{{ old('email') }}">
                </div>

                <!-- Row 3: Nomor Telepon (OTP) -->
                <div class="input-group" style="margin-bottom: 15px;">
                    <label class="field-label">Nomor WhatsApp</label>
                    <div style="display: flex; gap: 8px;">
                        <div style="position: relative; flex: 1;">
                            <i class="fa fa-whatsapp" style="position: absolute; left: 15px; bottom: 12px; color: #00d2ff; font-size: 18px; pointer-events: none; z-index: 5;"></i>
                            <input type="number" name="no_wa" id="no_wa" placeholder="628xxxx" required value="{{ old('no_wa') }}" style="padding-left: 45px;">
                        </div>
                        <button type="button" id="btn-send-otp" class="btn-otp">Kirim OTP</button>
                    </div>
                </div>

                <div id="otp-section" style="display: none; margin-bottom: 15px;">
                    <div class="input-group">
                        <label class="field-label">Kode Verifikasi</label>
                        <div style="display: flex; gap: 8px; position: relative;">
                            <div style="position: relative; flex: 1;">
                                <i class="fa fa-key" style="bottom: 12px; top: auto; transform: none; left: 15px;"></i>
                                <input type="text" id="otp_code" placeholder="Kode OTP" style="padding-left: 45px;">
                            </div>
                            <button type="button" id="btn-verify-otp" class="btn-otp" style="background: #28a745; color: #fff;">Verifikasi</button>
                        </div>
                    </div>
                </div>

                <div class="row-input">
                    <div class="input-group">
                        <label class="field-label">Kata Sandi</label>
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Min. 8 Karakter" required minlength="8">
                        <i class="fa fa-eye toggle-password" data-target="password"></i>
                    </div>
                    <div class="input-group">
                        <label class="field-label">Konfirmasi</label>
                        <i class="fa fa-shield"></i>
                        <input type="password" name="passwordd" id="passwordd" placeholder="Ulangi Sandi" required minlength="8">
                        <i class="fa fa-eye toggle-password" data-target="passwordd"></i>
                    </div>
                </div>

                <div style="color: rgba(255,255,255,0.6); font-size: 13px; margin-bottom: 15px; text-align: left; display: flex; align-items: flex-start; gap: 8px;">
                    <input type="checkbox" name="tac" id="tac" required style="margin-top: 3px;">
                    <label for="tac">Saya setuju dengan <a href="/id/terms-and-condition" style="color: #00d2ff; text-decoration: underline;">syarat dan ketentuan</a> dan <a href="/id/privacy-policy" style="color: #00d2ff; text-decoration: underline;">kebijakan pribadi</a>.</label>
                </div>

                <input type="hidden" name="verified" id="is_verified" value="0">
                <button type="submit" id="btn-submit-reg" class="btn-register" disabled style="opacity: 0.5; cursor: not-allowed; margin-top: 5px;">DAFTAR</button>
                
                <div class="or-divider">Atau</div>

                <button type="button" class="btn-google" id="btn-google-login">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
                    Daftar dengan Google
                </button>
            </form>

            <div class="login-link">
                Sudah Memiliki Akun? <a href="{{ route('login') }}">MASUK</a>
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
                            alert('Gagal daftar: ' + (response.message || 'Terjadi kesalahan'));
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Gagal daftar ke server. Silakan coba lagi.');
                        location.reload();
                    }
                });
            })
            .catch((error) => {
                console.error(error);
                alert('Gagal menghubungkan ke Google: ' + error.message);
                this.disabled = false;
                this.innerHTML = '<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"> Daftar dengan Google';
            });
    });

    const bgVideo = document.getElementById('video-bg');
    if (bgVideo) {
        bgVideo.play().catch(() => {});
        document.addEventListener('click', () => bgVideo.play().catch(() => {}), { once: true });
    }

    // Existing OTP Logic preserved
    document.getElementById('btn-send-otp').addEventListener('click', function() {
        const phoneNumber = document.getElementById('no_wa').value;
        if (!phoneNumber) { alert('Silakan masukkan nomor WhatsApp'); return; }

        const btn = this;
        btn.disabled = true;
        btn.innerText = 'Mengirim...';

        $.ajax({
            url: "{{ route('send.otp') }}",
            method: "POST",
            data: { phone: phoneNumber },
            success: function(response) {
                if (response.success) {
                    document.getElementById('otp-section').style.display = 'block';
                    btn.innerText = 'Sudah Terverifikasi';
                    alert('OTP dikirim ke ' + phoneNumber);
                } else {
                    alert('Gagal: ' + response.message);
                }
                btn.disabled = false;
            }
        });
    });

    document.getElementById('btn-verify-otp').addEventListener('click', function() {
        const code = document.getElementById('otp_code').value;
        if (!code) { alert('Masukkan kode OTP'); return; }

        const btn = this;
        btn.disabled = true;
        btn.innerText = 'Memeriksa...';

        $.ajax({
            url: "{{ route('verify.otp') }}",
            method: "POST",
            data: { otp: code },
            success: function(response) {
                if (response.success) {
                    alert('Terverifikasi!');
                    document.getElementById('is_verified').value = '1';
                    document.getElementById('btn-submit-reg').disabled = false;
                    document.getElementById('btn-submit-reg').style.opacity = '1';
                    document.getElementById('btn-submit-reg').style.cursor = 'pointer';
                    document.getElementById('otp-section').style.display = 'none';
                    document.getElementById('btn-send-otp').disabled = true;
                    document.getElementById('btn-send-otp').innerText = 'Verified';
                    document.getElementById('no_wa').readOnly = true;
                } else {
                    alert('Gagal: ' + response.message);
                }
                btn.disabled = false;
                btn.innerText = 'Verifikasi';
            }
        });
    });
</script>
@endsection
