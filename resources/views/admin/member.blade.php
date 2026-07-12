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
        <i class="fas fa-users" style="color:#00f0ff;font-size:0.9rem;"></i> Manajemen Member
    </h4>
</div>

{{-- Tambah Member Form --}}
<div class="card mb-4">
    <div class="card-header" style="display:flex;align-items:center;gap:8px;">
        <i class="fas fa-user-plus" style="color:#00ff88;"></i>
        <span>Tambah Member Baru</span>
    </div>
    <div class="card-body">
        <form id="addMemberForm" action="{{ route('member.post') }}" method="POST">
            @csrf

            <div class="mb-3 row">
                <label for="nama" class="col-lg-2 col-form-label">Nama</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama member">
                    @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="username" class="col-lg-2 col-form-label">Username</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username">
                    @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="col-lg-2 col-form-label">Email</label>
                <div class="col-lg-10">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password" class="col-lg-2 col-form-label">Password</label>
                <div class="col-lg-10">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" placeholder="Masukkan password">
                    <div id="password-requirements" style="font-size: 12px; margin-top: 8px; display: flex; flex-direction: column; gap: 4px; line-height: 1.4;">
                        <span class="text-muted font-weight-bold" style="display: block; margin-bottom: 2px;">Password harus memenuhi syarat:</span>
                        <div id="req-length" style="color: #6c757d; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                            <i class="fa fa-times-circle text-danger" id="icon-length"></i> Minimal 8 karakter
                        </div>
                        <div id="req-uppercase" style="color: #6c757d; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                            <i class="fa fa-times-circle text-danger" id="icon-uppercase"></i> Minimal 1 huruf besar
                        </div>
                        <div id="req-lowercase" style="color: #6c757d; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                            <i class="fa fa-times-circle text-danger" id="icon-lowercase"></i> Minimal 1 huruf kecil
                        </div>
                        <div id="req-number" style="color: #6c757d; display: flex; align-items: center; gap: 6px; transition: all 0.3s ease;">
                            <i class="fa fa-times-circle text-danger" id="icon-number"></i> Minimal 1 angka
                        </div>
                    </div>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="no_wa" class="col-lg-2 col-form-label">No Whatsapp</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control @error('no_wa') is-invalid @enderror" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" placeholder="628xxx">
                    @error('no_wa')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="role" class="col-lg-2 col-form-label">Role</label>
                <div class="col-lg-10">
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                        <option value="Member">Member</option>
                        <option value="Platinum">Platinum</option>
                        <option value="Gold">Gold</option>
                    </select>
                    @error('role')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-primary" onclick="confirmAddMember()" style="display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-plus"></i> Buat Member
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Member Table --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header" style="display:flex;align-items:center;gap:8px;">
                <i class="fas fa-list" style="color:#00f0ff;"></i>
                <span>Daftar Semua Member</span>
            </div>
            <div class="card-body">
                <div id="loadingSpinner" class="text-center" style="padding:3rem 0;">
                    <div class="spinner-border" role="status" style="width:2.5rem;height:2.5rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="color:#5a6380;margin-top:0.8rem;font-size:0.85rem;">Memuat data member...</p>
                </div>
                <div class="table-responsive d-none" id="dataTableContainer">
                    <table id="userTable" class="table table-dark table-bordered table-striped m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>No Whatsapp</th>
                                <th>Saldo</th>
                                <th>Level</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $users as $user )
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->no_wa }}</td>
                                <td style="color:#00ff88;">Rp. {{ number_format($user->balance, 0, ',', '.') }}</td>
                                <td>
                                    <span style="padding:3px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;
                                        @if($user->role == 'Platinum') background:rgba(255,0,229,0.12);color:#ff00e5;
                                        @elseif($user->role == 'Gold') background:rgba(238,247,11,0.12);color:#eef70b;
                                        @else background:rgba(0,240,255,0.1);color:#00f0ff;
                                        @endif
                                    ">{{ $user->role }}</span>
                                </td>
                                <td><small>{{ $user->created_at }}</small></td>
                                <td style="white-space:nowrap;">
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('member.delete', [$user->id]) }}')"><i class="bx bxs-message-alt-x"></i></button>
                                    <button class="btn btn-sm btn-info" onclick="confirmEdit('{{ $user->username }}', '{{ route('member.detail', [$user->id]) }}')"><i class="bx bxs-edit"></i></button>
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

<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#loadingSpinner').addClass('d-none');
        $('#dataTableContainer').removeClass('d-none');
        $('#userTable').DataTable({
            order: [], 
            columnDefs: [{
                targets: 0, 
                orderable: true, 
                orderData: [0], 
                orderSequence: ['desc'] 
            }]
        });
    }, 500);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
   function confirmAddMember() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Pastikan data member yang anda buat sudah benar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, buat!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('addMemberForm').submit();
            }
        })
    }
    
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan bisa mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }

    function confirmEdit(username, url) {
        Swal.fire({
            title: 'Edit User',
            text: `Apakah Anda yakin ingin mengedit pengguna ${username}?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, edit!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                openModal(username, url);
            }
        })
    }

    function openModal(name, link) {
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

    const adminPasswordInput = document.getElementById('password');
    if (adminPasswordInput) {
        adminPasswordInput.addEventListener('input', function() {
            const val = this.value;
            const reqs = {
                length: { el: document.getElementById('req-length'), icon: document.getElementById('icon-length'), test: val.length >= 8 },
                uppercase: { el: document.getElementById('req-uppercase'), icon: document.getElementById('icon-uppercase'), test: /[A-Z]/.test(val) },
                lowercase: { el: document.getElementById('req-lowercase'), icon: document.getElementById('icon-lowercase'), test: /[a-z]/.test(val) },
                number: { el: document.getElementById('req-number'), icon: document.getElementById('icon-number'), test: /\d/.test(val) }
            };

            for (const key in reqs) {
                const req = reqs[key];
                if (req.test) {
                    req.el.style.color = '#198754';
                    req.icon.className = 'fa fa-check-circle text-success';
                } else {
                    req.el.style.color = '#6c757d';
                    req.icon.className = 'fa fa-times-circle text-danger';
                }
            }
        });
    }
</script>

{{-- Edit Modal --}}
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
