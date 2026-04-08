@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0;padding-left:1.2rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Page Header --}}
<div style="display:flex;align-items:center;gap:8px;margin-bottom:1.5rem;">
    <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
        <i class="fas fa-cog" style="color:#00f0ff;font-size:0.9rem;"></i> Pengaturan Website
    </h4>
</div>

{{-- Website Config --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-globe" style="color:#00f0ff;"></i>
        <span>Konfigurasi Website</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/web') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Judul Website</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" value="{{$web->judul_web}}" name="judul_web">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Deskripsi Website</label>
                    <div class="col-lg-10">
                        <textarea class="form-control" name="deskripsi_web">{{$web->deskripsi_web}}</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Logo Header</label>
                    <div class="col-lg-10">
                    <img width="80" src="{{ asset($web->logo_header) }}" alt="" style="border-radius:8px;border:1px solid rgba(0,240,255,0.15);margin-bottom:8px;display:block;">
                        <input type="file" class="form-control" name="logo_header">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Logo Footer</label>
                    <div class="col-lg-10">
                    <img width="80" src="{{ asset($web->logo_footer) }}" alt="" style="border-radius:8px;border:1px solid rgba(0,240,255,0.15);margin-bottom:8px;display:block;">
                        <input type="file" class="form-control" name="logo_footer">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">Logo Favicon</label>
                    <div class="col-lg-10">
                    <img width="40" src="{{ asset($web->logo_favicon) }}" alt="" style="border-radius:6px;border:1px solid rgba(0,240,255,0.15);margin-bottom:8px;display:block;">
                        <input type="file" class="form-control" name="logo_favicon">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">URL WA</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" value="{{$web->url_wa}}" name="url_wa">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">URL IG</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" value="{{$web->url_ig}}" name="url_ig">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">URL TikTok</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" value="{{$web->url_tiktok}}" name="url_tiktok" >
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">URL Youtube</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" value="{{$web->url_youtube}}" name="url_youtube" >
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">URL FB</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" value="{{$web->url_fb}}" name="url_fb" >
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-2 col-form-label">( Check Game )</label>
                    <div class="col-lg-10">
                        <input type="password" class="form-control" value="{{$web->topupindo_api}}" name="topupindo_api" >
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-save"></i> Submit
            </button>
        </form>
    </div>
</div>

{{-- Warna Website --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-palette" style="color:#ff00e5;"></i>
        <span>Konfigurasi Warna Website</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/warnaweb') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([['Warna 1','colorbackground','warna1',$web->warna1], ['Warna 2','colorInputmel','warna2',$web->warna2], ['Warna 3','colorInputtih','warna3',$web->warna3], ['Warna 4','colorInputt','warna4',$web->warna4]] as $c)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $c[0] }}</label>
                <div class="col-lg-10">
                    <input type="color" class="form-control" id="{{ $c[1] }}" value="{{ $c[3] }}" name="{{ $c[2] }}" style="height:42px;">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-save"></i> Submit
            </button>
        </form>
    </div>
</div>

{{-- Tripay --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-credit-card" style="color:#00ff88;"></i>
        <span>Konfigurasi Tripay</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/tripay') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([['TRIPAY API','tripay_api',$web->tripay_api], ['TRIPAY MERCHANT CODE','tripay_merchant_code',$web->tripay_merchant_code], ['TRIPAY PRIVATE KEY','tripay_private_key',$web->tripay_private_key]] as $f)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $f[0] }}</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $f[2] }}" name="{{ $f[1] }}">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Tokopay --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-wallet" style="color:#eef70b;"></i>
        <span>Konfigurasi Tokopay</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/tokopay') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([['Tokopay Merchant ID','tokopay_merchant_id',$web->tokopay_merchant_id], ['Tokopay Secret Key','tokopay_secret_key',$web->tokopay_secret_key]] as $f)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $f[0] }}</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $f[2] }}" name="{{ $f[1] }}">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Paydisini --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-money-bill-wave" style="color:#00bcd4;"></i>
        <span>Konfigurasi Paydisini</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/paydisini') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Paydisini Apikey</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->paydisini}}" name="paydisini">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Digiflazz --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-bolt" style="color:#ff8800;"></i>
        <span>Konfigurasi Digiflazz</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/digiflazz') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([['USERNAME DIGI','username_digi',$web->username_digi], ['API KEY DIGI','api_key_digi',$web->api_key_digi]] as $f)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $f[0] }}</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $f[2] }}" name="{{ $f[1] }}">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Bangjeff --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-key" style="color:#ff3366;"></i>
        <span>Konfigurasi Bangjeff API</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/bangjeff') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Api Key</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->apikey_bangjeff}}" name="apikey_bangjeff">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Aoshi --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-shield-alt" style="color:#9c27b0;"></i>
        <span>Konfigurasi Aoshi API</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/aoshi') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Api Key</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->apikey_aoshi}}" name="apikey_aoshi">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Mobilegamestore --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-mobile-alt" style="color:#4caf50;"></i>
        <span>Konfigurasi Mobilegamestore API</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/mobilegamestore') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Api Key Mobilegamestore</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->api_mobilegamestore}}" name="api_mobilegamestore">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- ApiGames --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-gamepad" style="color:#2196f3;"></i>
        <span>Konfigurasi ApiGames</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/apigames') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([['APIGAMES SECRET','apigames_secret',$web->apigames_secret], ['APIGAMES MERCHANT','apigames_merchant',$web->apigames_merchant]] as $f)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $f[0] }}</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $f[2] }}" name="{{ $f[1] }}">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- VIP Reseller --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-crown" style="color:#ffc107;"></i>
        <span>Konfigurasi VIP Reseller</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/vip') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([['VIP APIID','vip_apiid',$web->vip_apiid], ['VIP APIKEY','vip_apikey',$web->vip_apikey]] as $f)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $f[0] }}</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $f[2] }}" name="{{ $f[1] }}">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- WA Gateway --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fab fa-whatsapp" style="color:#25d366;"></i>
        <span>Konfigurasi WA Gateway</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/wagateway') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">NOMOR ADMIN</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->nomor_admin}}" name="nomor_admin" id="nomor">
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">WA KEY</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->wa_key}}" name="wa_key">
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">WA NUMBER</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{$web->wa_number}}" name="wa_number" id="nomor">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Mutasi E-wallet --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-exchange-alt" style="color:#e91e63;"></i>
        <span>Konfigurasi Mutasi E-wallet / Bank</span>
    </div>
    <div class="card-body">
        <form action="{{ url('/setting/mutasi') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @foreach([
                ['OVO ADMIN','ovo_admin',$web->ovo_admin],
                ['OVO1 ADMIN','ovo1_admin',$web->ovo1_admin],
                ['GOPAY ADMIN','gopay_admin',$web->gopay_admin],
                ['GOPAY1 ADMIN','gopay1_admin',$web->gopay1_admin],
                ['DANA ADMIN','dana_admin',$web->dana_admin],
                ['SHOPEEPAY ADMIN','shopeepay_admin',$web->shopeepay_admin],
                ['BCA ADMIN','bca_admin',$web->bca_admin]
            ] as $f)
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">{{ $f[0] }}</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $f[2] }}" name="{{ $f[1] }}">
                </div>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i class="fas fa-save"></i> Submit</button>
        </form>
    </div>
</div>

<script>
    const nomorInputs = document.querySelectorAll("#nomor");
    nomorInputs.forEach(input => {
        input.addEventListener("input", function () {
            let nomor = this.value;
            if (nomor.startsWith("08")) {
                nomor = "62" + nomor.slice(1);
                this.value = nomor;
            }
        });
    });
</script>
</div>
@endsection
