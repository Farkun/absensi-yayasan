@extends('layouts.attendance')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Sakit</div>
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
        <style>
            .historicontent {
                display: flex;
            }

            .datapresensi {
                margin-left: 10px;
            }
        </style>
        <div class="row">
            <div class="col">
                @foreach ($dataizin as $d)
                @php
                if($d->status=="i"){
                $status = "Izin";
                } elseif ($d->status=="s"){
                $status = "Sakit";
                } elseif ($d->status=="c"){
                $status = "Cuti";
                } else {
                $status = "Not Found";
                } 
                @endphp
                    <ul class="listview image-listview">
                        <li>
                            <div class="item">
                                <div class="in">
                                    <div>
                                        @if ($d->status == "i")
                                        <ion-icon name="document-text-outline"></ion-icon>
                                        @elseif ($d->status == "s")
                                        <ion-icon name="medkit-outline"></ion-icon>
                                        @elseif ($d->status == "c")
                                        <ion-icon name="calendar-outline"></ion-icon>
                                        @endif
                                        <b>{{ date("d-m-Y", strtotime($d->tgl_izin_dari)) }}
                                            ({{ $status }})</b><br>
                                        <small class="text-muted">{{ date("d-M-Y", strtotime($d->tgl_izin_dari)) }} s/d {{ date("d-M-Y", strtotime($d->tgl_izin_sampai)) }}</small>
                                        @if ($d->status == "c")
                                        <br>
                                        <span class="badge bg-warning">{{ $d->nama_cuti }}</span>
                                        <br>
                                        @endif
                                        <p>{{ $d->keterangan }}</p>
                                        <br><br>
                                        @if ($d->gambar && file_exists(public_path('storage/' . $d->gambar)))
                                            <a href="{{ asset('storage/' . $d->gambar) }}" class="btn btn-sm btn-success ms-2 p-1"
                                                download>
                                                <ion-icon name="download-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        @endif
                                        @if ($d->status == "i" && $d->status_approved == '0')
                                            <a href="{{ route('edit_izin', $d->id) }}" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        @elseif ($d->status == "s" && $d->status_approved == '0')
                                            <a href="{{ route('edit_sakit', $d->id) }}" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        @elseif ($d->status == "c" && $d->status_approved == '0')
                                            <a href="{{ route('edit_cuti', $d->id) }}" class="btn btn-sm btn-warning ms-2 p-1">
                                                <ion-icon name="create-outline" style="font-size: 18px;"></ion-icon>
                                            </a>
                                        @endif

                                        @if ($d->status_approved == '2')
                                        <form action="{{ route('delete_izin') }}" method="POST" class="formdelete d-inline">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $d->id }}">
                                            <button type="submit" class="btn btn-sm btn-danger ms-2 p-1">
                                                <ion-icon name="trash-outline" style="font-size: 18px;"></ion-icon>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($d->status_approved == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($d->status_approved == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($d->status_approved == 2)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                        <p style="margin: top 5px; font-weight: bold;">{{ hitunghari($d->tgl_izin_dari,$d->tgl_izin_sampai) }} Hari</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
        <!-- <div class="fab-button bottom-right" style="margin-bottom: 70px;">
            <a href="/presensi/buatizin" class="fab">
                <ion-icon name="add-outline"></ion-icon>
            </a>
        </div> -->
        <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px;">
            <a href="#" class="fab bg-primary" data-toggle="dropdown">
                <ion-icon name="add-outline" role="img" class="md-hydrated" aria-label="add outline"></ion-icon>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item bg-primary" href="/izinabsen">
                    <ion-icon name="document-outline" role="img" class="md-hydrated" aria-label="image outline"></ion-icon>
                    <p>Izin Absen</p>
                </a>
                <a class="dropdown-item bg-primary" href="/izinsakit">
                    <ion-icon name="document-outline" role="img" class="md-hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Sakit</p>
                </a>
                <a class="dropdown-item bg-primary" href="/izincuti">
                    <ion-icon name="document-outline" role="img" class="md-hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Cuti</p>
                </a>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
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
@endpush