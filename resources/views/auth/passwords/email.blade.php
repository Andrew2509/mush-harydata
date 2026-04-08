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

    .recovery-wrapper {
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

    .recovery-container {
        width: 100%;
        max-width: 360px;
        text-align: center;
    }

    .recovery-header h2 {
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
        .recovery-header h2 { font-size: 32px; margin-bottom: 8px; }
    }

    .recovery-header p {
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

    .input-group i {
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

    .back-link {
        margin-top: 20px;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.4);
        text-align: center;
    }

    .back-link a {
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.3s;
    }

    .back-link a:hover {
        color: #00d2ff;
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

<div class="recovery-wrapper">
    <!-- Desktop Side -->
    <div class="desktop-side">
        <div class="mb-8">
            @if(isset($config->logo_header))
                <img src="{{ asset($config->logo_header) }}" alt="Logo" class="h-12 w-auto">
            @else
                <h1 class="brand-glow">{{ ENV('APP_NAME') }}</h1>
            @endif
        </div>
        <h2>Jangan Kehilangan <br> Akses Akun Anda</h2>
        <p>Pulihkan akun Anda dengan cepat menggunakan verifikasi OTP WhatsApp yang instan dan aman.</p>
    </div>

    <!-- Form Side -->
    <div class="form-side">
        <div class="recovery-container">
            <div class="recovery-header">
                <h2>Pulihkan Akun</h2>
                <p>Masukkan info WhatsApp Anda untuk reset.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger mb-4" style="background: rgba(220, 53, 69, 0.2); border: 1px solid rgba(220, 53, 69, 0.5); color: #fff; border-radius: 12px; font-size: 13px;">
                    {{ session('error') }}
                </div>
            @endif

            <div id="otp-form">
                <div class="input-group">
                    <label class="field-label">Nomor WhatsApp</label>
                    <i class="fab fa-whatsapp"></i>
                    <input type="text" id="no_wa" name="no_wa" placeholder="Contoh: 62812..." required value="{{ old('no_wa') }}">
                </div>
                <button type="button" id="btn-send-otp" class="btn-action">Kirim OTP</button>

                <div id="otp-section" style="display: none; margin-top: 15px;">
                    <div class="input-group">
                        <label class="field-label">Kode Verifikasi</label>
                        <i class="fa fa-key"></i>
                        <input type="text" id="otp_code" placeholder="6 Digit OTP" maxlength="6">
                    </div>
                    <button type="button" id="btn-verify-otp" class="btn-action" style="background: linear-gradient(45deg, #00b09b, #96c93d);">Verifikasi & Reset</button>
                </div>
            </div>

            <div class="back-link">
                Teringat kembali? <a href="{{ route('login') }}">MASUK</a>
            </div>
        </div>
    </div>
</div>

    <script>
        document.getElementById('btn-send-otp').addEventListener('click', function() {
            const phoneNumber = document.getElementById('no_wa').value;
            if (!phoneNumber) {
                alert('Silakan masukkan nomor WhatsApp');
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.innerText = 'Mengirim...';

            $.ajax({
                url: "{{ route('password.send_otp') }}",
                method: "POST",
                data: { 
                    _token: "{{ csrf_token() }}",
                    phone: phoneNumber 
                },
                success: function(response) {
                    if (response.success) {
                        document.getElementById('otp-section').style.display = 'block';
                        btn.innerText = 'Kirim Ulang';
                        alert('Kode OTP telah dikirim melalui WhatsApp ke nomor ' + phoneNumber);
                    } else {
                        alert('Gagal: ' + response.message);
                    }
                    btn.disabled = false;
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengirim OTP.');
                    btn.disabled = false;
                    btn.innerText = 'Kirim OTP';
                }
            });
        });

        document.getElementById('btn-verify-otp').addEventListener('click', function() {
            const code = document.getElementById('otp_code').value;
            if (!code) {
                alert('Silakan masukkan kode OTP');
                return;
            }

            const btn = this;
            btn.disabled = true;
            btn.innerText = 'Memverifikasi...';

            $.ajax({
                url: "{{ route('password.verify_otp') }}",
                method: "POST",
                data: { 
                    _token: "{{ csrf_token() }}",
                    otp: code 
                },
                success: function(response) {
                    if (response.success) {
                        alert('Nomor berhasil diverifikasi! Anda akan diarahkan ke halaman reset password.');
                        window.location.href = "{{ route('password.reset.otp') }}";
                    } else {
                        alert('Gagal: ' + response.message);
                        btn.disabled = false;
                        btn.innerText = 'Verifikasi & Reset';
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat verifikasi OTP.');
                    btn.disabled = false;
                    btn.innerText = 'Verifikasi & Reset';
                }
            });
        });
    </script>
</div>
@endsection
