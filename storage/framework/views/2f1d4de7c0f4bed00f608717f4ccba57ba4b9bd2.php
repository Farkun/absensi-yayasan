
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Rekap Presensi</h4>
                                <br>
                                <!-- <p class="text-muted m-b-15 f-s-12">Use the input classes on an <code>.input-default, input-flat, .input-rounded</code> for Default input.</p> -->
                                <div class="basic-form">
                                    <form id="formFilter" method="POST" target="_blank">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <select class="form-control" name="bulan" id="bulan">
                                                <option value="">Bulan (semua bulan)</option>
                                                <?php for($i = 1; $i <= 12; $i++): ?>
                                                    <option value="<?php echo e($i); ?>" <?php echo e(date("m") == $i ? 'selected' : ''); ?>>
                                                        <?php echo e($namabulan[$i]); ?>

                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="tahun" id="tahun">
                                                <?php
                                                    $tahunmulai = 2024;
                                                    $tahunskrg = date("Y");
                                                ?>
                                                <?php for($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++): ?>
                                                    <option value="<?php echo e($tahun); ?>" <?php echo e(date("Y") == $tahun ? 'selected' : ''); ?>>
                                                        <?php echo e($tahun); ?>

                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            
                                            <button type="submit" class="btn btn-info d-inline-flex align-items-center mr-2"
                                                formaction="<?php echo e(url('/presensi/cetakrekap')); ?>">
                                                <ion-icon name="print-outline" style="font-size: 18px;"></ion-icon> Cetak
                                            </button>

                                            
                                            <button type="submit" class="btn btn-success d-inline-flex align-items-center"
                                                formaction="<?php echo e(route('export.rekap')); ?>">
                                                <ion-icon name="download-outline" style="font-size: 18px;"></ion-icon>
                                                Export Excel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/rekap.blade.php ENDPATH**/ ?>