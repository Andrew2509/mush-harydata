@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-chart-line" style="color:#00f0ff;font-size:0.9rem;"></i> Analisis Performa & Latensi Transaksi
        </h4>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="latency-table">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>Provider</th>
                                    <th>Waktu Callback</th>
                                    <th>Waktu Fulfillment</th>
                                    <th>Total Latensi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $order)
                                @php
                                    $latency = 0;
                                    $keterangan = '-';
                                    $label = 'secondary';
                                    
                                    if($order->waktu_callback && $order->waktu_fulfillment) {
                                        $start = \Carbon\Carbon::parse($order->waktu_callback);
                                        $finish = \Carbon\Carbon::parse($order->waktu_fulfillment);
                                        $latency = $start->diffInSeconds($finish);
                                        
                                        if($latency <= 15) {
                                            $keterangan = 'Sangat Cepat';
                                            $label = 'success';
                                        } elseif($latency <= 30) {
                                            $keterangan = 'Cepat';
                                            $label = 'info';
                                        } elseif($latency <= 60) {
                                            $keterangan = 'Normal';
                                            $label = 'primary';
                                        } else {
                                            $keterangan = 'Lambat';
                                            $label = 'warning';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ ucfirst($order->layanan) }}</td>
                                    <td>{{ $order->waktu_callback ? \Carbon\Carbon::parse($order->waktu_callback)->format('H:i:s') : '-' }}</td>
                                    <td>{{ $order->waktu_fulfillment ? \Carbon\Carbon::parse($order->waktu_fulfillment)->format('H:i:s') : '-' }}</td>
                                    <td>{{ $latency }} Detik</td>
                                    <td><span class="badge bg-{{ $label }}">{{ $keterangan }}</span></td>
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
    $('#latency-table').DataTable({
        order: [[2, 'desc']]
    });
});
</script>
@endsection
