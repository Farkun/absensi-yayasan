<table>
    <tr>
        <th colspan="4">Laporan Presensi Pegawai</th>
    </tr>
    <tr>
        <td>Nama</td>
        <td>{{ $pegawai->nama_lengkap }}</td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>{{ $pegawai->nik }}</td>
    </tr>
    <tr>
        <td>Periode</td>
        <td>{{ $namabulan[$bulan] }} {{ $tahun }}</td>
    </tr>
</table>

<br>
@php
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
@endphp

<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Keterangan</th>
            <th>Jumlah Jam</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendance as $a)
            @php
                $izinTanggal = $izinMasukSiang[$a->nik] ?? collect([]);
                $punyaIzinMasukSiang = $izinTanggal->contains(function ($izin) use ($a) {
                    return $izin->tanggal == $a->tgl_presensi;
                });
            @endphp
            <tr>
                <td>{{ date('d-m-Y', strtotime($a->tgl_presensi)) }}</td>
                <td>{{ $a->jam_in }}</td>
                <td>{{ $a->jam_out ?? '-' }}</td>
                <td>
                    @if ($punyaIzinMasukSiang)
                        Izin Masuk Siang
                    @elseif ($a->jam_in > $jamkantor)
                        @php
                            $jamterlambat = \Carbon\Carbon::parse($jamkantor)->diff(\Carbon\Carbon::parse($a->jam_in))->format('%H:%I');
                        @endphp
                        Terlambat {{ $jamterlambat }}
                    @else
                        Tepat Waktu
                    @endif
                </td>
                <td>
                    @if ($a->jam_out)
                        {{ selisih($a->jam_in, $a->jam_out) }}
                    @else
                        0
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>