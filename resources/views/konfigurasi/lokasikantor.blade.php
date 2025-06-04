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
                                <h4 class="card-title">Konfigurasi Lokasi</h4>
                                <br>
                                <!-- <p class="text-muted m-b-15 f-s-12">Use the input classes on an <code>.input-default, input-flat, .input-rounded</code> for Default input.</p> -->
                                <div class="basic-form">
                                    <form action="/konfigurasi/updatelokasikantor" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="lokasi_kantor">Lokasi Latitude & Longitude</label>
                                            <input type="text" class="form-control input-default"
                                                value="{{ $lok_kantor->lokasi_kantor }}" id="lokasi_kantor"
                                                name="lokasi_kantor" placeholder="Lokasi Kantor">
                                        </div>
                                        <div class="form-group">
                                            <label for="radius">Radius Per Meter</label>
                                            <input type="text" class="form-control input-default"
                                                value="{{ $lok_kantor->radius }}" id="radius" name="radius"
                                                placeholder="Radius">
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