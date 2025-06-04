
<?php $__env->startSection('header'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        .datepicker-modal {
            max-height: 430px !important;
        }

        .datepicker-date-display {
            background-color: #4169E1 !important;
        }
    </style>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin</div>
        <div class="right"></div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <form method="POST" action="<?php echo e(route('updateizin')); ?>" id="frmIzin" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo e($izin->id); ?>">
            <div class="form-group">
                <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" value="<?php echo e($izin->tgl_izin); ?>" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Izin / Sakit</option>
                    <option value="i" <?php echo e($izin->status == 'i' ? 'selected' : ''); ?>>Izin</option>
                    <option value="s" <?php echo e($izin->status == 's' ? 'selected' : ''); ?>>Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"><?php echo e($izin->keterangan); ?></textarea>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="gambar" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                            <i>Tap to Upload</i>
                            <i>(Bukti)</i>
                        </strong>
                    </span>
                </label>
            </div>
            <?php if($izin->gambar): ?>
                <p><strong>Gambar saat ini:</strong> <a href="<?php echo e(asset('storage/' . $izin->gambar)); ?>" target="_blank">Lihat Gambar</a></p>
            <?php endif; ?>
            <div class="form-group">
                <button class="btn btn-primary w-100">Update</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('myscript'); ?>
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function () {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.attendance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/editizin.blade.php ENDPATH**/ ?>