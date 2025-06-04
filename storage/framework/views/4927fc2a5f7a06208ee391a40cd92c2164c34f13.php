
<?php $__env->startSection('content'); ?>
    <style>
        td.d-flex {
            display: flex;
            gap: 4px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Pegawai</h4>
                        <div class="row">
                            <div class="col">
                                <?php if(Session::get('success')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(Session::get('success')); ?>

                                    </div>
                                <?php endif; ?>

                                <?php if(Session::get('warning')): ?>
                                    <div class="alert alert-warning">
                                        <?php echo e(Session::get('warning')); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <div class="general-button col-lg-11 text-right">
                                    <a href="#">
                                        <button type="button" class="btn mb-1 btn-primary" id="btnTambahPegawai">Tambah
                                            Akun</button>
                                    </a>
                                </div>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No. HP</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $pegawai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $path = Storage::url('upload/pegawai/' . $d->foto);
                                        ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($d->nik); ?></td>
                                            <td><?php echo e($d->username); ?></td>
                                            <td><?php echo e($d->nama_lengkap); ?></td>
                                            <td><?php echo e($d->jabatan); ?></td>
                                            <td><?php echo e($d->no_hp); ?></td>
                                            <td>
                                                <?php if(empty($d->foto)): ?>
                                                    <img width="35" src="<?php echo e(asset('assets/img/sample/avatar/avatar1.jpg')); ?>"
                                                        class="rounded-circle" alt="">
                                                <?php else: ?>
                                                    <img width="35" src="<?php echo e(url($path)); ?>" class="rounded-circle" alt="">
                                                <?php endif; ?>
                                            </td>
                                            <td class="d-flex gap-1">

                                                <!-- Tombol Edit -->
                                                <a href="#" class="edit" nik="<?php echo e($d->nik); ?>" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <button type="button" class="btn btn-warning btn-sm">
                                                        <ion-icon name="pencil-outline" style="font-size: 15px;"></ion-icon>
                                                    </button>
                                                </a>

                                                <!-- Tombol Delete -->
                                                <form action="<?php echo e(url('/pegawai/delete')); ?>" method="POST" class="form-delete"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="nik" value="<?php echo e($d->nik); ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                                        <ion-icon name="trash-outline" style="font-size: 15px;"></ion-icon>
                                                    </button>
                                                </form>

                                                <!-- Tombol Reset Password -->
                                                <form action="<?php echo e(url('/pegawai/resetPassword')); ?>" method="POST"
                                                    class="form-resetPassword" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Reset Password">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="nik" value="<?php echo e($d->nik); ?>">
                                                    <button type="submit" class="btn btn-info btn-sm">
                                                        <ion-icon name="lock-closed-outline"
                                                            style="font-size: 15px;"></ion-icon>
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Username</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No. HP</th>
                                        <th>foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-inputPegawai" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/pegawai/store" method="POST" id="frmPegawai" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="nik" name="nik" placeholder="NIK">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="username" name="username"
                                placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="nama_lengkap" name="nama_lengkap"
                                placeholder="Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="jabatan" name="jabatan"
                                placeholder="Jabatan">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="no_hp" name="no_hp"
                                placeholder="No HP">
                        </div>
                        <div class="form-group">
                            <label for="foto">Foto</label>
                            <input type="file" name="foto" class="form-control-file">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- modal edit -->
    <div class="modal fade" id="modal-editPegawai" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pegawai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="loadeditform">

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('myscript'); ?>
    <script>
        $(function () {
            $("#btnTambahPegawai").click(function () {
                $("#modal-inputPegawai").modal("show");
            });
            $(".edit").click(function () {
                var nik = $(this).attr('nik');
                $("#modal-editPegawai").modal("show");
                $.ajax({
                    type: 'POST',
                    url: '/pegawai/edit',
                    cache: false,
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        nik: nik
                    },
                    success: function (respond) {
                        $("#loadeditform").html(respond);
                    }
                })
            });
            $("#frmPegawai").submit(function () {
                var nik = $("#nik").val();
                var nama_lengkap = $("#nama_lengkap").val();
                var jabatan = $("#jabatan").val();
                var no_hp = $("#no_hp").val();
                if (nik == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'NIK harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#nik").focus();
                    })
                    return false;
                } else if (username == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Username harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#username").focus();
                    })
                    return false;
                } else if (nama_lengkap == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Lengkap harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#nama_lengkap").focus();
                    })
                    return false;
                } else if (jabatan == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jabatan harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#jabatan").focus();
                    })
                    return false;
                } else if (no_hp == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'No Hp harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#no_hp").focus();
                    })
                    return false;
                }
            });

            // SweetAlert konfirmasi hapus
            $('.form-delete').on('submit', function (e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pegawai akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });

            $('.form-resetPassword').on('submit', function (e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Password pegawai akan direset!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });

        })
    </script>

    <script>
        // Aktifkan semua tooltip
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/pegawai/index.blade.php ENDPATH**/ ?>