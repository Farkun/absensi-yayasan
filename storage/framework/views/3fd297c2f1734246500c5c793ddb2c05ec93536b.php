
<?php $__env->startSection('header'); ?>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Sakit</div>
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
        <style>
            .historicontent {
                display: flex;
            }

            .datapresensi {
                margin-left: 10px;
            }
        </style>
        <div class="row">
            <div class="col">
                <?php $__currentLoopData = $dataizin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                if($d->status=="i"){
                $status = "Izin";
                } elseif ($d->status=="s"){
                $status = "Sakit";
                } elseif ($d->status=="c"){
                $status = "Cuti";
                } else {
                $status = "Not Found";
                } 
                ?>
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div>
                                        <?php if($d->status == "i"): ?>
                                        <ion-icon name="document-text-outline"></ion-icon>
                                        <?php elseif($d->status == "s"): ?>
                                        <ion-icon name="medkit-outline"></ion-icon>
                                        <?php elseif($d->status == "c"): ?>
                                        <ion-icon name="calendar-outline"></ion-icon>
                                        <?php endif; ?>
                                        <b><?php echo e(date("d-m-Y", strtotime($d->tgl_izin_dari))); ?>

                                            (<?php echo e($status); ?>)</b><br>
                                        <small class="text-muted"><?php echo e(date("d-M-Y", strtotime($d->tgl_izin_dari))); ?> s/d <?php echo e(date("d-M-Y", strtotime($d->tgl_izin_sampai))); ?></small>
                                        <?php if($d->status == "c"): ?>
                                        <br>
                                        <span class="badge bg-warning"><?php echo e($d->nama_cuti); ?></span>
                                        <br>
                                        <?php endif; ?>
                                        <p><?php echo e($d->keterangan); ?></p>
                                        <br><br>
                                        <?php if($d->gambar): ?>
                                            <a href="<?php echo e(asset('storage/' . $d->gambar)); ?>" class="btn btn-sm btn-success ms-2 p-1"
                                                download>
                                                <ion-icon name="download-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($d->status == "i" && $d->status_approved == '0'): ?>
                                            <a href="<?php echo e(route('edit_izin', $d->id)); ?>" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        <?php elseif($d->status == "s" && $d->status_approved == '0'): ?>
                                            <a href="<?php echo e(route('edit_sakit', $d->id)); ?>" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        <?php elseif($d->status == "c" && $d->status_approved == '0'): ?>
                                            <a href="<?php echo e(route('edit_cuti', $d->id)); ?>" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        <?php endif; ?>

                                        <?php if($d->status_approved == '2'): ?>
                                        <form action="<?php echo e(route('delete_izin')); ?>" method="POST" class="formdelete d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($d->id); ?>">
                                            <button type="submit" class="btn btn-sm btn-danger ms-2 p-1">
                                                <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <?php if($d->status_approved == 0): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif($d->status_approved == 1): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif($d->status_approved == 2): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                        <p style="margin: top 5px; font-weight: bold;"><?php echo e(hitunghari($d->tgl_izin_dari,$d->tgl_izin_sampai)); ?> Hari</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <!-- <div class="fab-button bottom-right" style="margin-bottom: 70px;">
            <a href="/presensi/buatizin" class="fab">
                <ion-icon name="add-outline"></ion-icon>
            </a>
        </div> -->
        <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px;">
            <a href="#" class="fab bg-primary" data-toggle="dropdown">
                <ion-icon name="add-outline" role="img" class="md-hydrated" aria-label="add outline"></ion-icon>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item bg-primary" href="/izinabsen">
                    <ion-icon name="document-outline" role="img" class="md-hydrated" aria-label="image outline"></ion-icon>
                    <p>Izin Absen</p>
                </a>
                <a class="dropdown-item bg-primary" href="/izinsakit">
                    <ion-icon name="document-outline" role="img" class="md-hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Sakit</p>
                </a>
                <a class="dropdown-item bg-primary" href="/izincuti">
                    <ion-icon name="document-outline" role="img" class="md-hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Cuti</p>
                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('myscript'); ?>
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.attendance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/izin.blade.php ENDPATH**/ ?>