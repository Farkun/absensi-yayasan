@extends('layouts.attendance')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Profile</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top:4rem">
        <div class="col">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ $messagesuccess }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ $messageerror }}
                </div>
            @endif
        </div>
    </div>
    <form action="/presensi/{{ $pegawai->nik }}/updateProfile" method="POST" enctype="multipart/form-data"
        style="margin-top:1rem; padding-bottom:100px">
        @csrf
        <div class="col">
            <div class="form-group boxed">
                <label>Nama/No Telp</label>
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $pegawai->nama_lengkap }}" name="nama_lengkap"
                        placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $pegawai->no_hp }}" name="no_hp" placeholder="No. HP"
                        autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <label>Ubah Password</label>
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="old_password" placeholder="Password Lama">
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="new_password" placeholder="Password Baru">
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="confirm_new_password"
                        placeholder="Konfirmasi Password Baru">
                </div>
            </div>

            <label>Foto Profile</label>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload</i>
                        </strong>
                    </span>
                </label>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>

@endsection