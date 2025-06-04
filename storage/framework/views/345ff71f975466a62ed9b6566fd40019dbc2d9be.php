<table>
    <tr>
        <th colspan="4">Laporan Presensi Pegawai</th>
    </tr>
    <tr>
        <td>Nama</td>
        <td><?php echo e($pegawai->nama_lengkap); ?></td>
    </tr>
    <tr>
        <td>NIK</td>
        <td><?php echo e($pegawai->nik); ?></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td><?php echo e($namabulan[$bulan]); ?> <?php echo e($tahun); ?></td>
    </tr>
</table>

<br>
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

<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Keterangan</th>
            <th>Jumlah Jam</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $izinTanggal = $izinMasukSiang[$a->nik] ?? collect([]);
                $punyaIzinMasukSiang = $izinTanggal->contains(function ($izin) use ($a) {
                    return $izin->tanggal == $a->tgl_presensi;
                });
            ?>
            <tr>
                <td><?php echo e(date('d-m-Y', strtotime($a->tgl_presensi))); ?></td>
                <td><?php echo e($a->jam_in); ?></td>
                <td><?php echo e($a->jam_out ?? '-'); ?></td>
                <td>
                    <?php if($punyaIzinMasukSiang): ?>
                        Izin Masuk Siang
                    <?php elseif($a->jam_in > $jamkantor): ?>
                        <?php
                            $jamterlambat = \Carbon\Carbon::parse($jamkantor)->diff(\Carbon\Carbon::parse($a->jam_in))->format('%H:%I');
                        ?>
                        Terlambat <?php echo e($jamterlambat); ?>

                    <?php else: ?>
                        Tepat Waktu
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($a->jam_out): ?>
                        <?php echo e(selisih($a->jam_in, $a->jam_out)); ?>

                    <?php else: ?>
                        0
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/presensi/lapresensiexcel.blade.php ENDPATH**/ ?>