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
        <div class="pageTitle">Edit Izin Cuti</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div style="height: calc(100vh - 60px); overflow-y: auto;">
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <form method="POST" action="{{ route('update_cuti') }}" id="frmIzin" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $dataizin->id }}">
                <div class="form-group">
                    <input type="text" id="tgl_izin_dari" name="tgl_izin_dari" value="{{ $dataizin->tgl_izin_dari }}"  class="form-control datepicker" placeholder="Dari">
                </div>
                <div class="form-group">
                    <input type="text" id="tgl_izin_sampai" name="tgl_izin_sampai" value="{{ $dataizin->tgl_izin_sampai }}" class="form-control datepicker" placeholder="Sampai">
                </div>
                <div class="form-group">
                    <input type="text" id="jml_hari" name="jml_hari" class="form-control" placeholder="Jumlah Hari" readonly>
                </div>
                <div class="form-group">
                    <select name="kode_cuti" id="kode_cuti" class="form-control">
                        <option value="">Pilih Kategori Cuti</option>
                        @foreach ( $mastercuti as $c )
                        <option {{ $dataizin->kode_cuti == $c->kode_cuti ? 'selected' : '' }} value="{{ $c->kode_cuti }}">{{ $c->nama_cuti }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan *maks: 225 karakter">{{ $dataizin->keterangan }}</textarea>
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

            loadjumlahhari();

            $("#tgl_izin_dari,#tgl_izin_sampai").change(function(e) {
                loadjumlahhari();
            });
            $("#frmIzin").submit(function (e) {
                e.preventDefault();
                var tgl_izin_dari = $("#tgl_izin_dari").val();
                var tgl_izin_sampai = $("#tgl_izin_sampai").val();
                var kode_cuti = $("#kode_cuti").val();
                var keterangan = $("#keterangan").val();
                var id = $("#id").val();

                $.ajax({
                    type: "POST",
                    url: "/izinabsen/cekpengajuan",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tgl_izin_dari: tgl_izin_dari,
                        tgl_izin_sampai: tgl_izin_sampai,
                        id: id
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