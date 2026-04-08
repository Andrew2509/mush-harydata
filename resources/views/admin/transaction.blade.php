@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-lg-12 mb-4 order-0">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <i class="fas fa-shopping-cart" style="color:#00f0ff;font-size:0.9rem;"></i> Semua Pesanan
        </h4>
    </div>

   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="loadingSpinnerPesanan" class="text-center" style="padding:3rem 0;">
                    <div class="spinner-border" role="status" style="width:2.5rem;height:2.5rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p style="color:#5a6380;margin-top:0.8rem;font-size:0.85rem;">Memuat data pesanan...</p>
                </div>
                <div class="table-responsive d-none" id="pesananTableContainer">
                    <table class="table" id="pesanan-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Pembayaran</th>
                                <th>Layanan</th>
                                <th>UID/NICKNAME</th>
                                <th>Status</th>
                                <th>Informasi Status</th>
                                <th>PID</th>
                                <th>Log</th>
                                <th>Informasi IP/TGL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $data_pesanan)
                            @php
                            $label_pesanan = '';
                            $status_info = '';
                            $status_display = ''; 
                            $status = $data_pesanan->status;

                            switch($status) {
                                case "Batal":
                                case "Gagal":
                                    $label_pesanan = 'danger';
                                    $status_info = 'Pesanan dibatalkan.';
                                    $status_display = 'Cancelled';
                                    break;
                                case "Pending":
                                case "pending":
                                    $label_pesanan = 'warning';
                                    $status_info = 'Pesanan menunggu proses.';
                                    $status_display = 'Pending';
                                    break;
                                case "Success":
                                case "Sukses":
                                    $label_pesanan = 'success';
                                    $status_info = 'Pesanan berhasil.';
                                    $status_display = 'Success';
                                    break;
                                case "Proses":
                                case "Process":
                                    $label_pesanan = 'info';
                                    $status_info = 'Pesanan sedang diproses.';
                                    $status_display = 'Process'; 
                                    break;
                                default:
                                    $label_pesanan = 'info';
                                    $status_info = 'Sedang diproses.';
                                    $status_display = $status; 
                                    break;
                            }
                            @endphp

                            <tr class="table-{{ $label_pesanan }}">
                                <th scope="row"><a href="{{ ENV('APP_URL') }}/id/invoices/{{ $data_pesanan->order_id }}">{{ $data_pesanan->order_id }}</a><br><small style="color:#5a6380;">{{ $data_pesanan->created_at }}</small></th>
                                <td>Rp. {{ number_format($data_pesanan->harga, 0, '.', ',') }}<br><small>{{ $data_pesanan->status_pembayaran }}</small><br><small>{{ $data_pesanan->metode }}</small></td>
                                <td>{{ $data_pesanan->layanan }}</td>
                                <td>{{ $data_pesanan->nickname ?? '' }} {{ $data_pesanan->nickname_joki ?? '' }}<br>{{ $data_pesanan->user_id }}  {{ $data_pesanan->zone != null ? "(".$data_pesanan->zone.")" : '' }} </td>
                                <td>
                                    <div class="btn-group-vertical">
                                         <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-{{$label_pesanan}} dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $status_display }} <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item process-link" href="/process-order/{{ $data_pesanan->order_id }}" data-status="{{ $status }}">Process</a></li>
                                            <li><a class="dropdown-item status-link" href="/order-status/{{ $data_pesanan->order_id }}/Sukses" data-current-status="{{ $status }}" data-target-status="Sukses">Success</a></li>
                                            <li><a class="dropdown-item status-link" href="/order-status/{{ $data_pesanan->order_id }}/Batal" data-current-status="{{ $status }}" data-target-status="Batal">Cancelled</a></li>
                                            <li><a class="dropdown-item status-link" href="/order-status/{{ $data_pesanan->order_id }}/Pending" data-current-status="{{ $status }}" data-target-status="Pending">Pending</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td><small>{{ $status_info }}</small></td>
                                <td><small>{{ $data_pesanan->provider_order_id ?? '-' }}</small></td>
                                <td><small>{{ $data_pesanan->log }}</small></td>
                                <td><small>{{ $data_pesanan->ip_address }}</small></td>
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
        $('#loadingSpinnerPesanan').addClass('d-none');
        $('#pesananTableContainer').removeClass('d-none');
        $('#pesanan-table').DataTable({
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.status-link, .process-link').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    var currentStatus = this.getAttribute('data-status').toLowerCase();
                    var targetStatus = this.getAttribute('data-target-status') ? this.getAttribute('data-target-status').toLowerCase() : null;
                    
                    if (link.classList.contains('process-link') && (currentStatus === 'pending')) {
                        event.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak bisa memproses pesanan pending',
                        });
                        return;
                    }

                    if (targetStatus && currentStatus === 'pending' && (targetStatus === 'sukses' || targetStatus === 'success' || targetStatus === 'batal' || targetStatus === 'Gagal')) {
                        event.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Pesanan pending tidak bisa dipindahkan ke Success, Cancelled, atau Failed.',
                        });
                        return;
                    }

                    if (targetStatus && (currentStatus === 'success' || currentStatus === 'sukses') && (targetStatus === 'batal' || targetStatus === 'cancelled' || targetStatus === 'pending')) {
                        event.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Pesanan yang sudah berhasil tidak bisa dipindahkan ke Cancelled atau Pending.',
                        });
                    }
                });
            });
        });
    </script>
    </div>
@endsection
