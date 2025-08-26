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
                                @if(count(auth()->guard('user')->user()->unreadNotifications) > 0)
                                    <span
                                        class="badge badge-pill gradient-2">{{ count(auth()->guard('user')->user()->unreadNotifications) }}</span>
                                @endif
                            </a>
                            <div class="drop-down animated fadeIn dropdown-menu dropdown-notfication">
                                <div class="dropdown-content-heading d-flex justify-content-between">
                                    @if(count(auth()->guard('user')->user()->unreadNotifications) > 0)
                                        <span>{{ count(auth()->guard('user')->user()->unreadNotifications) }} New Notifications</span>
                                    @else
                                        <span>No Notifications</span>
                                    @endif
                                </div>
                                <div class="dropdown-content-body">
                                    <ul>
                                        @foreach(auth()->guard('user')->user()->unreadNotifications as $notification)
                                            <li>
                                                <a href="{{ route('notifications.read', ['id' => $notification->id]) }}">
                                                    <span class="mr-3 avatar-icon bg-success-lighten-2"><i
                                                            class="icon-present"></i></span>
                                                    <div class="notification-content">
                                                        <h6 class="notification-heading">
                                                            {{ $notification->data['messages'] }}
                                                        </h6>
                                                        <span
                                                            class="notification-text">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                
                                    @if(count(auth()->guard('user')->user()->unreadNotifications) > 5)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('historinotif.index') }}" class="text-primary">Lihat Semua
                                                Notifikasi</a>
                                        </div>
                                    @endif
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
                                <!-- <img src="{{ asset('admin/images/LOGO-YPB_BULAT.png') }}" height="40" width="40" alt=""> -->
                                <img src="{{ asset('assets/img/logo-bhs.png') }}" height="40" width="40" alt="">
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
        </div>