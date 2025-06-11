<?php
function selisih($jam_masuk, $jam_keluar)
{
    list($h, $m, $s) = explode(":", $jam_masuk);
    $dtAwal = mktime($h, $m, $s, "1", "1", "1");
    list($h, $m, $s) = explode(":", $jam_keluar);
    $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
    $dtSelisih = $dtAkhir - $dtAwal;
    $totalmenit = $dtSelisih / 60;
    $jam = explode(".", $totalmenit / 60);
    $sisamenit = ($totalmenit / 60) - $jam[0];
    $sisamenit2 = $sisamenit * 60;
    $jml_jam = $jam[0];
    return $jml_jam . ":" . round($sisamenit2);
}
?>
<?php $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
$bukti_in = Storage::url('upload/absensi/'.$d->bukti_in);
$bukti_out = Storage::url('upload/absensi/'.$d->bukti_out);
$jamKerja = DB::table('jam_kerja')->first();
$jamTepat = $jamKerja->jam_masuk;
$izin = DB::table('izin_khusus')
        ->where('nik', $d->nik)
        ->where('tanggal', $d->tgl_presensi)
        ->where('status', '1') 
        ->first();
?>
<tr>
    <td><?php echo e($loop ->iteration); ?></td>
    <td><?php echo e($d ->nik); ?></td>
    <td><?php echo e($d ->nama_lengkap); ?></td>
    <td><?php echo e($d ->jabatan); ?></td>
    <td><?php echo e($d ->jam_in); ?></td>
    <td>
        <img width="70" src="<?php echo e(url($bukti_in)); ?>" alt="avatar">
    </td>
    <td><?php echo $d->jam_out != null ? $d->jam_out : '<span class="badge badge-danger">Belum Absen</span>'; ?></td>
    <td>
        <?php if($d->jam_out != null): ?>
            <img width="70" src="<?php echo e(url($bukti_out)); ?>" alt="avatar">
            <?php else: ?>
            <ion-icon name="hourglass-outline" style="font-size: 18px;"></ion-icon>
        <?php endif; ?>
    </td>
    <td>
        <?php if($izin): ?>
        <span class="badge badge-info">Izin (<?php echo e($izin->jenis_izin); ?>)</span>
        <?php elseif($d->jam_in > $jamTepat): ?>
        <?php
        $jamterlambat = selisih($jamTepat, $d->jam_in);
        ?>
        <span class="badge badge-danger">Terlambat <?php echo e($jamterlambat); ?></span>
        <?php else: ?>
        <span class="badge badge-success">Tepat Waktu</span>
        <?php endif; ?>
    </td>
    <td>
        <a href="#" class="btn mb-1 btn-primary showmap" id="<?php echo e($d->id); ?>">
            <ion-icon name="map-outline" style="font-size: 19px;"></ion-icon></a>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script>
    $(function() {
        $(".showmap").click(function(e) {
            var id = $(this).attr("id");
            $.ajax({
                type: "POST",
                url: '/showmap',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    id: id
                },
                cache:false,
                success:function(respond) {
                    $("#loadmap").html(respond);
                }
            })
            $("#modal-showmap").modal("show");
        });
    });
</script><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/getpresensi.blade.php ENDPATH**/ ?>