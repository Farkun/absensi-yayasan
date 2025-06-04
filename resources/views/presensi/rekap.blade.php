@extends('layouts.admin.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Rekap Presensi</h4>
                                <br>
                                <!-- <p class="text-muted m-b-15 f-s-12">Use the input classes on an <code>.input-default, input-flat, .input-rounded</code> for Default input.</p> -->
                                <div class="basic-form">
                                    <form id="formFilter" method="POST" target="_blank">
                                        @csrf
                                        <div class="form-group">
                                            <select class="form-control" name="bulan" id="bulan">
                                                <option value="">Bulan (semua bulan)</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ date("m") == $i ? 'selected' : '' }}>
                                                        {{ $namabulan[$i] }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="tahun" id="tahun">
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

                                        <div class="d-flex justify-content-end">
                                            {{-- Tombol Cetak --}}
                                            <button type="submit" class="btn btn-info d-inline-flex align-items-center mr-2"
                                                formaction="{{ url('/presensi/cetakrekap') }}">
                                                <ion-icon name="print-outline" style="font-size: 18px;"></ion-icon> Cetak
                                            </button>

                                            {{-- Tombol Export --}}
                                            <button type="submit" class="btn btn-success d-inline-flex align-items-center"
                                                formaction="{{ route('export.rekap') }}">
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
    </div>
@endsection