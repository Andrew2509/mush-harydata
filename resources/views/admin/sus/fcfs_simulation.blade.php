@extends('layouts.admin')

@section('content')
<style>
.simulation-card {
    background: rgba(20, 24, 33, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 16px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
}
.metric-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 1.2rem;
    text-align: center;
    transition: all 0.3s ease;
}
.metric-card:hover {
    border-color: rgba(0, 240, 255, 0.2);
    box-shadow: 0 4px 15px rgba(0, 240, 255, 0.05);
}
.metric-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #ff9f00; /* Gold */
    font-family: 'Orbitron', sans-serif;
    margin-bottom: 4px;
}
.metric-label {
    font-size: 0.8rem;
    color: #a0aec0;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-hdd" style="color:#00f0ff;font-size:0.9rem;"></i> Simulasi FCFS Disk Scheduling
        </h4>
        <span class="badge bg-primary" style="font-family:'Orbitron',sans-serif;">Algoritma & Struktur Data</span>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Input Form -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Parameter Input</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('admin.fcfs.calculate') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="head" class="form-label">Posisi Head Awal (Starting Cylinder)</label>
                            <input type="number" class="form-control" id="head" name="head" value="{{ $head }}" min="0" required>
                            <div class="form-text text-muted">Contoh standard GeeksforGeeks: 53</div>
                        </div>

                        <div class="mb-4">
                            <label for="sequence" class="form-label">Sequence Request (Pemisah Koma)</label>
                            <textarea class="form-control" id="sequence" name="sequence" rows="4" required>{{ $sequence }}</textarea>
                            <div class="form-text text-muted">Contoh standard: 98, 183, 37, 122, 14, 124, 65, 67</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-calculator me-2"></i> Hitung FCFS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Simulation Graph & Results -->
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Hasil Simulasi & Visualisasi</h5>
                </div>
                <div class="card-body pt-4">
                    @if($result)
                    <!-- Metrics Row -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="metric-card">
                                <div class="metric-value">{{ $result['total_head_movement'] }}</div>
                                <div class="metric-label">Total Head Movement (Cylinder)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="metric-card">
                                <div class="metric-value">{{ $result['avg_seek_time'] }}</div>
                                <div class="metric-label">Average Seek Time</div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Grafik Pergerakan Head (Seek Trajectory)</h6>
                        <div id="fcfsChart" style="min-height: 320px;"></div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-cogs text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5>Belum Ada Hasil Simulasi</h5>
                        <p class="text-muted">Isi parameter input di sebelah kiri lalu klik tombol "Hitung FCFS" untuk memulai simulasi.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($result)
    <!-- Step-by-Step Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Perhitungan Langkah Demi Langkah (Step-by-Step Calculation)</h5>
                </div>
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th style="width: 10%;">Step</th>
                                    <th>Posisi Head Awal</th>
                                    <th>Cylinder Target</th>
                                    <th>Rumus Selisih Absolut</th>
                                    <th>Selisih (Head Movement)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result['steps'] as $step)
                                <tr class="text-center">
                                    <td>{{ $step['step'] }}</td>
                                    <td>{{ $step['from'] }}</td>
                                    <td>{{ $step['to'] }}</td>
                                    <td>| {{ $step['to'] }} - {{ $step['from'] }} |</td>
                                    <td class="fw-bold text-primary">{{ $step['diff'] }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-success text-center fw-bold">
                                    <td colspan="4" class="text-end">TOTAL HEAD MOVEMENT:</td>
                                    <td class="text-success">{{ $result['total_head_movement'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 alert alert-info">
                        <strong>Keterangan Teori:</strong> Pada algoritma **FCFS (First-Come, First-Served) Disk Scheduling**, disk controller melayani permintaan disk I/O sesuai urutan kedatangan. Total pergerakan head dihitung dengan menjumlahkan selisih absolut posisi head silinder antara setiap perpindahan yang berurutan. Formula umum untuk langkah $i$ adalah:
                        $$\text{Movement} = | \text{Target}_i - \text{Head}_{i-1} |$$
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if($result)
<script>
document.addEventListener("DOMContentLoaded", function() {
    var options = {
        series: [{
            name: 'Cylinder Position',
            data: @json($result['seek_sequence'])
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#00f0ff'],
        stroke: {
            curve: 'straight',
            width: 3
        },
        markers: {
            size: 6,
            colors: ['#ff9f00'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 8,
            }
        },
        grid: {
            row: {
                colors: ['rgba(255, 255, 255, 0.02)', 'transparent'],
                opacity: 0.5
            },
            borderColor: 'rgba(255, 255, 255, 0.08)'
        },
        xaxis: {
            categories: @json($result['categories']),
            labels: {
                style: {
                    colors: '#a0aec0'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Nomor Cylinder / Track',
                style: {
                    color: '#a0aec0'
                }
            },
            labels: {
                style: {
                    colors: '#a0aec0'
                }
            }
        },
        tooltip: {
            theme: 'dark'
        }
    };

    var chart = new ApexCharts(document.querySelector("#fcfsChart"), options);
    chart.render();
});
</script>
@endif
@endsection
