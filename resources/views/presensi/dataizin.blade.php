@extends('layouts.admin.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3>Data Izin</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered zero-configuration">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jabatan</th>
                                                <th>Status Izin</th>
                                                <th>keterangan</th>
                                                <th>Bukti Gambar</th>
                                                <th>Status Approve</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataizin as $d)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($d->tgl_izin_dari))  }} s/d 
                                                        {{ date('d-m-Y', strtotime($d->tgl_izin_sampai))  }}
                                                    </td>
                                                    <td>{{ $d->nik }}</td>
                                                    <td>{{ $d->nama_lengkap }}</td>
                                                    <td>{{ $d->jabatan }}</td>
                                                    <td>
                                                        {{
                                                            $d->status == "i" ? "Izin" :
                                                            ($d->status == "s" ? "Sakit" :
                                                            ($d->status == "c" ? "Cuti" : "Tidak Diketahui"))
                                                        }}
                                                    </td>
                                                    <td>{{ $d->keterangan }}</td>
                                                    <td>
                                                        @if ($d->gambar)
                                                            <a href="{{ asset('storage/' . $d->gambar) }}"
                                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Download Gambar" download>
                                                                <ion-icon name="download-outline"
                                                                    style="font-size: 16px;"></ion-icon>
                                                            </a>
                                                        @else
                                                            <span class="text-muted">No photo</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($d->status_approved == 1)
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif ($d->status_approved == 2)
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        @else ($d->status_approved==3)
                                                            <span class="badge badge-warning">pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($d->status_approved == 0)
                                                            <a href="#" class="btn-approve" id_izinsakit="{{ $d->id }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Persetujuan">
                                                                <button type="button"
                                                                    class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                                                                    style="width: 20px; height: 20px; padding: 0;">
                                                                    <ion-icon name="ellipsis-vertical-outline"
                                                                        style="font-size: 15px;"></ion-icon>
                                                                </button>
                                                            </a>
                                                        @else
                                                            <a href="/presensi/{{ $d->id }}/batalkanizin" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Batalkan">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                                                                    style="width: 20px; height: 20px; padding: 0;">
                                                                    <ion-icon name="close-outline"
                                                                        style="font-size: 15px;"></ion-icon>
                                                                </button>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>NIK</th>
                                                <th>Nama Pegawai</th>
                                                <th>Jabatan</th>
                                                <th>Status Izin</th>
                                                <th>keterangan</th>
                                                <th>Status Approve</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-dataizin" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Persetujuan Izin/Sakit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/presensi/approveizin" method="POST">
                        @csrf
                        <input type="hidden" name="id_izinsakit_form" id="id_izinsakit_form">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="status_approved" id="status_approved" class="form-control">
                                        <option selected="selected">Choose...</option>
                                        <option value="1">Disetujui</option>
                                        <option value="2">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function () {
            $(".btn-approve").on("click", function (e) {
                e.preventDefault();
                var id_izinsakit = $(this).attr("id_izinsakit");
                $(id_izinsakit_form).val(id_izinsakit);
                $("#modal-dataizin").modal("show");
            });
        });

        // Aktifkan semua tooltip
        document.addEventListener('DOMContentLoaded', function () {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush