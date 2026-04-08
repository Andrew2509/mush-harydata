@extends('layouts.admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-cog" style="color:#00f0ff;font-size:0.9rem;"></i> Kelola Instrumen SUS
        </h4>
        <a href="{{ route('admin.sus.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- List Questions -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Daftar Pertanyaan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Urutan</th>
                                    <th>Teks Pertanyaan</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $q)
                                <tr>
                                    <td>{{ $q->order }}</td>
                                    <td>
                                        <form action="{{ route('admin.sus.question.update', $q->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            <input type="text" name="question_text" class="form-control form-control-sm" value="{{ $q->question_text }}">
                                            <input type="number" name="order" class="form-control form-control-sm" value="{{ $q->order }}" style="width: 60px;">
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.sus.question.delete', $q->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pertanyaan ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Question -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">Tambah Pertanyaan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sus.question.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Teks Pertanyaan</label>
                            <textarea name="question_text" class="form-control" rows="3" required placeholder="Contoh: Saya merasa sistem mudah digunakan..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutan (1-10)</label>
                            <input type="number" name="order" class="form-control" required value="{{ $questions->max('order') + 1 }}">
                        </div>
                        <button type="submit" class="btn btn-success w-full">Simpan Pertanyaan</button>
                    </form>

                    <div class="alert alert-info mt-4 pb-0">
                        <h6 class="alert-heading small fw-bold">Tips SUS:</h6>
                        <ul class="small ps-3">
                            <li>Pertanyaan ganjil bersifat positif.</li>
                            <li>Pertanyaan genap bersifat negatif.</li>
                            <li>Gunakan 10 pertanyaan untuk akurasi terbaik.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
