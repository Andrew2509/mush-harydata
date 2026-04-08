@extends('layouts.user')

@section('custom_style')
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

    .reset-wrapper {
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

    .desktop-side h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 20px;
        line-height: 1.2;
        text-shadow: 0 0 20px rgba(0, 210, 255, 0.3);
    }

    .desktop-side p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.6);
        max-width: 500px;
        line-height: 1.6;
    }

    @media (min-width: 1200px) {
        .desktop-side h2 { font-size: 45px; }
        .desktop-side p { font-size: 20px; }
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

    .reset-container {
        width: 100%;
        max-width: 360px;
        text-align: center;
    }

    .reset-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-shadow: 0 0 20px rgba(0, 210, 255, 0.5);
    }

    @media (min-width: 1024px) {
        .reset-header h2 { font-size: 32px; margin-bottom: 8px; }
    }

    .reset-header p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 25px;
    }

    .input-group {
        position: relative;
        margin-bottom: 15px;
        display: block !important;
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
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #fff;
        font-size: 14px;
        transition: all 0.3s ease;
        display: block;
    }

    .input-group input:-webkit-autofill {
        -webkit-text-fill-color: #fff;
        -webkit-box-shadow: 0 0 0px 1000px rgba(0, 0, 0, 0.8) inset;
        transition: background-color 5000s ease-in-out 0s;
    }

    .input-group input:focus {
        background: rgba(255, 255, 255, 0.08);
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

    .btn-action {
        width: 100%;
        padding: 12px;
        background: linear-gradient(45deg, #00d2ff, #3a7bd5);
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

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0, 210, 255, 0.5);
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

    #preloder, .footer, .header {
        display: none !important;
    }
</style>
@endsection

@section('content')
<video id="video-bg" autoplay loop muted playsinline preload="auto"
    src="{{ asset('video/3e46bc5d17b2e798fb438a720f5fcd58.mp4') }}">
</video>

<div class="reset-wrapper">
    <!-- Desktop Side -->
    <div class="desktop-side">
        <div class="mb-8">
            @if(isset($config->logo_header))
                <img src="{{ asset($config->logo_header) }}" alt="Logo" class="h-12 w-auto">
            @else
                <h1 class="brand-glow">{{ ENV('APP_NAME') }}</h1>
            @endif
        </div>
        <h2>Keamanan Akun <br> Adalah Prioritas</h2>
        <p>Amankan kembali akun Anda dengan membuat kata sandi baru yang kuat dan unik.</p>
    </div>

    <!-- Form Side -->
    <div class="form-side">
        <div class="reset-container">
            <div class="reset-header">
                <h2>Atur Ulang</h2>
                <p>Silakan buat password baru untuk akun: <br><strong>{{ $username }}</strong></p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #fff; border-radius: 12px;">
                    <ul style="margin: 0; padding: 10px 15px; text-align: left; font-size: 13px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="username" value="{{ $username }}">

                <div class="input-group">
                    <label class="field-label">Password Baru</label>
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Min. 8 Karakter" required minlength="8">
                    <i class="fa fa-eye toggle-password" data-target="password"></i>
                </div>
                <div class="input-group">
                    <label class="field-label">Konfirmasi Password</label>
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi Password" required minlength="8">
                    <i class="fa fa-eye toggle-password" data-target="password_confirmation"></i>
                </div>
                <button type="submit" class="btn-action">Update Password</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
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
@endsection
