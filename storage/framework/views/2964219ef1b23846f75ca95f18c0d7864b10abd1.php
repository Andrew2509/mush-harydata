

<?php $__env->startSection('custom_style'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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

        <form action="<?php echo e(route('sus.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12 p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
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
                <?php
                    $midPoint = ceil($questions->count() / 2);
                    $columns = $questions->chunk($midPoint);
                ?>

                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $column; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-white dark:hover:bg-slate-800/50 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700 group text-slate-900 dark:text-slate-100">
                        <div class="mt-1 flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm"><?php echo e($q->order); ?></div>
                        <div class="flex-1">
                            <p class="font-medium mb-3 group-hover:text-primary transition-colors"><?php echo e($q->question_text); ?></p>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">STS</span>
                                <div class="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-lg gap-1">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                    <input class="hidden likert-radio" id="q<?php echo e($q->order); ?>-<?php echo e($i); ?>" name="q<?php echo e($q->order); ?>" type="radio" value="<?php echo e($i); ?>" required/>
                                    <label class="w-10 h-10 flex items-center justify-center rounded-md border border-transparent cursor-pointer text-sm font-semibold hover:bg-white dark:hover:bg-slate-700 transition-all" for="q<?php echo e($q->order); ?>-<?php echo e($i); ?>"><?php echo e($i); ?></label>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SS</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <p class="text-slate-500 text-sm">© <?php echo e(date('Y')); ?> Usability Testing Suite. Hak cipta dilindungi undang-undang.</p>
    </footer>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/user/sus/form.blade.php ENDPATH**/ ?>