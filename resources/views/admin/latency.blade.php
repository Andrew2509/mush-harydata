@extends('layouts.admin')

@section('content')
<style>
.analysis-card {
    background: rgba(20, 24, 33, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 20px;
    height: 100%;
    backdrop-filter: blur(10px);
}
.analysis-card:hover {
    transform: translateY(-3px);
    border-color: rgba(0, 240, 255, 0.3);
    box-shadow: 0 8px 24px rgba(0, 240, 255, 0.08);
}
.analysis-number {
    font-size: 2.2rem;
    font-weight: 800;
    color: #ff9f00; /* Beautiful orange-gold */
    text-shadow: 0 0 15px rgba(255, 159, 0, 0.3);
    font-family: 'Orbitron', sans-serif;
    min-width: 110px;
    line-height: 1;
}
.analysis-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.analysis-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #00f0ff; /* Neon cyan */
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 4px 0;
}
.analysis-desc {
    font-size: 0.78rem;
    color: #a0aec0; /* Slate text */
    line-height: 1.4;
    margin: 0;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-chart-line" style="color:#00f0ff;font-size:0.9rem;"></i> Analisis Performa & Latensi Transaksi
        </h4>
    </div>

    <!-- Analisis Statistik (Rekomendasi Dosen) -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="analysis-card">
                <div class="analysis-number">{{ $reduksiPersen }}%</div>
                <div class="analysis-details">
                    <h5 class="analysis-title">Reduksi Latensi Verifikasi</h5>
                    <p class="analysis-desc">Waktu respons callback webhook turun drastis dari 5.3 detik menjadi hanya {{ $avgCallbackSeconds }} detik.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="analysis-card">
                <div class="analysis-number">{{ $avgLatency }}s</div>
                <div class="analysis-details">
                    <h5 class="analysis-title">Avg. Waktu End-to-End</h5>
                    <p class="analysis-desc">Siklus kronologis utuh pemrosesan FCFS Queue hingga produk sukses terkirim.</p>
                </div>
            </div>
        </div>
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
