@extends('layouts.admin')

@section("content")
@if(session('error'))

    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@elseif(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 mb-4 order-0">
    </div>
    <div class="row mt-4">
    <div class="col-12">
        <div class="card mt-2">
            <div class="card-body">
                <h4 class="page-title">Riwayat deposit</h4>
                <div id="loadingSpinnerRiwayat" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="table-responsive d-none" id="riwayatTableContainer">
                    <table class="table table-dark table-bordered table-striped m-0" id="riwayat-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Username</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <!--<th>No Pembayaran</th>-->
                                <th>Status</th>
                                <th>Tanggal</th>
                                <!--<th>Aksi</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $data_pesanan)
                            @php
                            $label_pesanan = '';
                            if($data_pesanan->status == "Pending"){
                                $label_pesanan = 'warning';
                            }else if($data_pesanan->status == "Success"){
                                $label_pesanan = 'success';
                            }else{
                                $label_pesanan = 'danger';
                            }
                            @endphp
                            <tr class="table-{{ $label_pesanan }}">
                                <th scope="row">{{ $data_pesanan->order_id }}</th>
                                <td>{{ $data_pesanan->username }}</td>
                                <td>Rp. {{ number_format($data_pesanan->jumlah, 0, '.', ',') }}</td>
                                <th>{{ $data_pesanan->metode }}</th>
                                <!--<td>{!! $data_pesanan->metode != "QRIS" ? $data_pesanan->no_pembayaran : '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Lihat QR</button>'!!}</td>-->
                                <td>{{ $data_pesanan->status }}</td>
                                <td>{{ $data_pesanan->created_at }}</td>
                                <!--<td><a href="{{ route('confirm.deposit', [$data_pesanan->id,'Success']) }}" class="btn btn-success">Konfirmasi</a></td>-->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>                
            </div>
        </div>
    </div>
</div>

</div>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#loadingSpinnerRiwayat').addClass('d-none');
        $('#riwayatTableContainer').removeClass('d-none');
        $('#riwayat-table').DataTable();
    }, 500); // Adjust the delay as needed
});
</script>

<script>
    $(document).ready(function(){
        $('.table').DataTable({
            order: [], 
            columnDefs: [{
                targets: 0, 
                orderable: true, 
                orderData: [0], 
                orderSequence: ['desc'] 
            }]
        });
    });
</script>
@endsection
