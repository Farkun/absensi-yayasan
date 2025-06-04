
<style>
    .custom-card-width {
        flex: 0 0 20%;
        max-width: 20%;
        padding: 0 10px;
    }

    @media (max-width: 992px) {
        .custom-card-width {
            flex: 0 0 33.333%;
            max-width: 33.333%;
            margin-bottom: 15px;
        }
    }

    @media (max-width: 768px) {
        .custom-card-width {
            flex: 0 0 50%;
            max-width: 50%;
            margin-bottom: 15px;
        }
    }

    @media (max-width: 576px) {
        .custom-card-width {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <h2 class="mb-1">Selamat Datang, <?php echo e(Auth::guard('user')->user()->name); ?></h2>
                                    <div class="mt-1 large text-muted">Administrator</div>
                                </div>
                            </div>
                            <br><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col custom-card-width">
                <div class="card card-widget">
                    <div class="card-body gradient-7">
                        <div class="media">
                            <span class="card-widget__icon"><i class="icon-user gradient-4-text"></i></span>
                            <div class="media-body">
                                <h2 class="card-widget__title"><?php echo e($rekappresensi->jmlhadir); ?></h2>
                                <h5 class="card-widget__subtitle">Pegawai Hadir</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col custom-card-width">
                <div class="card card-widget">
                    <div class="card-body gradient-4">
                        <div class="media">
                            <span class="card-widget__icon"><ion-icon name="document-outline"
                                    style="font-size: 35px;"></ion-icon></span>
                            <div class="media-body">
                                <h2 class="card-widget__title"><?php echo e($rekapizin->jmlizin != null ? $rekapizin->jmlizin : 0); ?>

                                </h2>
                                <h5 class="card-widget__subtitle">Pegawai Izin</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col custom-card-width">
                <div class="card card-widget">
                    <div class="card-body gradient-5">
                        <div class="media">
                            <span class="card-widget__icon"><ion-icon name="medkit-outline"
                                    style="font-size: 35px;"></ion-icon></span>
                            <div class="media-body">
                                <h2 class="card-widget__title"><?php echo e($rekapizin->jmlsakit != null ? $rekapizin->jmlsakit : 0); ?>

                                </h2>
                                <h5 class="card-widget__subtitle">Pegawai Sakit</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col custom-card-width">
                <div class="card card-widget">
                    <div class="card-body gradient-9">
                        <div class="media">
                            <span class="card-widget__icon"><ion-icon name="time-outline"
                                    style="font-size: 35px;"></ion-icon></span>
                            <div class="media-body">
                                <h2 class="card-widget__title">
                                    <?php echo e($rekappresensi->jmlterlambat != null ? $rekappresensi->jmlterlambat : 0); ?></h2>
                                <h5 class="card-widget__subtitle">Pegawai Telat</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col custom-card-width">
                <div class="card card-widget">
                    <div class="card-body gradient-3">
                        <div class="media">
                            <span class="card-widget__icon"><ion-icon name="calendar-clear-outline"
                                    style="font-size: 35px;"></ion-icon></span>
                            <div class="media-body">
                                <h2 class="card-widget__title">
                                    <?php echo e($rekapizin->jmlcuti != null ? $rekapizin->jmlcuti : 0); ?></h2>
                                <h5 class="card-widget__subtitle">Pegawai Cuti</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/dashboard/dashboardadmin.blade.php ENDPATH**/ ?>