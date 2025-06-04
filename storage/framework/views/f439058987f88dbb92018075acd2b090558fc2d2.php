
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3>Data Izin Khusus</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jabatan</th>
                                                <th>Jenis Izin</th>
                                                <th>Jam Izin</th>
                                                <th>Keterangan</th>
                                                <th>Status Approve</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $dataizinkhusus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($loop->iteration); ?></td>
                                                    <td><?php echo e(date('d-m-Y', strtotime($d->tanggal))); ?></td>
                                                    <td><?php echo e($d->nik); ?></td>
                                                    <td><?php echo e($d->nama_lengkap); ?></td>
                                                    <td><?php echo e($d->jabatan); ?></td>
                                                    <td><?php echo e($d->jenis_izin); ?></td>
                                                    <td><?php echo e($d->jam_izin); ?></td>
                                                    <td><?php echo e($d->alasan); ?></td>
                                                    <td>
                                                        <?php if($d->status == 1): ?>
                                                            <span class="badge badge-success">Approved</span>
                                                        <?php elseif($d->status == 2): ?>
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning">pending</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($d->status == 0): ?>
                                                            <a href="#" class="btn-approve" id_izin="<?php echo e($d->id); ?>"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Persetujuan">
                                                                <button type="button"
                                                                    class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                                                                    style="width: 20px; height: 20px; padding: 0;">
                                                                    <ion-icon name="ellipsis-vertical-outline"
                                                                        style="font-size: 15px;"></ion-icon>
                                                                </button>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="/presensi/<?php echo e($d->id); ?>/batalkanizinkhusus" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Batalkan">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                                                                    style="width: 20px; height: 20px; padding: 0;">
                                                                    <ion-icon name="close-outline"
                                                                        style="font-size: 15px;"></ion-icon>
                                                                </button>
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jabatan</th>
                                                <th>Jenis Izin</th>
                                                <th>Jam Izin</th>
                                                <th>Keterangan</th>
                                                <th>Status Approve</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-dataizinkhusus" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Persetujuan Izin/Sakit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/presensi/approveizinkhusus" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id_izin_form" id="id_izin_form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status" id="status" class="form-control">
                                        <option selected="selected">Choose...</option>
                                        <option value="1">Disetujui</option>
                                        <option value="2">Ditolak</option>
                                    </select>
                                </div>
                            </div>
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
<?php $__env->stopSection(); ?>
<?php $__env->startPush('myscript'); ?>
    <script>
        $(function () {
            $(".btn-approve").on("click", function (e) {
                e.preventDefault();
                var id_izin = $(this).attr("id_izin");
                $(id_izin_form).val(id_izin);
                $("#modal-dataizinkhusus").modal("show");
            });
        });

        // Aktifkan semua tooltip
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/dataizinkhusus.blade.php ENDPATH**/ ?>