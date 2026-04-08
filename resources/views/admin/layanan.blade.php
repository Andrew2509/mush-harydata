@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 mb-4 order-0">
    </div>
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

{{-- Page Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
    <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
        <i class="fas fa-box" style="color:#00f0ff;font-size:0.9rem;"></i> Manajemen Layanan
    </h4>
</div>

{{-- Action Buttons --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a href="javascript:;" onclick="modal('EditProfit', '{{ route('detail.produk.get', 1) }}')">
                <button type="button" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-money-bill-wave"></i> Update Profit
                </button>
            </a>
            <form method="POST" action="{{ route('sync.produk.get.post') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-sync-alt"></i> Sync Price Digiflazz
                </button>
            </form>
            <a href="{{ route('produk.get') }}">
                <button type="button" class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-download"></i> Get Product
                </button>
            </a>
        </div>
    </div>
</div>

{{-- Add Service Form --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-plus-circle" style="color:#00ff88;"></i>
        <span>Tambah Layanan</span>
    </div>
    <div class="card-body">
        <form id="addServiceForm" action="{{ route('layanan.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Layanan</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama" placeholder="Nama layanan">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Kategori</label>
                <div class="col-lg-10">
                    <select class="form-select" name="kategori">
                        <option>--Pilih Game--</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Provider</label>
                <div class="col-lg-10">
                    <select class="form-select" name="provider">
                        <option>--Pilih Provider--</option>
                        <option value="digiflazz">Digiflazz</option>
                        <option value="joki">Joki</option>
                        <option value="vilogml">ML Vilog</option>
                        <option value="giftskin">Gift Skin</option>
                    </select>
                </div>
            </div>

           <div class="mb-3 row">
    <label for="harga" class="col-lg-1 col-form-label">Harga</label>
    <div class="col-lg-5">
        <input type="number" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" name="harga" id="harga" placeholder="0">
        @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <label for="harga_member" class="col-lg-1 col-form-label">H.Member</label>
    <div class="col-lg-5">
        <input type="number" class="form-control @error('harga_member') is-invalid @enderror" value="{{ old('harga_member') }}" name="harga_member" id="harga_member" placeholder="0">
        @error('harga_member')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3 row">
    <label for="harga_platinum" class="col-lg-1 col-form-label">H.Platinum</label>
    <div class="col-lg-5">
        <input type="number" class="form-control @error('harga_platinum') is-invalid @enderror" value="{{ old('harga_platinum') }}" name="harga_platinum" id="harga_platinum" placeholder="0">
        @error('harga_platinum')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <label for="harga_gold" class="col-lg-1 col-form-label">H.Gold</label>
    <div class="col-lg-5">
        <input type="number" class="form-control @error('harga_gold') is-invalid @enderror" value="{{ old('harga_gold') }}" name="harga_gold" id="harga_gold" placeholder="0">
        @error('harga_gold')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

            <div class="mb-3 row">
                <label class="col-lg-1 col-form-label">Profit</label>
                <div class="col-lg-5">
                    <input type="number" class="form-control @error('profit') is-invalid @enderror" value="{{ old('profit') }}" name="profit" placeholder="0">
                    @error('profit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <label class="col-lg-1 col-form-label">P.Member</label>
                <div class="col-lg-5">
                    <input type="number" class="form-control @error('profit_member') is-invalid @enderror" value="{{ old('profit_member') }}" name="profit_member" placeholder="0">
                    @error('profit_member')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <label class="col-lg-1 col-form-label">P.Platinum</label>
                <div class="col-lg-5">
                    <input type="number" class="form-control @error('profit_platinum') is-invalid @enderror" value="{{ old('profit_platinum') }}" name="profit_platinum" placeholder="0">
                    @error('profit_platinum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <label class="col-lg-1 col-form-label">P.Gold</label>
                <div class="col-lg-5">
                    <input type="number" class="form-control @error('profit_gold') is-invalid @enderror" value="{{ old('profit_gold') }}" name="profit_gold" placeholder="0">
                    @error('profit_gold')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <label class="col-lg-1 col-form-label">PID</label>
                <div class="col-lg-5">
                    <input type="text" class="form-control @error('provider_id') is-invalid @enderror" value="{{ old('provider_id') }}" name="provider_id" placeholder="Provider ID">
                    @error('provider_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Flash Sale?</label>
                <div class="col-lg-10">
                    <select class="form-select" name="flash_sale">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Judul Flash Sale</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('judul_flash_sale') is-invalid @enderror" value="{{ old('judul_flash_sale') }}" name="judul_flash_sale" placeholder="Judul flash sale">
                    @error('judul_flash_sale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
<div class="mb-3 row">
    <label class="col-lg-2 col-form-label">Harga Flash Sale</label>
    <div class="col-lg-10">
        <input type="number" class="form-control @error('harga_flash_sale') is-invalid @enderror" value="0" name="harga_flash_sale" id="harga_flash_sale">
        @error('harga_flash_sale')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3 row">
    <label class="col-lg-2 col-form-label">Stock Flash Sale</label>
    <div class="col-lg-10">
        <input type="number" class="form-control @error('stock_flash_sale') is-invalid @enderror" value="0" name="stock_flash_sale" id="stock_flash_sale">
        @error('stock_flash_sale')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<script>
    document.querySelectorAll('input[type="number"]').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    });
</script>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Expired Flash Sale</label>
                <div class="col-lg-10">
                    <input type="datetime-local" class="form-control @error('expired_flash_sale') is-invalid @enderror" value="{{ old('expired_flash_sale') }}" name="expired_flash_sale">
                    @error('expired_flash_sale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="button" class="btn btn-primary" onclick="confirmAddService()" style="display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-save"></i> Submit
            </button>
        </form>
    </div>
</div>

{{-- Service Table --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-list" style="color:#00f0ff;"></i>
                <span>Semua Layanan</span>
            </div>
            <div class="card-body">
                <div id="loadingSpinnerLayanan" class="text-center" style="padding:3rem 0;">
                    <div class="spinner-border" role="status" style="width:2.5rem;height:2.5rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="color:#5a6380;margin-top:0.8rem;font-size:0.85rem;">Memuat data layanan...</p>
                </div>
                <div class="table-responsive d-none" id="layananTableContainer">
                    <table class="table table-dark table-bordered table-striped m-0" id="layanan-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Layanan</th>
                                <th>Provider</th>
                                <th>PID</th>
                                <th>Harga</th>
                                <th>H.Member</th>
                                <th>H.Platinum</th>
                                <th>H.Gold</th>
                                <th>Profit</th>
                                <th>P.Member</th>
                                <th>P.Platinum</th>
                                <th>P.Gold</th>
                                <th>H.Flash</th>
                                <th>Flash?</th>
                                <th>Judul FS</th>
                                <th>Exp FS</th>
                                <th>Status</th>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datas as $data)
                            @php
                            $label_pesanan = '';
                            if($data->status == "available"){
                                $label_pesanan = 'info';
                            }else if($data->status == "unavailable"){
                                $label_pesanan = 'warning';
                            }
                            @endphp
                            <tr>
                                <th scope="row">{{ $data->id }}</th>
                                <td>{{ $data->nama_kategori }}</td>
                                <td>{{ $data->layanan }}</td>
                                <td><small>{{ $data->provider }}</small></td>
                                <td><code style="color:#00f0ff;background:rgba(0,240,255,0.08);padding:2px 4px;border-radius:4px;font-size:0.75rem;">{{ $data->provider_id }}</code></td>
                                <td style="white-space:nowrap;">Rp. {{ number_format($data->harga, 0, '.', ',') }}</td>
                                <td style="white-space:nowrap;">Rp. {{ number_format($data->harga_member, 0, '.', ',') }}</td>
                                <td style="white-space:nowrap;">Rp. {{ number_format($data->harga_platinum, 0, '.', ',') }}</td>
                                <td style="white-space:nowrap;">Rp. {{ number_format($data->harga_gold, 0, '.', ',') }}</td>
                                <td>{{ number_format($data->profit, 0, '.', ',') }}%</td>
                                <td>{{ number_format($data->profit_member, 0, '.', ',') }}%</td>
                                <td>{{ number_format($data->profit_platinum, 0, '.', ',') }}%</td>
                                <td>{{ number_format($data->profit_gold, 0, '.', ',') }}%</td>
                                <td style="white-space:nowrap;">Rp. {{ number_format($data->harga_flash_sale, 0, '.', ',') }}</td>
                                <td>
                                    @if($data->is_flash_sale == 0)
                                        <span style="color:#ff3366;font-size:0.75rem;">No</span>
                                    @else
                                        <span style="color:#00ff88;font-size:0.75rem;">Yes</span>
                                    @endif
                                </td>
                                <td><small>{{$data->judul_flash_sale}}</small></td>
                                <td><small>{{$data->expired_flash_sale}}</small></td>
                                <td>
                                    <div class="btn-group-vertical">
                                        <button type="button" class="btn btn-sm btn-{{$label_pesanan}} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> {{ $data->status }} </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;" onclick="changeStatus('{{ $data->id }}', 'available')">available</a></li>
                                            <li><a class="dropdown-item" href="javascript:;" onclick="changeStatus('{{ $data->id }}', 'unavailable')">unavailable</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td style="white-space:nowrap;">
                                    <a href="javascript:;" onclick="modal('{{ $data->layanan }}', '{{ route('layanan.detail', [$data->id]) }}')" class="btn btn-sm btn-info mb-1">Edit</a>
                                    <a href="javascript:;" onclick="confirmDelete('{{ $data->id }}')" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                                <td><small>{{ $data->created_at }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#loadingSpinnerLayanan').addClass('d-none');
        $('#layananTableContainer').removeClass('d-none');
        $('#layanan-table').DataTable();
    }, 500);
});
</script>

<!-- Modal -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modal-detail-title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-detail-title">Detail Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-detail-body"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    function confirmAddService() {
        Swal.fire({
            title: 'Tambah Layanan',
            text: "Apakah Anda yakin ingin menambah layanan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tambahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('addServiceForm').submit();
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Layanan',
            text: "Apakah Anda yakin ingin menghapus layanan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/layanan/hapus/' + id;
            }
        });
    }

    function changeStatus(id, status) {
        Swal.fire({
            title: 'Ubah Status Layanan',
            text: "Apakah Anda yakin ingin mengubah status layanan ini menjadi " + status + "?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/layanan-status/' + id + '/' + status;
            }
        });
    }

    function modal(name, link) {
        var myModal = new bootstrap.Modal($('#modal-detail'))
        $.ajax({
            type: "GET",
            url: link,
            beforeSend: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('<div class="text-center py-4"><div class="spinner-border" role="status" style="color:#00f0ff;"></div></div>');
            },
            success: function(result) {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html(result);
            },
            error: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('<div class="text-center py-3" style="color:#ff3366;">Terjadi kesalahan saat memuat data.</div>');
            }
        });
        myModal.show();
    }
</script>
</div>

@endsection
