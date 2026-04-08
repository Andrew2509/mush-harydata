@extends('layouts.admin')

@section('content')
<Style>
#summernote .note-editable {
  background-color: rgba(15, 20, 40, 0.8) !important;
  color: #e8eaf6 !important;
}
</Style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 mb-4 order-0">
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-tags" style="color:#00f0ff;font-size:0.9rem;"></i> Manajemen Kategori
        </h4>
    </div>

{{-- Tambah Kategori --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-plus-circle" style="color:#00ff88;"></i>
        <span>Tambah Kategori</span>
    </div>
    <div class="card-body">
        <form action="{{ route('kategori.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Nama</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" name="nama" placeholder="Nama kategori">
                    @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Sub Nama (brand)</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('sub_nama') is-invalid @enderror" value="{{ old('sub_nama') }}" name="sub_nama" placeholder="Sub nama / brand">
                    @error('sub_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Url</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" name="kode" placeholder="URL slug">
                    @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Deskripsi Game</label>
                <div class="col-lg-10">
                    <textarea id="summernote" class="form-control @error('deskripsi_game') is-invalid @enderror" name="deskripsi_game">{{ old('deskripsi_game') }}</textarea>
                    @error('deskripsi_game')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Deskripsi field</label>
                <div class="col-lg-10">
                    <textarea class="form-control @error('deskripsi_field') is-invalid @enderror" name="deskripsi_field" placeholder="Deskripsi field input">{{ old('deskripsi_field') }}</textarea>
                    @error('deskripsi_field')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div id="fieldsContainer" style="padding:1rem;border:1px solid rgba(0,240,255,0.12);border-radius:12px;margin-bottom:1rem;">
                <div class="field-group">
                    <div style="color:#00f0ff;font-family:'Orbitron',sans-serif;font-size:0.75rem;margin-bottom:8px;">Field 1</div>
                    <div class="mt-2">
                        <input class="form-control" placeholder="Title" name="inputs_1_title">
                    </div>
                    <div class="mt-3">
                        <input class="form-control" placeholder="Name" name="inputs_1_name">
                    </div>
                    <div class="mt-3 mb-3">
                        <select class="form-select" name="inputs_1_type">
                            <option>Select Input Type</option>
                            <option value="text">Text</option>
                            <option value="number">Number</option>
                            <option value="password">Password</option>
                            <option value="select">Select</option>
                        </select>
                    </div>
                </div>
            </div>
            <button id="addMoreBtn" class="btn btn-sm btn-secondary mb-3" type="button" style="display:inline-flex;align-items:center;gap:5px;">
                <i class="fas fa-plus"></i> Add More
            </button>
            
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Thumbnail</label>
                <div class="col-lg-10">
                    <input type="file" class="form-control" name="thumbnail">
                    @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Banner</label>
                <div class="col-lg-10">
                    <input type="file" class="form-control" name="banner">
                    @error('banner')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label">Tipe</label>
                <div class="col-lg-10">
                    <select class="form-select" name="tipe">                
                        <option value='populer'>Populer</option>
                        <option value='game'>Topup Game</option>
                        <option value='e-money'>E-Money</option>
                        <option value='app'>App Premium</option>
                        <option value='voucher'>Voucher</option>
                        <option value='joki'>JOKI MLBB</option>
                        <option value='jokigendong'>JOKI Gendong</option>
                        <option value='giftskin'>Gift Skins</option>
                        <option value='vilogml'>ML Vilog</option>
                        <option value='streamingapp'>Streaming APP</option>
                    </select>
                    @error('tipe')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
                <i class="fas fa-save"></i> Submit
            </button>
        </form>
    </div>
</div>

{{-- Kategori Table --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-list" style="color:#00f0ff;"></i>
                <span>Semua Kategori</span>
            </div>
            <div class="card-body">
                <div id="loadingSpinnerKategori" class="text-center" style="padding:3rem 0;">
                    <div class="spinner-border" role="status" style="width:2.5rem;height:2.5rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="color:#5a6380;margin-top:0.8rem;font-size:0.85rem;">Memuat data kategori...</p>
                </div>
                <div class="table-responsive d-none" id="kategoriTableContainer">
                    <table class="table table-dark table-bordered table-striped m-0" id="kategori-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Thumbnail</th>
                                <th>Nama</th>
                                <th>Sub Nama</th>
                                <th>Kode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $data as $datas )
                            @php
                            $label_pesanan = '';
                            if($datas->status == "active"){
                            $label_pesanan = 'info';
                            }else if($datas->status == "unactive"){
                            $label_pesanan = 'warning';
                            }
                            @endphp
                            <tr>
                                <th scope="row">{{ $datas->id }}</th>
                                <td>
                                    <img src="{{ asset($datas->thumbnail) }}" width="50" height="50" style="border-radius: 10px;border:1px solid rgba(0,240,255,0.15);">
                                </td>
                                <td>{{ $datas->nama }}</td>
                                <td>{{ $datas->sub_nama }}</td>
                                <td><code style="color:#00f0ff;background:rgba(0,240,255,0.08);padding:2px 6px;border-radius:4px;">{{ $datas->kode }}</code></td>
                                <td>
                                    <div class="btn-group-vertical">
                                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-{{$label_pesanan}} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> {{ $datas->status }} <i class="mdi mdi-chevron-down"></i> </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="/kategori-status/{{ $datas->id }}/unactive">unactive</a></li>
                                            <li><a class="dropdown-item" href="/kategori-status/{{ $datas->id }}/active">active</a></li>
                                    </div>
                                </td>
                                <td style="white-space:nowrap;">
                                    <a href="javascript:;" onclick="modal('{{ $datas->nama }}', '{{ route('kategori.detail', [$datas->id]) }}')" class="btn btn-sm btn-info mb-1">Edit</a>
                                    <a class="btn btn-sm btn-danger" href="/kategori/hapus/{{ $datas->id }}">Hapus</a>
                                </td>
                                <td><small>{{ $datas->created_at }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#loadingSpinnerKategori').addClass('d-none');
        $('#kategoriTableContainer').removeClass('d-none');
        $('#kategori-table').DataTable();
    }, 500);
});
</script>
    
    <script>
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
                 $('#summernotee').summernote({
                    height: 200,
                    minHeight: null,
                    maxHeight: null,
                    focus: true
                });
            },
            error: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('<div class="text-center py-3" style="color:#ff3366;">Terjadi kesalahan saat memuat data.</div>');
            }
        });
        myModal.show();
    }
