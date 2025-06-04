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
        <form method="POST" action="{{ route('updateizinkhusus') }}" id="frmIzinkhs" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $izinkhusus->id }}">
           <div class="form-group">
                    <input type="text" id="tanggal" name="tanggal" value="{{ $izinkhusus->tanggal }}" class="form-control datepicker" placeholder="Tanggal">
                </div>
                <div class="form-group">
                    <select name="jenis_izin" id="jenis_izin" class="form-control">
                        <option value="">Jenis Izin</option>
                        <option value="masuk siang" {{ $izinkhusus->jenis_izin == 'masuk siang' ? 'selected' : '' }}>Masuk Siang</option>
                        <option value="pulang awal" {{ $izinkhusus->jenis_izin == 'pulang awal' ? 'selected' : '' }}>Pulang Awal</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="time" id="jam_izin" name="jam_izin" value="{{ $izinkhusus->jam_izin }}" class="form-control" placeholder="Jam Izin(masuk/pulang)">
                </div>
                <div class="form-group">
                    <textarea name="alasan" id="alasan" cols="30" rows="5" class="form-control" placeholder="Keterangan *maks: 225 karakter">{{ $izinkhusus->alasan }}</textarea>
                </div>
            <div class="form-group">
                <button class="btn btn-primary w-100">Update</button>
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
        });
    </script>
@endpush