@extends('layouts.attendance')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin Khusus</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div style="height: calc(100vh - 60px); overflow-y: auto;">
        <div class="row" style="margin-top: 70px;">
            <div class="col">
                @php
                    $messagesuccess = Session::get('success');
                    $messageerror = Session::get('error');
                @endphp
                @if (Session::get('success'))
                    <div class="alert alert-success">
                        {{ $messagesuccess }}
                    </div>
                @endif
                @if (Session::get('error'))
                    <div class="alert alert-danger">
                        {{ $messageerror }}
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
                 @foreach ($izinkhusus as $d)
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div>
                                        <b>{{ date("d-m-Y", strtotime($d->tanggal)) }}
                                            ({{ $d->jenis_izin }})</b><br>
                                        <small class="text">{{ $d->jam_izin }}</small><br>
                                        <small class="text-muted">{{ $d->alasan }}</small>
                                        <br><br>
                                        @if ($d->status == '0')
                                            <a href="{{ route('editizinkhusus', $d->id) }}" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        @endif
                                        <form action="{{ route('deleteizinkhusus') }}" method="POST" class="formdelete d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $d->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger ms-2 p-1">
                                                <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                                            </button>
                                        </form>
                                    </div>
                                    <div>
                                        @if ($d->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($d->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($d->status == 2)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
        <div class="fab-button bottom-right" style="margin-bottom: 70px;">
            <a href="/presensi/buatizinkhusus" class="fab">
                <ion-icon name="add-outline"></ion-icon>
            </a>
        </div>
    </div>
@endsection
<!-- @push('myscript')
    <script>
        $(document).ready(function () {
            $('.formdelete').on('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                })
            });
        });
    </script>
@endpush -->