</script>

<script>
    let fieldCount = 1;
    const addMoreBtn = document.getElementById('addMoreBtn');
    const fieldsContainer = document.getElementById('fieldsContainer');

    addMoreBtn.addEventListener('click', () => {
        fieldCount++;
        const newFieldGroup = document.createElement('div');
        newFieldGroup.classList.add('field-group');
        newFieldGroup.innerHTML = `
            <div style="color:#00f0ff;font-family:'Orbitron',sans-serif;font-size:0.75rem;margin:12px 0 8px;">Field ${fieldCount}</div>
            <div class="mt-2"><input class="form-control" placeholder="Title" name="inputs_${fieldCount}_title"></div>
            <div class="mt-3"><input class="form-control" placeholder="Name" name="inputs_${fieldCount}_name"></div>
            <div class="mt-3 mb-3">
                <select class="form-select" name="inputs_${fieldCount}_type">
                    <option>Select Input Type</option>
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="password">Password</option>
                    <option value="select">Select</option>
                </select>
            </div>
        `;
        fieldsContainer.appendChild(newFieldGroup);

        const newSelect = newFieldGroup.querySelector(`select[name="inputs_${fieldCount}_type"]`);
        newSelect.addEventListener('change', (event) => {
            if (event.target.value === 'select') {
                const labelInput = document.createElement('input');
                labelInput.classList.add('form-control','mt-2');
                labelInput.placeholder = 'Label';
                labelInput.name = `inputs_${fieldCount}_label`;
                const valueInput = document.createElement('input');
                valueInput.classList.add('form-control','mt-2');
                valueInput.placeholder = 'Value';
                valueInput.name = `inputs_${fieldCount}_value`;
                newFieldGroup.appendChild(labelInput);
                newFieldGroup.appendChild(valueInput);
            } else {
                const labelInput = newFieldGroup.querySelector(`input[name="inputs_${fieldCount}_label"]`);
                const valueInput = newFieldGroup.querySelector(`input[name="inputs_${fieldCount}_value"]`);
                if (labelInput) labelInput.remove();
                if (valueInput) valueInput.remove();
            }
        });
    });
</script>

{{-- Modal --}}
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-detail-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-detail-body"></div>
        </div>
    </div>
</div>
</div>

@endsection
