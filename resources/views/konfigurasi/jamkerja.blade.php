@extends('layouts.admin.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Jam Kerja</h4>
                                <br>
                                <!-- <p class="text-muted m-b-15 f-s-12">Use the input classes on an <code>.input-default, input-flat, .input-rounded</code> for Default input.</p> -->
                                <div class="basic-form">
                                    <form action="/konfigurasi/updatejamkerja" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="lokasi_kantor">Jam Awal Masuk</label>
                                            <input type="text" class="form-control input-default"
                                                value="{{ $jam_kantor->awal_jam_masuk }}" id="awal_jam_masuk"
                                                name="awal_jam_masuk" placeholder="Jam Awal Masuk">
                                        </div>
                                        <div class="form-group">
                                            <label for="radius">Jam Masuk</label>
                                            <input type="text" class="form-control input-default"
                                                value="{{ $jam_kantor->jam_masuk }}" id="jam_masuk" name="jam_masuk"
                                                placeholder="Jam Masuk">
                                        </div>
                                        <div class="form-group">
                                            <label for="lokasi_kantor">Jam Akhir Masuk</label>
                                            <input type="text" class="form-control input-default"
                                                value="{{ $jam_kantor->akhir_jam_masuk }}" id="akhir_jam_masuk"
                                                name="akhir_jam_masuk" placeholder="Jam Akhir Masuk">
                                        </div>
                                        <div class="form-group">
                                            <label for="radius">Jam Pulang</label>
                                            <input type="text" class="form-control input-default"
                                                value="{{ $jam_kantor->jam_pulang }}" id="jam_pulang" name="jam_pulang"
                                                placeholder="Jam Pulang">
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit"
                                                    class="btn btn-primary w-100 d-inline align-items-center mr-2">
                                                    <ion-icon name="reload-outline"></ion-icon>
                                                    Update
                                                </button>
                                            </div>
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