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
        <div class="pageTitle">Edit Sakit Absen</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div style="height: calc(100vh - 60px); overflow-y: auto;">
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <form method="POST" action="{{ route('update_sakit') }}" id="frmIzin" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $izin->id }}">
                <div class="form-group">
                    <input type="text" id="tgl_izin_dari" name="tgl_izin_dari" value="{{ $izin->tgl_izin_dari }}" class="form-control datepicker" placeholder="Dari">
                </div>
                <div class="form-group">
                    <input type="text" id="tgl_izin_sampai" name="tgl_izin_sampai" value="{{ $izin->tgl_izin_sampai }}" class="form-control datepicker" placeholder="Sampai">
                </div>
                <div class="form-group">
                    <input type="text" id="jml_hari" name="jml_hari" class="form-control" placeholder="Jumlah Hari" readonly>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Keterangan *maks: 225 karakter">{{ $izin->keterangan }}</textarea>
                </div>
                @if ($izin->gambar != null)
                <div class="row">
                    <div class="col-12">
                        <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ Storage::url($izin->gambar) }}" alt="" width="100px" > 
                        <p class="mb-0"><strong>File Sebelumnya</strong></p>
                        </div>
                    </div>
                </div>
                    <!-- <p><strong>Gambar saat ini:</strong> <a href="{{ asset('storage/' . $izin->gambar) }}" target="_blank">Lihat Gambar</a></p> -->
                @endif
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

            loadjumlahhari();

            $("#tgl_izin_dari,#tgl_izin_sampai").change(function(e) {
                loadjumlahhari();
            });

            $("#frmIzin").submit(function (e) {
                e.preventDefault();
                var tgl_izin_dari = $("#tgl_izin_dari").val();
                var tgl_izin_sampai = $("#tgl_izin_sampai").val();
                var keterangan = $("#keterangan").val();
                var id =$("#id").val();
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