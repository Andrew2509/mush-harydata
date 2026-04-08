@extends('layouts.user')

@section('custom_style')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#135bec",
                    "background-light": "#f6f6f8",
                    "background-dark": "#101622",
                },
                fontFamily: {
                    "display": ["Inter", "sans-serif"]
                },
                borderRadius: {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
            },
        },
    }
</script>
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    .likert-radio:checked + label {
        background-color: #135bec;
        color: white;
        border-color: #135bec;
    }
</style>
@endsection

@section('content')
<div class="relative flex min-h-screen flex-col overflow-x-hidden bg-background-light dark:bg-background-dark">
    <main class="flex-1 w-full max-w-5xl mx-auto px-6 py-10">
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight mb-4 text-slate-900 dark:text-white uppercase">
                EVALUASI USABILITY - <span class="text-primary">SKOR SUS</span>
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 max-w-3xl leading-relaxed">
                Bantu kami meningkatkan kualitas layanan dengan memberikan penilaian sejujur mungkin. 
                Skala 1 (Sangat Tidak Setuju) hingga 5 (Sangat Setuju).
            </p>
        </div>

        <form action="{{ route('sus.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="space-y-4">
                    <label for="nama" class="block text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" required 
                           class="w-full h-12 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary text-slate-900 dark:text-white"
                           placeholder="Contoh: Budi Santoso">
                </div>
                <div class="space-y-4">
                    <label for="usia" class="block text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Usia (Tahun)</label>
                    <input type="number" name="usia" id="usia" required min="1" max="120" 
                           class="w-full h-12 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary text-slate-900 dark:text-white"
                           placeholder="Contoh: 25">
                </div>
                <div class="space-y-4">
                    <label for="jenis_kelamin" class="block text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                            class="w-full h-12 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-primary focus:border-primary text-slate-900 dark:text-white">
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-12 mb-16">
                @php
                    $midPoint = ceil($questions->count() / 2);
                    $columns = $questions->chunk($midPoint);
                @endphp

                @foreach($columns as $column)
                <div class="space-y-4">
                    @foreach($column as $q)
                    <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-white dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 group text-slate-900 dark:text-slate-100">
                        <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">{{ $q->order }}</div>
                        <div class="flex-1">
                            <p class="font-medium mb-3 group-hover:text-primary transition-colors">{{ $q->question_text }}</p>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">STS</span>
                                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-lg gap-1">
                                    @for($i=1; $i<=5; $i++)
                                    <input class="hidden likert-radio" id="q{{ $q->order }}-{{ $i }}" name="q{{ $q->order }}" type="radio" value="{{ $i }}" required/>
                                    <label class="w-10 h-10 flex items-center justify-center rounded-md border border-transparent cursor-pointer text-sm font-semibold hover:bg-white dark:hover:bg-slate-700 transition-all" for="q{{ $q->order }}-{{ $i }}">{{ $i }}</label>
                                    @endfor
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SS</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full">
                        <span class="material-symbols-outlined block">verified</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white">Pastikan semua terjawab</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Penilaian Anda sangat berharga bagi pengembangan kami.</p>
                    </div>
                </div>
                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/25 hover:bg-primary/90 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    Kirim Penilaian
                </button>
            </div>
        </form>
    </main>
    <footer class="mt-auto py-8 text-center border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-background-dark">
        <p class="text-slate-500 text-sm">© {{ date('Y') }} Usability Testing Suite. Hak cipta dilindungi undang-undang.</p>
    </footer>
</div>
@endsection
