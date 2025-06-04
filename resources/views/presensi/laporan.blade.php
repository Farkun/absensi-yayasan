@extends('layouts.admin.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Laporan Presensi</h4>
                                <br>
                                <!-- <p class="text-muted m-b-15 f-s-12">Use the input classes on an <code>.input-default, input-flat, .input-rounded</code> for Default input.</p> -->
                                <div class="basic-form">
                                    <form action="/presensi/cetaklaporan" id="frmLaporan" method="POST" target="_blank">
                                        @csrf
                                        <div class="form-group">
                                            <select class="form-control" name="bulan" id="bulan">
                                                <option value="">Bulan</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>
                                                        {{ $namabulan[$i] }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="tahun" id="tahun">
                                                <option value="">Tahun</option>
                                                @php
                                                    $tahunmulai = 2024;
                                                    $tahunskrg = date("Y");
                                                @endphp
                                                @for ($tahun = $tahunmulai; $tahun <= $tahunskrg; $tahun++)
                                                    <option value="{{ $tahun }}" {{ date("Y") == $tahun ? 'selected' : '' }}>
                                                        {{ $tahun }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="nik" id="nik">
                                                <option value="">Pilih Pegawai</option>
                                                @foreach ($pegawai as $d)
                                                    <option value="{{ $d->nik }}">{{ $d->nama_lengkap }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit"
                                                class="btn btn-info d-inline-flex align-items-center mr-2">
                                                <ion-icon name="print-outline" style="font-size: 18px;"></ion-icon> Cetak
                                            </button>
                                            <button type="submit" name="exportexcel"
                                                class="btn btn-success d-inline-flex align-items-center">
                                                <ion-icon name="download-outline" style="font-size: 18px;"></ion-icon>
                                                Export Excel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
<script>
    $(function() {
        $("#frmLaporan").submit(function(e){
            var bulan = $("#bulan").val(); 
            var tahun = $("#tahun").val();
            var nik = $("#nik").val();

            if (bulan == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Bulan harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#bulan").focus();
                });
                return false; 
            } else if (tahun == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'Tahun harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#tahun").focus();
                });
                return false; 
            } else if (nik == "") {
                Swal.fire({
                    title: 'Warning!',
                    text: 'NIK/Nama Pegawai harus Diisi',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    $("#nik").focus();
                });
                return false; 
            }  
        });
    });
</script>
@endpush
