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
        <div class="pageTitle">Form Izin Khusus</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <form method="POST" action="/presensi/storeizinkhusus" id="frmIzinkhs" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="text" id="tanggal" name="tanggal" class="form-control datepicker" placeholder="Tanggal">
                </div>
                <div class="form-group">
                    <select name="jenis_izin" id="jenis_izin" class="form-control">
                        <option value="">Jenis Izin</option>
                        <option value="masuk siang">Masuk Siang</option>
                        <option value="pulang awal">Pulang Awal</option>
                        <option value="lembur">Lembur / Kerja Hari Libur </option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="time" id="jam_izin" name="jam_izin" class="form-control"
                        placeholder="Jam Izin(masuk/pulang)">
                </div>
                <div class="form-group">
                    <textarea name="alasan" id="alasan" cols="30" rows="5" class="form-control"
                        placeholder="Keterangan *maks: 225 karakter"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-prim ary w-100">Kirim</button>
                </div>
            </form>
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

            $("#tanggal").change(function (e) {
                var tanggal = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/presensi/cekpengajuanizinkhusus',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function (respond) {
                        if (respond == 1) {
                            Swal.fire({
                                title: 'Oops !',
                                text: 'Tanggal Tersebut Sudah Pernah Anda Ajukan ',
                                icon: 'warning'
                            }).then((result) => {
                                $("#tanggal").val("");
                            });
                        }
                    }
                });
            });

            $("#frmIzinkhs").submit(function () {
                var tanggal = $("#tanggal").val();
                var jenis_izin = $("#jenis_izin").val();
                var jam_izin = $("#jam_izin").val();
                var alasan = $("#alasan").val();
                var status = $("#status").val();

                var jamIzinVisible = $("#jam_izin").is(':visible');

                if (tanggal == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Tanggal harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (jenis_izin == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Jenis Izin harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (jamIzinVisible && jam_izin == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Jam Izin harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (alasan == "") {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Keterangan harus diisi',
                        icon: 'warning'
                    });
                    return false;
                } else if (alasan.length > 255) {
                    Swal.fire({
                        title: 'Oops !',
                        text: 'Keterangan tidak boleh lebih dari 255 karakter',
                        icon: 'warning'
                    });
                    return false;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const jenisIzin = document.getElementById('jenis_izin');
            const jamIzin = document.getElementById('jam_izin').closest('.form-group');

            function toggleJamIzin() {
                if (jenisIzin.value.toLowerCase() === 'lembur') {
                    jamIzin.style.display = 'none';
                } else {
                    jamIzin.style.display = '';
                }
            }

            jenisIzin.addEventListener('change', toggleJamIzin);
            toggleJamIzin(); // trigger saat load
        });
    </script>
@endpush