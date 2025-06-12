<div class="header">    
            <div class="header-content clearfix">
                
                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
                <!-- <div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3" id="basic-addon1"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Search Dashboard" aria-label="Search Dashboard">
                        <div class="drop-down animated flipInX d-md-none">
                            <form action="#">
                                <input type="text" class="form-control" placeholder="Search">
                            </form>
                        </div>
                    </div>
                </div> -->
                <div class="header-right">
                    <ul class="clearfix">
                        <li class="icons dropdown"> 
                            <a href="javascript:void(0)" data-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                <!-- Tampilkan badge hanya jika ada notifikasi -->
                                <?php if(count(auth()->guard('user')->user()->unreadNotifications) > 0): ?>
                                    <span
                                        class="badge badge-pill gradient-2"><?php echo e(count(auth()->guard('user')->user()->unreadNotifications)); ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
                                <div class="dropdown-content-heading d-flex justify-content-between">
                                    <?php if(count(auth()->guard('user')->user()->unreadNotifications) > 0): ?>
                                        <span><?php echo e(count(auth()->guard('user')->user()->unreadNotifications)); ?> New Notifications</span>
                                    <?php else: ?>
                                        <span>No Notifications</span>
                                    <?php endif; ?>
                                </div>
                                <div class="dropdown-content-body">
                                    <ul>
                                        <?php $__currentLoopData = auth()->guard('user')->user()->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <a href="<?php echo e(route('notifications.read', ['id' => $notification->id])); ?>">
                                                    <span class="mr-3 avatar-icon bg-success-lighten-2"><i
                                                            class="icon-present"></i></span>
                                                    <div class="notification-content">
                                                        <h6 class="notification-heading">
                                                            <?php echo e($notification->data['messages']); ?>

                                                        </h6>
                                                        <span
                                                            class="notification-text"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                
                                    <?php if(count(auth()->guard('user')->user()->unreadNotifications) > 5): ?>
                                        <div class="text-center mt-3">
                                            <a href="<?php echo e(route('historinotif.index')); ?>" class="text-primary">Lihat Semua
                                                Notifikasi</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <!-- <li class="icons dropdown d-none d-md-flex">
                            <a href="javascript:void(0)" class="log-user"  data-toggle="dropdown">
                                <span>English</span>  <i class="fa fa-angle-down f-s-14" aria-hidden="true"></i>
                            </a>
                            <div class="drop-down dropdown-language animated fadeIn  dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li><a href="javascript:void()">English</a></li>
                                        <li><a href="javascript:void()">Dutch</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li> -->
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                                <!-- <span class="activity active"></span> -->
                                <img src="<?php echo e(asset('admin/images/LOGO-YPB_BULAT.png')); ?>" height="40" width="40" alt="">
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="#"><i class="icon-user"></i> <span>Profile</span></a>
                                        </li>
                                        <li><a href="/logoutadmin"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div><?php /**PATH C:\xampp\htdocs\attendance-ypb\resources\views/layouts/admin/header.blade.php ENDPATH**/ ?>