@extends('layouts.attendance')
@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <style>
        .datepicker-modal {
            max-height: 430px !important;
        }

        .datepicker-date-display {
            background-color: #4169E1 !important;
        }
    </style>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin Cuti</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div style="height: calc(100vh - 60px); overflow-y: auto;">
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <form method="POST" action="/izincuti/store" id="frmIzin" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" id="tgl_izin_dari" name="tgl_izin_dari" class="form-control datepicker" placeholder="Dari">
                </div>
                <div class="form-group">
                    <input type="text" id="tgl_izin_sampai" name="tgl_izin_sampai" class="form-control datepicker" placeholder="Sampai">
                </div>
                <div class="form-group">
                    <input type="text" id="jml_hari" name="jml_hari" class="form-control" placeholder="Jumlah Hari" readonly>
                </div>
                <div class="form-group">
                    <select name="kode_cuti" id="kode_cuti" class="form-control">
                        <option value="">Pilih Kategori Cuti</option>
                        @foreach ( $mastercuti as $c )
                        <option value="{{ $c->kode_cuti }}">{{ $c->nama_cuti }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="max_cuti" name="max_cuti" class="form-control" placeholder="Max Cuti / Sisa Cuti" readonly>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan *maks: 225 karakter"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push ('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function () {
            $(".datepicker").datepicker({
                format: "yyyy-mm-dd"
            });

            function loadjumlahhari() {
                var dari = $("#tgl_izin_dari").val(); 
                var sampai = $("#tgl_izin_sampai").val();
                var date1 = new Date(dari); 
                var date2 = new Date(sampai);
                
                var Difference_In_Time = date2.getTime() - date1.getTime();

                var Diffrence_In_Days = Difference_In_Time / (1000 * 3600 * 24);

                if (dari == "" || sampai == "") {
                    var jmlhari = 0;
                } else {
                    var jmlhari = Diffrence_In_Days + 1;
                }

                $("#jml_hari").val(jmlhari + " Hari");
            }

            $("#tgl_izin_dari,#tgl_izin_sampai").change(function(e) {
                loadjumlahhari();
            });

            // $("#tgl_izin").change(function (e) {
            //     var tgl_izin = $(this).val();
            //     $.ajax({
            //         type: 'POST',
            //         url: '/presensi/cekpengajuanizin',
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             tgl_izin: tgl_izin
            //         },
            //         cache: false,
            //         success: function (respond) {
            //             if (respond == 1) {
            //                 Swal.fire({
            //                     title: 'Oops !',
            //                     text: 'Tanggal Tersebut Sudah Pernah Anda Ajukan ',
            //                     icon: 'warning'
            //                 }).then((result) => {
            //                     $("#tgl_izin").val("");
            //                 });
            //             }
            //         }
            //     });
            // });

            $("#frmIzin").submit(function (e) {
                e.preventDefault();
                var tgl_izin_dari = $("#tgl_izin_dari").val();
                var tgl_izin_sampai = $("#tgl_izin_sampai").val();
                var kode_cuti = $('#kode_cuti').val();
                var jml_hari = $('#jml_hari').val();
                var max_cuti = $('#max_cuti').val();
                var keterangan = $("#keterangan").val();
                if (tgl_izin_dari == "" || tgl_izin_sampai == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Tanggal harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (kode_cuti == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Kategori Cuti harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (keterangan == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Keterangan harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (keterangan.length > 255) {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Keterangan tidak boleh lebih dari 255 karakter',
                        icon: 'warning'
                    });
                    return false;
                } else if (keterangan.length > 255) {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Keterangan tidak boleh lebih dari 255 karakter',
                        icon: 'warning'
                    });
                    return false;
                } else if (parseInt(jml_hari) > parseInt(max_cuti)) {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Jumlah Hari Cuti Tidak Boleh Melebihi ' + max_cuti + " Hari",
                        icon: 'warning'
                    });
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "/izinabsen/cekpengajuan",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tgl_izin_dari: tgl_izin_dari,
                        tgl_izin_sampai: tgl_izin_sampai
                    },
                    success: function (response) {
                        if (response.ada) {
                            Swal.fire({
                                title: 'Sudah Pernah Diajukan',
                                text: 'Anda sudah mengajukan izin pada rentang tanggal tersebut sebelumnya.',
                                icon: 'warning'
                            });
                        } else {
                            // Jika belum pernah, submit form manual
                            $("#frmIzin")[0].submit();
                        }
                    }
                });
            });

            $("#kode_cuti").change(function(e) {
                var kode_cuti = $(this).val();
                var tgl_izin_dari = $("#tgl_izin_dari").val();
                $.ajax({
                    url: '/izincuti/getmaxcuti',
                    type: 'POST',
                    data : {
                        _token: "{{ csrf_token() }}",
                        kode_cuti: kode_cuti,
                        tgl_izin_dari: tgl_izin_dari
                    },
                    cache: false,
                    success: function(respond) {
                        $('#max_cuti').val(respond.max_cuti);
                    }
                });
            });
        });
    </script>
@endpush