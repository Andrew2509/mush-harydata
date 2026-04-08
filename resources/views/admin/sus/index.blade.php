@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-poll-h" style="color:#00f0ff;font-size:0.9rem;"></i> Analisis Usability (SUS)
        </h4>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('admin.sus.export') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Download Excel
            </a>
            <a href="{{ route('admin.sus.manage') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-cog me-1"></i> Kelola Pertanyaan
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Total Responden</h6>
                    <h2 class="mb-0">{{ $totalResponses }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Rata-rata Skor SUS</h6>
                    <h2 class="mb-0 text-primary">{{ number_format($meanScore, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Grade</h6>
                    <h2 class="mb-0">{{ $analysis['grade'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Acceptability</h6>
                    <span class="badge bg-{{ $analysis['color'] }}">{{ $analysis['acceptability'] }}</span>
                    <p class="small text-muted mt-2 mb-0">Rating: {{ $analysis['adjective'] }}</p>
                </div>
            </div>
        </div>

        <!-- Tabulation Table -->
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Tabulasi Skor Mentah</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" id="sus-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    @foreach($questions as $q)
                                    <th title="{{ $q->question_text }}">P{{ $q->order }}</th>
                                    @endforeach
                                    <th>Total Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($responses as $res)
                                <tr>
                                    <td>{{ $res->nama ?? ($res->user ? $res->user->name : 'Anonymous') }}</td>
                                    @for($i=1; $i<=10; $i++)
                                    <td>{{ $res->{'q'.$i} }}</td>
                                    @endfor
                                    <td class="fw-bold">{{ number_format($res->total_score, 2) }}</td>
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
    $('#sus-table').DataTable({
        order: [[11, 'desc']]
    });
});
</script>
@endsection
