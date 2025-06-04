<?php
function selisih($jam_masuk, $jam_keluar)
{
    list($h, $m, $s) = explode(":", $jam_masuk);
    $dtAwal = mktime($h, $m, $s, "1", "1", "1");
    list($h, $m, $s) = explode(":", $jam_keluar);
    $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
    $dtSelisih = $dtAkhir - $dtAwal;
    $totalmenit = $dtSelisih / 60;
    $jam = explode(".", $totalmenit / 60);
    $sisamenit = ($totalmenit / 60) - $jam[0];
    $sisamenit2 = $sisamenit * 60;
    $jml_jam = $jam[0];
    return $jml_jam . ":" . round($sisamenit2);
}
?>
@foreach ($attendance as $d)
@php
$bukti_in = Storage::url('upload/absensi/'.$d->bukti_in);
$bukti_out = Storage::url('upload/absensi/'.$d->bukti_out);
$jamKerja = DB::table('jam_kerja')->first();
$jamTepat = $jamKerja->jam_masuk;
$izin = DB::table('izin_khusus')
        ->where('nik', $d->nik)
        ->where('tanggal', $d->tgl_presensi)
        ->where('status', '1') 
        ->first();
@endphp
<tr>
    <td>{{ $loop ->iteration }}</td>
    <td>{{ $d ->nik}}</td>
    <td>{{ $d ->nama_lengkap }}</td>
    <td>{{ $d ->jabatan }}</td>
    <td>{{ $d ->jam_in }}</td>
    <td>
        <img width="70" src="{{ url($bukti_in) }}" alt="avatar">
    </td>
    <td>{!! $d->jam_out != null ? $d->jam_out : '<span class="badge badge-danger">Belum Absen</span>'!!}</td>
    <td>
        @if ($d->jam_out != null)
            <img width="70" src="{{ url($bukti_out) }}" alt="avatar">
            @else
            <ion-icon name="hourglass-outline" style="font-size: 18px;"></ion-icon>
        @endif
    </td>
    <td>
        @if ($izin)
        <span class="badge badge-info">Izin ({{ $izin->jenis_izin }})</span>
        @elseif ($d->jam_in > $jamTepat)
        @php
        $jamterlambat = selisih($jamTepat, $d->jam_in);
        @endphp
        <span class="badge badge-danger">Terlambat {{ $jamterlambat }}</span>
        @else
        <span class="badge badge-success">Tepat Waktu</span>
        @endif
    </td>
    <td>
        <a href="#" class="btn mb-1 btn-primary showmap" id="{{ $d->id }}">
            <ion-icon name="map-outline" style="font-size: 19px;"></ion-icon></a>
    </td>
</tr>
@endforeach
<script>
    $(function() {
        $(".showmap").click(function(e) {
            var id = $(this).attr("id");
            $.ajax({
                type: "POST",
                url: '/showmap',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache:false,
                success:function(respond) {
                    $("#loadmap").html(respond);
                }
            })
            $("#modal-showmap").modal("show");
        });
    });
</script>