
<?php $__env->startSection('content'); ?>
    <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 30px;
            text-decoration: none;
            right: 8px;
        }

        .logout:hover {
            color: white;
        }
    </style>
    <div class="section" id="user-section">
        <a href="/logout" class="logout">
            <ion-icon name="exit-outline"></ion-icon>
        </a>
        <div id="user-detail">
            <div class="avatar">
                <?php if(!empty(Auth::guard('pegawai')->user()->foto)): ?>
                    <?php
                        $path = Storage::url('upload/pegawai/' . Auth::guard('pegawai')->user()->foto);
                    ?>
                    <img src="<?php echo e(url($path)); ?>" alt="avatar" class="imaged 64w rounded" style="height:60px">
                <?php else: ?>
                    <img src="<?php echo e(asset('assets/img/sample/avatar/avatar1.jpg')); ?>" alt="avatar" class="imaged w64 rounded">
                <?php endif; ?>
            </div>
            <div id="user-info">
                <h2 id="user-name"><?php echo e(Auth::guard('pegawai')->user()->nama_lengkap); ?></h2>
                <span id="user-role"><?php echo e(Auth::guard('pegawai')->user()->jabatan); ?></span>
            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editProfile" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="<?php echo e(route('izin')); ?>" class="danger">
                                <div style="position: relative; display: inline-block;">
                                    <ion-icon name="calendar-number" style="font-size: 40px;"></ion-icon>
                                    <?php if($rekapizin->jmlcuti > 0): ?>
                                        <span class="badge bg-danger"
                                            style="position: absolute; top: -5px; right: -10px; font-size: 0.65rem; padding: 4px 6px;
                                                border-radius: 50%; line-height: 1; z-index: 10;">
                                            <?php echo e($rekapizin->jmlcuti); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>       
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Cuti</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="<?php echo e(route('histori')); ?>" class="warning" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Histori</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="<?php echo e(route('izinkhusus')); ?>" class="orange" style="font-size: 40px;">
                                <!-- <ion-icon name="location"></ion-icon> -->
                                 <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Izin Khusus
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <?php if($presensihariini != null): ?>
                                        <?php
                                            $path = Storage::url('upload/absensi/' . $presensihariini->bukti_in);
                                        ?>
                                        <img src="<?php echo e(url($path)); ?>" alt="" class="imaged w64">
                                    <?php else: ?>
                                        <ion-icon name="camera"></ion-icon>
                                    <?php endif; ?>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span><?php echo e($presensihariini != null ? \Carbon\Carbon::parse($presensihariini->jam_in)->format('H:i') : "Belum Absen"); ?></span>
                                    <!-- <?php echo e($presensihariini != null ? $presensihariini->jam_in : "Belum Absen"); ?> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <?php if($presensihariini != null && $presensihariini->jam_out != null): ?>
                                        <?php
                                            $path = Storage::url('upload/absensi/' . $presensihariini->bukti_out);
                                        ?>
                                        <img src="<?php echo e(url($path)); ?>" alt="" class="imaged w64">
                                    <?php else: ?>
                                        <ion-icon name="camera"></ion-icon>
                                    <?php endif; ?>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span><?php echo e($presensihariini != null && $presensihariini->jam_out ?
                                    \Carbon\Carbon::parse($presensihariini->jam_out)->format('H:i') : "Belum Absen"); ?></span>
                                    <!-- <?php echo e($presensihariini != null && $presensihariini->jam_out? $presensihariini->jam_out : "Belum Absen"); ?> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="rekappresensi">
            <h3 class="text-center">Rekap Presensi Bulan <?php echo e($namabulan[$bulanini]); ?> Tahun <?php echo e($tahunini); ?></h3>
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 16px 12px !important">
                            <span class="badge bg-danger" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;
                                    z-index:999"><?php echo e($rekappresensi->jmlhadir); ?></span>
                            <ion-icon name="hand-left-outline" style="font-size: 1.5rem;"
                                class="text-success"></ion-icon><br>
                            <span>Hadir</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 16px 12px !important">
                            <span class="badge bg-danger" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;
                                    z-index:999"><?php echo e($rekapizin->jmlizin); ?></span>
                            <ion-icon name="newspaper-outline" style="font-size: 1.5rem;"
                                class="text-primary"></ion-icon><br>
                            <span>Izin</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 16px 12px !important">
                            <span class="badge bg-danger" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;
                                z-index:999"><?php echo e($rekapizin->jmlsakit); ?></span>
                            <ion-icon name="medkit-outline" style="font-size: 1.5rem;" class="text-warning"></ion-icon><br>
                            <span>Sakit</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 16px 12px !important">
                            <span class="badge bg-danger" style="position: absolute; top:5px; right:10px; font-size: 0.7rem;
                                z-index:999"><?php echo e($rekappresensi->jmlterlambat); ?></span>
                            <ion-icon name="time-outline" style="font-size: 1.5rem;" class="text-danger"></ion-icon><br>
                            <span>Telat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="presencetab mt-2">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <ul class="nav nav-tabs style1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                            Bulan Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                            Leaderboard
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content mt-2" style="margin-bottom:100px;">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    <ul class="listview image-listview">
                        <?php $__currentLoopData = $historibulanini; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="calendar-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div><?php echo e(date("d-m-Y", strtotime($d->tgl_presensi))); ?></div>
                                        <div>
                                            <span class="badge badge-success"><?php echo e($d->jam_in); ?></span><br>
                                            <span
                                                class="badge badge-danger mt-1"><?php echo e($d->jam_out != null ? $d->jam_out : 'Belum Absen'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <ul class="listview image-listview">
                        <?php $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>
                                            <b><?php echo e($d->nama_lengkap); ?></b><br>
                                            <small class="text-muted"><?php echo e($d->jabatan); ?></small>
                                        </div>
                                        <span class="badge <?php echo e($d->jam_in < $jamMasuk ? "bg-success" : "bg-danger"); ?>">
                                            <?php echo e($d->jam_in); ?>

                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.attendance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\BHF\absensi-yayasan\resources\views/dashboard/dashboard.blade.php ENDPATH**/ ?>