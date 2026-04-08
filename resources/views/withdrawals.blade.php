@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Penarikan (Withdrawals)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="withdrawals-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Rekening</th>
                                    <th>Total Transfer</th>
                                    <th>Biaya Admin</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>{{ $withdrawal->rekening }}</td>
                                    <td>Rp {{ number_format($withdrawal->total_transfer, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($withdrawal->biaya_admin, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            switch(strtolower($withdrawal->status)) {
                                                case 'success':
                                                case 'sukses':
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'pending':
                                                    $badgeClass = 'bg-warning';
                                                    break;
                                                case 'failed':
                                                case 'gagal':
                                                    $badgeClass = 'bg-danger';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($withdrawal->status) }}</span>
                                    </td>
                                    <td>{{ $withdrawal->created_at }}</td>
                                </tr>
                                @endforeach
                                @if($withdrawals->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data penarikan.</td>
                                </tr>
                                @endif
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
    if ($('#withdrawals-table tbody tr').length > 1 || !$('#withdrawals-table tbody tr td').hasClass('text-center')) {
        $('#withdrawals-table').DataTable({
            order: [[0, 'desc']]
        });
    }
});
</script>
@endsection
