@extends('layouts.admin')

@section('content')
    <!-- start page title -->
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

        <div class="row row-cols-1">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Buat Nama Paket</h4>
                        <form action="{{ route('paket.store') }}" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Nama</label>
                                <div class="col-lg-10">
                                    <input id="summermell" type="text"
                                           class="form-control @error('nama') is-invalid @enderror" name="nama"
                                           placeholder="Masukkan Nama Paket" value="{{ old('nama') }}">
                                    @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                                <button type="button" class="btn btn-primary" onclick="confirmTambahPaket()">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3 header-title mt-0">Tambah Layanan Paket</h4>
                        <form action="{{ route('paket-layanan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label" for="example-fileinput">Paket</label>
                                <div class="col-lg-10">
                                    <select class="form-control @error('paket_id') is-invalid @enderror"
                                            name="paket_id">
                                        <option value="" selected disabled>--Pilih Paket--</option>
                                        @foreach ($pakets as $paket)
                                            <option value="{{ $paket->id }}"
                                                    {{ old('paket_id') == $paket->id ? 'selected' : '' }}>
                                                {{ $paket->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('paket_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Kategori</label>
                                <div class="col-lg-10">
                                    <select class="form-select" onchange="get_layanan(this.value)">
                                        <option value="" selected disabled>Pilih Kategori</option>
                                        @foreach ($kategoris as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Layanan</label>
                                <div class="col-lg-10" id="layanan-container">
                                    <!-- Dynamic content will be loaded here -->
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Upload Logo</label>
                                <div class="col-lg-10">
                                    <input type="file" class="form-control @error('product_logo') is-invalid @enderror"
                                           name="product_logo">
                                    @error('product_logo')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                           
    <button type="button" class="btn btn-primary" onclick="confirmTambahLayananPaket()">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0 mb-1">Semua Paket</h4>
                        <div id="loadingSpinnerPaket" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="table-responsive d-none" id="paketTableContainer">
                            <table class="table m-0">
                                <thead>
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Paket</th>
                                    <th>Layanan</th>
                                    <th style="width: 200px">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pakets as $paket)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $paket->nama }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#kategoriModal{{ $paket->id }}">{{ $paket->layanan->count() }}
                                                Layanan
                                            </button>
                                        </td>
                                        <td class="d-flex gap-2">
                                            <a href="javascript:;" class="btn btn-primary" onclick="confirmEdit('{{ $paket->id }}')">
    Edit
</a>

                                            <form action="{{ route('paket.destroy', $paket->id) }}" method="POST"
                                                  id="deleteForm{{ $paket->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger"
                                                        onclick="confirmDelete({{ $paket->id }})">Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@foreach ($pakets as $paket)
    <div class="modal fade" id="editModal{{ $paket->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $paket->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $paket->id }}">Edit Paket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Edit -->
                    <form action="{{ route('paket.update', $paket->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editNama{{ $paket->id }}" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" id="editNama{{ $paket->id }}" name="nama" value="{{ $paket->nama }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="kategoriModal{{ $paket->id }}" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategoriModalLabel">{{ $paket->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form action="{{ route('paket-layanan.destroy') }}" method="post" id="bulkDeleteForm">
                        @csrf
                        @method('DELETE')
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Layanan Diamond</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paket->layanan->groupBy('kategori_id') as $k => $l)
                                    <tr>
                                        <td>{{ isset($l->first()->kategori->nama) ? $l->first()->kategori->nama : 'undefined' }}</td>
                                        <td>
                                            <ul>
                                                @foreach ($l as $item)
                                                    <li class="d-flex ml-3">
                                                        <input type="checkbox" name="layanan_ids[]" value="{{ $item->id }}">
                                                        <span>{{ $item->layanan }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-danger mt-3">Hapus Terpilih</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('bulkDeleteForm').addEventListener('submit', function(event) {
        if (!confirm('Apakah Anda yakin ingin menghapus item yang dipilih?')) {
            event.preventDefault();
        }
    });
</script>

@endforeach


    </div>

    <!-- Pastikan SweetAlert sudah di-load sebelum menggunakan script ini -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi konfirmasi sebelum menghapus paket
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan penghapusan
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }


// Fungsi konfirmasi sebelum edit (opsional)
function confirmEdit(paketId) {
    swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan mengubah data ini!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, edit!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan modal edit setelah konfirmasi
            $('#editModal' + paketId).modal('show');
        }
    });
}

// Fungsi konfirmasi sebelum menambah paket
function confirmTambahPaket() {
    swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan menambahkan paket baru!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, tambah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Lanjutkan submit
            document.querySelector('form[action="{{ route('paket.store') }}"]').submit();
        }
    });
}
// Fungsi konfirmasi sebelum menambah layanan paket
function confirmTambahLayananPaket() {
    swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda akan menambah layanan untuk paket ini!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, tambah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Lanjutkan submit
            document.querySelector('form[action="{{ route('paket-layanan.store') }}"]').submit();
        }
    });
}

    </script>

    <script>
        $(document).ready(function () {
            // Simulate a delay for demonstration purposes
            setTimeout(function () {
                $('#loadingSpinnerPaket').addClass('d-none');
                $('#paketTableContainer').removeClass('d-none');
            }, 500); // Sesuaikan delay jika perlu
        });
    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            $('.table').DataTable({

            });
        });

        function get_layanan(kategori_id) {
            let layananContainer = $('#layanan-container');
            $.ajax({
                url: "{{ route('paket-layanan.get-layanan') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    kategori_id: kategori_id
                },
                beforeSend: function () {
                    layananContainer.html('Mengambil Layanan...');
                },
                success: function (response) {
                    var data = response.data;
                    data.sort(function (a, b) {
                        return a.harga - b.harga;
                    });
                    layananContainer.empty();
                    $.each(data, function (index, item) {
                        layananContainer.append('<div class="form-check">' +
                            '<input class="form-check-input" type="checkbox" name="layanan_id[]" value="' + item.id + '" id="layanan_' + item.id + '">' +
                            '<label class="form-check-label" for="layanan_' + item.id + '">' + item.layanan + '</label>' +
                            '</div>'
                        );
                    });
                    clearSelectedLayanan();
                },
                error: function (response) {
                    let res = JSON.parse(response.responseText);
                    layananContainer.html('<div class="text-danger">' + res.message + '</div>');
                }
            });
        }

        function clearSelectedLayanan() {
            $('input[type="checkbox"]:checked').each(function () {
                $(this).prop('checked', false);
            });
        }

    </script>

@endsection
