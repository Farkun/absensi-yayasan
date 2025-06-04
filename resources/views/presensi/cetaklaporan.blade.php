<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabeldatapegawai {
            margin-top: 40px;
        }

        .tabeldatapegawai td {
            padding: 5px;
        }

        .tablepresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tablepresensi tr th {
            border: 2px solid #1b1e23;
            padding: 8px;
            background-color: rgb(214, 213, 213);
        }

        .tablepresensi tr td {
            border: 2px solid #1b1e23;
            padding: 5px;
            font-size: 12px;
        }

        .bukti {
            width: 50px;
            height: 45px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">
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
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ asset('admin/images/LOGO-YPB_BULAT.png') }}" width="70" height="70" alt="">
                </td>
                <td>
                    <span id="title">
                        LAPORAN PRESENSI PEGAWAI<br>
                        PERIODE {{strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                        BOGOR HERITAGE FOUNDATION
                    </span>
                    <br>
                    <span>
                        <i>Jl. KH. R. Abdullah Bin Nuh Jl. Yasmin Raya No.16A, RT.01/RW.04, Curugmekar, Kec. Bogor Bar.,
                            Kota Bogor, Jawa Barat 16113</i>
                    </span>
                </td>
            </tr>
        </table>
        <table class="tabeldatapegawai">
            <tr>
                <td rowspan="5">
                    @php
                        $path = Storage::url('upload/pegawai/' . $pegawai->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="150" height="150">
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>{{ $pegawai->nik }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $pegawai->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $pegawai->jabatan }}</td>
            </tr>
            <tr>
                <td>No HP</td>
                <td>:</td>
                <td>{{ $pegawai->no_hp }}</td>
            </tr>
        </table>
        <table class="tablepresensi">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Bukti Masuk</th>
                <th>Jam Keluar</th>
                <th>Bukti Keluar</th>
                <th>Keterangan</th>
                <th>Jumlah Jam</th>
            </tr>
            @foreach ($attendance as $d)
                @php
                    $path_in = Storage::url('upload/absensi/' . $d->bukti_in);
                    $path_out = Storage::url('upload/absensi/' . $d->bukti_out);
                    $jamterlambat = selisih($jamkantor,$d->jam_in);
                    $izinTanggal = $izinMasukSiang[$d->nik] ?? collect([]);
                    $punyaIzinMasukSiang = $izinTanggal->contains(function ($izin) use ($d) {
                        return $izin->tanggal == $d->tgl_presensi;
                    });
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date("d-m-Y", strtotime($d->tgl_presensi)) }}</td>
                    <td>{{ $d->jam_in }}</td>
                    <td><img src="{{ url($path_in) }}" alt="" class="bukti"></td>
                    <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen'}}</td>
                    <td>
                        @if ($d->jam_out != null)
                        <img src="{{ url($path_out) }}" alt="" class="bukti">
                        @else
                        No Photo
                        @endif
                    </td>
                    <td>
                        @if ($punyaIzinMasukSiang)
                        Izin Masuk Siang
                        @elseif ($d->jam_in > $jamkantor)
                        Terlambat {{ $jamterlambat }}
                        @else
                        Tepat Waktu
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_out != null)
                        @php
                        $jmljamkerja = selisih($d->jam_in,$d->jam_out);
                        @endphp
                        @else
                        @php
                        $jmljamkerja = 0;
                        @endphp
                        @endif
                        {{ $jmljamkerja }}
                    </td>
                </tr>
            @endforeach
        </table>

        <table width="100%" style="margin-top: 100px;">
        <tr>
            <td colspan="2" style="text-align: right;">Bogor, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: bottom;" height="100px">
                <u>Lorem Ipsum</u><br>
                <i><b>HRD Manager</b></i>
            </td>
            <td style="text-align: center; vertical-align: bottom;">
                <u>Ir Lorrem Ipsum</u><br>
                <i><b>Direktur</b></i>
            </td>
        </tr>
    </table>
    </section>
</body>

</html>