
<?php $__env->startSection('header'); ?>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Profile</div>
        <div class="right"></div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row" style="margin-top:4rem">
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
    <form action="/presensi/<?php echo e($pegawai->nik); ?>/updateProfile" method="POST" enctype="multipart/form-data"
        style="margin-top:1rem; padding-bottom:100px">
        <?php echo csrf_field(); ?>
        <div class="col">
            <div class="form-group boxed">
                <label>Nama/No Telp</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="<?php echo e($pegawai->nama_lengkap); ?>" name="nama_lengkap"
                        placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="<?php echo e($pegawai->no_hp); ?>" name="no_hp" placeholder="No. HP"
                        autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <label>Ubah Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="old_password" placeholder="Password Lama">
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="new_password" placeholder="Password Baru">
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="confirm_new_password"
                        placeholder="Konfirmasi Password Baru">
                </div>
            </div>

            <label>Foto Profile</label>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload</i>
                        </strong>
                    </span>
                </label>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.attendance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/editProfile.blade.php ENDPATH**/ ?>