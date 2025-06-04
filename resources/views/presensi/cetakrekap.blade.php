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

        .tabelpresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            /* table-layout: fixed; */
        }

        .tabelpresensi tr th {
            border: 2px solid #1b1e23;
            padding: 8px;
            background-color: rgb(214, 213, 213);
            font-size: 10px;
        }

        .tabelpresensi tr td {
            border: 2px solid #1b1e23;
            padding: 5px;
            font-size: 10px;
            /* padding: 2px;
            text-align: center;
            word-break: break-word; */
        }

        .bukti {
            width: 50px;
            height: 45px;
        }

        .sheet {
            overflow: visible;
            height: auto !important;
            min-height: auto;
        }

        @media print {

            .sheet {
                page-break-after: always;
            }

            /* Menambahkan aturan khusus untuk bagian per-tahun */
            .per-tahun {
                page-break-before: always;
            }

            .per-tahun h4 {
                page-break-before: always;
                /* Memastikan judul per tahun berada di halaman baru */
            }

            .per-tahun .tabelpresensi {
                page-break-inside: auto;
            }

            .per-tahun .tabelpresensi tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .per-tahun .sheet {
                margin-bottom: 10mm;
                /* Jarak antar halaman untuk bagian per tahun */
            }

            /* Menghindari pembagian halaman pada perbulan */
            .tabelpresensi {
                page-break-inside: auto;
                /* Tidak ada pembagian halaman di dalam tabel bulan */
            }

            .tabelpresensi tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4 landscape">
    <?php
if (!function_exists('selisih')) {
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
                        REKAP PRESENSI PEGAWAI<br>
                        PERIODE
                        @if (!empty($bulan))
                            BULAN {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}
                        @else
                            TAHUN {{ $tahun }}
                        @endif
                        <br>
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

        @if (isset($rekap))
            <table class="tabelpresensi">
                <tr>
                    <th rowspan="2">NIK</th>
                    <th rowspan="2">Nama Pegawai</th>
                    <th colspan="31">Tanggal</th>
                    <th rowspan="2">H</th>
                    <th rowspan="2">T</th>
                    <th rowspan="2">I</th>
                    <th rowspan="2">S</th>
                    <th rowspan="2">C</th>
                </tr>
                <tr>
                    @for ($i = 1; $i <= 31; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                </tr>
                @foreach ($rekap as $d)
                    <tr>
                        <td>{{ $d->nik }}</td>
                        <td>{{ $d->nama_lengkap }}</td>
                        @php
                            $totalhadir = 0;
                            $totalterlambat = 0;
                            $totalizin = 0;
                            $totalsakit = 0;
                            $totalcuti = 0;
                        @endphp
                        @for ($i = 1; $i <= 31; $i++)
                            @php
                                $tglKey = "tgl_" . $i;
                                $jam = $d->$tglKey;
                                $jamMasuk = explode('-', $jam)[0] ?? null;
                                $tanggalPresensi = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $izinTanggalArray = isset($izinMasukSiang[$d->nik])
                                    ? $izinMasukSiang[$d->nik]->pluck('tanggal')->toArray()
                                    : [];
                                $izinHariIni = $izinData[$d->nik] ?? collect([]);
                                $izin = $izinHariIni->first(function ($item) use ($tanggalPresensi) {
                                    return $tanggalPresensi >= $item->tgl_izin_dari && $tanggalPresensi <= $item->tgl_izin_sampai;
                                });
                                $kodeIzin = $izin->status ?? null; // 's' = sakit, 'i' = izin
                            @endphp
                            <td>
                                @if ($kodeIzin === 'i')
                                    @php $totalizin++; @endphp
                                    <span style="color: blue;">I</span>
                                @elseif ($kodeIzin === 's')
                                    @php $totalsakit++; @endphp
                                    <span style="color: orange;">S</span>
                                @elseif ($kodeIzin === 'c')
                                    @php $totalcuti++; @endphp
                                    <span style="color: purple;">C</span>
                                @elseif ($jamMasuk)
                                    @php
                                        $tanggalPresensi = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    @if (in_array($tanggalPresensi, $izinTanggalArray))
                                        @php $totalhadir++; @endphp
                                        H
                                    @elseif ($jamMasuk <= $jamkantor)
                                        @php $totalhadir++; @endphp
                                        <span style="color: green;">H</span>
                                    @else
                                        @php $totalterlambat++; @endphp
                                        <span style="color: red;">T</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @endfor
                        <td>{{ $totalhadir }}</td>
                        <td>{{ $totalterlambat }}</td>
                        <td>{{ $totalizin }}</td>
                        <td>{{ $totalsakit }}</td>
                        <td>{{ $totalcuti }}</td>
                    </tr>
                @endforeach
            </table>

        @elseif (isset($rekap_perbulan))
            {{-- TAMPILAN REKAP 12 BULAN --}}
            <h4>Rekap Kehadiran Tahun {{ $tahun }}</h4>

            <div class="per-tahun"> <!-- Menambahkan kelas per-tahun -->
                @foreach ($rekap_perbulan as $bulan => $rekap)
                    <h5>Bulan {{ $namabulan[$bulan] }}</h5>
                    <table class="tabelpresensi" style="margin-bottom: 30px;">
                        <tr>
                            <th rowspan="2">NIK</th>
                            <th rowspan="2">Nama Pegawai</th>
                            <th colspan="31">Tanggal</th>
                            <th rowspan="2">H</th>
                            <th rowspan="2">T</th>
                            <th rowspan="2">I</th>
                            <th rowspan="2">S</th>
                            <th rowspan="2">C</th>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= 31; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        @foreach ($rekap as $d)
                            <tr>
                                <td>{{ $d->nik }}</td>
                                <td>{{ $d->nama_lengkap }}</td>
                                @php
                                    $totalhadir = 0;
                                    $totalterlambat = 0;
                                    $totalizin = 0;
                                    $totalsakit = 0;
                                    $totalcuti = 0;
                                @endphp
                                @for ($i = 1; $i <= 31; $i++)
                                    @php
                                        $tglKey = "tgl_" . $i;
                                        $jam = $d->$tglKey;
                                        $jamMasuk = explode('-', $jam)[0] ?? null;
                                        $tanggalPresensi = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                        $izinKey = $d->nik . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                        $izinTanggalArray = isset($izinMasukSiang[$izinKey])
                                            ? $izinMasukSiang[$izinKey]->pluck('tanggal')->toArray()
                                            : [];
                                        $izinHariIni = $izinData[$izinKey] ?? collect([]);
                                        $izin = $izinHariIni->first(function ($item) use ($tanggalPresensi) {
                                            return $tanggalPresensi >= $item->tgl_izin_dari && $tanggalPresensi <= $item->tgl_izin_sampai;
                                        });
                                        $kodeIzin = $izin->status ?? null; // 's' = sakit, 'i' = izin
                                    @endphp
                                    <td>
                                        @if ($kodeIzin === 'i')
                                            @php $totalizin++; @endphp
                                            <span style="color: blue;">I</span>
                                        @elseif ($kodeIzin === 's')
                                            @php $totalsakit++; @endphp
                                            <span style="color: orange;">S</span>
                                        @elseif ($kodeIzin === 'c')
                                            @php $totalcuti++; @endphp
                                            <span style="color: purple;">C</span>
                                        @elseif ($jamMasuk)
                                            @php
                                                $tanggalPresensi = $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                            @endphp
                                            @if (in_array($tanggalPresensi, $izinTanggalArray))
                                                @php $totalhadir++; @endphp
                                                H
                                            @elseif ($jamMasuk <= $jamkantor)
                                                @php $totalhadir++; @endphp
                                                <span style="color: green;">H</span>
                                            @else
                                                @php $totalterlambat++; @endphp
                                                <span style="color: red;">T</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor
                                <td>{{ $totalhadir }}</td>
                                <td>{{ $totalterlambat }}</td>
                                <td>{{ $totalizin }}</td>
                                <td>{{ $totalsakit }}</td>
                                <td>{{ $totalcuti }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endforeach
            </div>
        @endif
        <small>*H = hadir, T = telat, I = izin, S = sakit, C = cuti</small>

        <table width="100%" style="margin-top: 100px;">
            <tr>
                <td></td>
                <td style="text-align: center;">Bogor, {{ date('d-m-Y') }}</td>
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