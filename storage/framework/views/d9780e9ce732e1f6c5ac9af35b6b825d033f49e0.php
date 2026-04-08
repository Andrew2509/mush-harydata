

<?php $__env->startSection('content'); ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:10px;">
        <h4 class="page-title" style="display:flex;align-items:center;gap:8px;margin:0;">
            <i class="fas fa-poll-h" style="color:#00f0ff;font-size:0.9rem;"></i> Analisis Usability (SUS)
        </h4>
        <div style="display:flex;gap:10px;">
            <a href="<?php echo e(route('admin.sus.export')); ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> Download Excel
            </a>
            <a href="<?php echo e(route('admin.sus.manage')); ?>" class="btn btn-primary btn-sm">
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
                    <h2 class="mb-0"><?php echo e($totalResponses); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Rata-rata Skor SUS</h6>
                    <h2 class="mb-0 text-primary"><?php echo e(number_format($meanScore, 2)); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Grade</h6>
                    <h2 class="mb-0"><?php echo e($analysis['grade']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">Acceptability</h6>
                    <span class="badge bg-<?php echo e($analysis['color']); ?>"><?php echo e($analysis['acceptability']); ?></span>
                    <p class="small text-muted mt-2 mb-0">Rating: <?php echo e($analysis['adjective']); ?></p>
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
                                    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th title="<?php echo e($q->question_text); ?>">P<?php echo e($q->order); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <th>Total Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $responses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($res->user ? $res->user->name : 'Anonymous'); ?></td>
                                    <?php for($i=1; $i<=10; $i++): ?>
                                    <td><?php echo e($res->{'q'.$i}); ?></td>
                                    <?php endfor; ?>
                                    <td class="fw-bold"><?php echo e(number_format($res->total_score, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/admin/sus/index.blade.php ENDPATH**/ ?>