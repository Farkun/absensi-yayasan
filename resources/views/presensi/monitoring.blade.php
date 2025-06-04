@extends('layouts.admin.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="example">
                                    <h5 class="box-title m-t-30">Monitoring Presensi</h5>
                                    <p class="text-muted m-b-20">Pilih Tanggal</p>
                                    <div class="input-group">
                                        <input type="text" id="tanggal" value="{{ date("Y-m-d") }}" name="tanggal"
                                            class="form-control mydatepicker" placeholder="mm/dd/yyyy"> <span
                                            class="input-group-append"><span class="input-group-text"><i
                                                    class="mdi mdi-calendar-check"></i></span></span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jabatan</th>
                                                <th>Jam Masuk</th>
                                                <th>Bukti Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Bukti Pulang</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="loadpresensi">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jabatan</th>
                                                <th>Jam Masuk</th>
                                                <th>Bukti Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Bukti Pulang</th>
                                                <th>keterangan</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-showmap" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lokasi Presensi Pegawai</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="loadmap">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            function loadpresensi() {
                var tanggal = $("#tanggal").val();
                $.ajax({
                    type: 'POST',
                    url: '/getpresensi',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function (respond) {
                        $("#loadpresensi").html(respond);
                    }
                });
            }
            $("#tanggal").change(function (e) {
                loadpresensi();
            });

            loadpresensi();
        }); 
    </script>
@endpush