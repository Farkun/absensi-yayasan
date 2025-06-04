<form action="/cuti/<?php echo e($cuti->kode_cuti); ?>/update" method="POST" id="frmCuti" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <input type="text" value="<?php echo e($cuti->kode_cuti); ?>" class="form-control input-default" id="kode_cuti" name="kode_cuti" placeholder="Kode Cuti" readonly>
    </div>
    <div class="form-group">
        <input type="text" value="<?php echo e($cuti->nama_cuti); ?>" class="form-control input-default" id="nama_cuti" name="nama_cuti" placeholder="Nama Cuti">
    </div>
    <div class="form-group">
        <input type="text" value="<?php echo e($cuti->jml_hari); ?>" class="form-control input-default" id="jml_hari" name="jml_hari" placeholder="Jumlah Hari">
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/cuti/edit.blade.php ENDPATH**/ ?>