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
        <div class="pageTitle">Form Izin Sakit</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div style="height: calc(100vh - 60px); overflow-y: auto;">
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <form method="POST" action="/izinsakit/store" id="frmIzin" enctype="multipart/form-data">
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
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan *maks: 225 karakter"></textarea>
                </div>
                <div class="custom-file-upload" id="fileUpload1">
                    <input type="file" name="gambar" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                    <label for="fileuploadInput">
                        <span>
                            <strong>
                                <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                    aria-label="cloud upload outline"></ion-icon>
                                <i>Tap to Upload</i>
                                <i>(Bukti)</i>
                            </strong>
                        </span>
                    </label>
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
                var keterangan = $("#keterangan").val();
                if (tgl_izin_dari == "" || tgl_izin_sampai == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Tanggal harus diisi',
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
        });
    </script>
@endpush