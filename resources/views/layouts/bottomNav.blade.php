<div class="appBottomMenu">
        <a href="/dashboard" class="item {{ request()->is('dashboard')? 'active' : '' }}">
            <div class="col">
            <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="{{ route('histori') }}" class="item {{ request()->is('presensi/histori')? 'active' : '' }}" >
            <div class="col">
                <ion-icon name="calendar-outline" role="img" class="md hydrated"
                    aria-label="calendar outline"></ion-icon>
                <strong>Histori</strong>
            </div>
        </a>
        <a href="{{ route('create') }}" class="item">
            <div class="col">
                <div class="action-button large">
                    <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                </div>
            </div>
        </a>
        <a href="{{ route('izin') }}" class="item {{ request()->is('presensi/izin')? 'active' : '' }}" >
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Izin</strong>
            </div>
        </a>
        <a href="/editProfile" class="item {{ request()->is('editProfile')? 'active' : '' }}" >
            <div class="col">
                <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
    </div>