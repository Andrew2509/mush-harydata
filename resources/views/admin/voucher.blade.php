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
<div class="card mb-3">
    <div class="card-body">
        <h4 class="mb-3 header-title mt-0">Tambah Voucher</h4>
        <form action="{{ route('voucher.post') }}" method="POST">
            @csrf

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label" for="example-fileinput">Kode</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" name="kode">
                    @error('kode')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label" for="example-fileinput">Persenan Promo</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control @error('promo') is-invalid @enderror" value="{{ old('promo') }}" name="promo" placeholder="10">
                    @error('promo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label" for="example-fileinput">Stock</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" name="stock" placeholder="10">
                    @error('stock')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3 row">
                <label class="col-lg-2 col-form-label" for="example-fileinput">Max Potongan</label>
                <div class="col-lg-10">
                    <input type="number" class="form-control @error('max_potongan') is-invalid @enderror" value="{{ old('max_potongan') }}" name="max_potongan" placeholder="100000">
                    @error('max_potongan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>            

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mt-0 mb-1">Semua Voucher</h4>
                <div id="loadingSpinnerVoucher" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="table-responsive d-none" id="voucherTableContainer">
                    <table class="table table-dark table-bordered table-striped m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Potongan</th>
                                <th>Kuota</th>
                                <th>Max Potongan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $vouchers as $data )
                            <tr>
                                <th scope="row">{{ $data->id }}</th>
                                <td>{{ $data->kode }}</td>
                                <td>{{ $data->promo }} %</td>
                                <td>{{ $data->stock }}</td>
                                <td>{{ $data->max_potongan }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>
                                    <a href="javascript:;" onclick="modal('{{ $data->kode }}', '{{ route('voucher.detail', [$data->id]) }}')" class="btn btn-info"><i class="fa fa-qrcode"></i> Edit</a>
                                    <a class="btn btn-danger" href="{{ route('voucher.delete', [$data->id]) }}">Hapus</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($vouchers) == 0)
                <p class="text-center mt-3">No vouchers available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Simulate a delay for demonstration purposes
    setTimeout(function() {
        $('#loadingSpinnerVoucher').addClass('d-none');
        $('#voucherTableContainer').removeClass('d-none');
    }, 500); // Adjust the delay as needed
});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.table').DataTable({
           
        });
    });
    function modal(name, link) {
        var myModal = new bootstrap.Modal($('#modal-detail'))
        $.ajax({
            type: "GET",
            url: link,
            beforeSend: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('Loading...');
            },
            success: function(result) {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html(result);
            },
            error: function() {
                $('#modal-detail-title').html(name);
                $('#modal-detail-body').html('There is an error...');
            }
        });
        myModal.show();
    }
</script>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" id="modal-detail" style="border-radius:7%">
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
