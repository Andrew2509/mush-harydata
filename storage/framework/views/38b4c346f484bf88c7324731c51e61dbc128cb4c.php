

<?php $__env->startSection('content'); ?>
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
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
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
                                ?>
                                <tr>
                                    <td><?php echo e($order->order_id); ?></td>
                                    <td><?php echo e(ucfirst($order->layanan)); ?></td>
                                    <td><?php echo e($order->waktu_callback ? \Carbon\Carbon::parse($order->waktu_callback)->format('H:i:s') : '-'); ?></td>
                                    <td><?php echo e($order->waktu_fulfillment ? \Carbon\Carbon::parse($order->waktu_fulfillment)->format('H:i:s') : '-'); ?></td>
                                    <td><?php echo e($latency); ?> Detik</td>
                                    <td><span class="badge bg-<?php echo e($label); ?>"><?php echo e($keterangan); ?></span></td>
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
    $('#latency-table').DataTable({
        order: [[2, 'desc']]
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\muslihinnnn (1)\harydata\resources\views/admin/latency.blade.php ENDPATH**/ ?>