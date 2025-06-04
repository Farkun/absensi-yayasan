
<?php $__env->startSection('header'); ?>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin Khusus</div>
        <div class="right"></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div style="height: calc(100vh - 60px); overflow-y: auto;">
        <div class="row" style="margin-top: 70px;">
            <div class="col">
                <?php
                    $messagesuccess = Session::get('success');
                    $messageerror = Session::get('error');
                ?>
                <?php if(Session::get('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e($messagesuccess); ?>

                    </div>
                <?php endif; ?>
                <?php if(Session::get('error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e($messageerror); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                 <?php $__currentLoopData = $izinkhusus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div>
                                        <b><?php echo e(date("d-m-Y", strtotime($d->tanggal))); ?>

                                            (<?php echo e($d->jenis_izin); ?>)</b><br>
                                        <small class="text"><?php echo e($d->jam_izin); ?></small><br>
                                        <small class="text-muted"><?php echo e($d->alasan); ?></small>
                                        <br><br>
                                        <?php if($d->status == '0'): ?>
                                            <a href="<?php echo e(route('editizinkhusus', $d->id)); ?>" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('deleteizinkhusus')); ?>" method="POST" class="formdelete d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($d->id); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger ms-2 p-1">
                                                <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                                            </button>
                                        </form>
                                    </div>
                                    <div>
                                        <?php if($d->status == 0): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif($d->status == 1): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif($d->status == 2): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="fab-button bottom-right" style="margin-bottom: 70px;">
            <a href="/presensi/buatizinkhusus" class="fab">
                <ion-icon name="add-outline"></ion-icon>
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<!-- <?php $__env->startPush('myscript'); ?>
    <script>
        $(document).ready(function () {
            $('.formdelete').on('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                })
            });
        });
    </script>
<?php $__env->stopPush(); ?> -->
<?php echo $__env->make('layouts.attendance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/izinkhusus.blade.php ENDPATH**/ ?>