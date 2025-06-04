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
        <div class="pageTitle">Form Izin</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div class="row" style="margin-top: 70px;">
    <div class="col">
        <form method="POST" action="{{ route('updateizin') }}" id="frmIzin" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $izin->id }}">
            <div class="form-group">
                <input type="text" id="tgl_izin" name="tgl_izin" class="form-control datepicker" value="{{ $izin->tgl_izin }}" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Izin / Sakit</option>
                    <option value="i" {{ $izin->status == 'i' ? 'selected' : '' }}>Izin</option>
                    <option value="s" {{ $izin->status == 's' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control">{{ $izin->keterangan }}</textarea>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="gambar" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline"></ion-icon>
                            <i>Tap to Upload</i>
                            <i>(Bukti)</i>
                        </strong>
                    </span>
                </label>
            </div>
            @if ($izin->gambar)
                <p><strong>Gambar saat ini:</strong> <a href="{{ asset('storage/' . $izin->gambar) }}" target="_blank">Lihat Gambar</a></p>
            @endif
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