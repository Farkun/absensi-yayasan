@extends('layouts.admin.admin')
@section('content')
    <style>
        td.d-flex {
            display: flex;
            gap: 4px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Cuti</h4>
                        <div class="row">
                            <div class="col">
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <div class="general-button col-lg-11 text-right">
                                    <a href="#">
                                        <button type="button" class="btn mb-1 btn-primary" id="btnTambahCuti">Tambah Data Cuti</button>
                                    </a>
                                </div>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Cuti</th>
                                        <th>Nama Cuti</th>
                                        <th>Jumlah Hari</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $cuti as $d )
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $d->kode_cuti }}</td>
                                        <td>{{ $d->nama_cuti }}</td>
                                        <td>{{ $d->jml_hari }}</td>
                                        <td class="d-flex gap-1">

                                                <!-- Tombol Edit -->
                                                <a href="#" class="edit" kode_cuti="{{ $d->kode_cuti }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <button type="button" class="btn btn-warning btn-sm">
                                                        <ion-icon name="pencil-outline" style="font-size: 15px;"></ion-icon>
                                                    </button>
                                                </a>

                                                <!-- Tombol Delete -->
                                                <form action="{{ url('/cuti/delete') }}" method="POST" class="form-delete"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    @csrf
                                                    <input type="hidden" name="kode_cuti" value="{{ $d->kode_cuti }}">
                                                    <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                                        <ion-icon name="trash-outline" style="font-size: 15px;"></ion-icon>
                                                    </button>
                                                </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Cuti</th>
                                        <th>Nama Cuti</th>
                                        <th>Jumlah Hari</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-inputcuti" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/cuti/store" method="POST" id="frmCuti" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="kode_cuti" name="kode_cuti" placeholder="Kode Cuti">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="nama_cuti" name="nama_cuti"
                                placeholder="Nama Cuti">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control input-default" id="jml_hari" name="jml_hari"
                                placeholder="Jumlah Hari">
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

    <!-- modal edit -->
    <div class="modal fade" id="modal-editcuti" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="loadeditform">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            $("#btnTambahCuti").click(function () {
                $("#modal-inputcuti").modal("show");
            });
            $(".edit").click(function () {
                var kode_cuti = $(this).attr('kode_cuti');
                $.ajax({
                    type: 'POST',
                    url: '/cuti/edit',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        kode_cuti: kode_cuti
                    },
                    success: function (respond) {
                        $("#loadeditform").html(respond);
                    }
                })
                $("#modal-editcuti").modal("show");
            });
            $("#frmCuti").submit(function () {
                var kode_cuti = $("#kode_cuti").val();
                var nama_cuti = $("#nama_cuti").val();
                var jml_hari = $("#jml_hari").val();
                if (kode_cuti == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Kode Cuti harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#kode_cuti").focus();
                    })
                    return false;
                } else if (nama_cuti == "") {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Nama Cuti harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#nama_cuti").focus();
                    })
                    return false;
                } else if (jml_hari == "" || jml_hari == 0) {
                    Swal.fire({
                        title: 'Warning!',
                        text: 'Jumlah Hari harus Diisi',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $("#jml_hari").focus();
                    })
                    return false;
                } 
            });

            // SweetAlert konfirmasi hapus
            $('.form-delete').on('submit', function (e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pegawai akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });

        })
    </script>

    <script>
        // Aktifkan semua tooltip
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush