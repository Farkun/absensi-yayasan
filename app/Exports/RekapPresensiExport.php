<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use DateTime;

class RekapPresensiExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $bulan;
    protected $tahun;
    protected $rekap;
    protected $namabulan;

    public function __construct($rekap, $bulan, $tahun, $namabulan)
    {
        $this->rekap = $rekap;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->namabulan = $namabulan;
    }

    public function collection()
    {
        $jamKerja = DB::table('jam_kerja')->first();
        $jamkantor = $jamKerja->jam_masuk;
        $jamTelat = $jamkantor;

        // MODE TAHUNAN
        if (empty($this->bulan)) {
            $result = [];
            $pegawais = DB::table('pegawais')->select('nik', 'nama_lengkap')->get();

            foreach ($pegawais as $pegawai) {
                $row = [
                    'nik' => $pegawai->nik,
                    'nama_lengkap' => $pegawai->nama_lengkap,
                ];

                $totalHadir = 0;
                $totalTelat = 0;
                $totalIzin = 0;
                $totalSakit = 0;
                $totalCuti = 0;

                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    // Ambil izin masuk siang & izin biasa untuk pegawai per bulan
                    $izinMasukSiang = DB::table('izin_khusus')
                        ->where('status', 1)
                        ->where('jenis_izin', 'masuk siang')
                        ->where('nik', $pegawai->nik)
                        ->whereMonth('tanggal', $bulan)
                        ->whereYear('tanggal', $this->tahun)
                        ->pluck('tanggal')
                        ->map(fn($tanggal) => date('Y-m-d', strtotime($tanggal)))
                        ->toArray();

                    $bulanAngka = str_pad($bulan, 2, '0', STR_PAD_LEFT); // misalnya: 03
                    $tanggalAwalBulan = "{$this->tahun}-{$bulanAngka}-01";
                    $tanggalAkhirBulan = date("Y-m-t", strtotime($tanggalAwalBulan)); // akhir bulan

                    $izinBiasa = DB::table('pengajuan_izins')
                        ->where('status_approved', 1)
                        ->where('nik', $pegawai->nik)
                        ->where(function ($query) use ($tanggalAwalBulan, $tanggalAkhirBulan) {
                            $query->whereBetween('tgl_izin_dari', [$tanggalAwalBulan, $tanggalAkhirBulan])
                                ->orWhereBetween('tgl_izin_sampai', [$tanggalAwalBulan, $tanggalAkhirBulan])
                                ->orWhere(function ($query2) use ($tanggalAwalBulan, $tanggalAkhirBulan) {
                                    $query2->where('tgl_izin_dari', '<', $tanggalAwalBulan)
                                        ->where('tgl_izin_sampai', '>', $tanggalAkhirBulan);
                                });
                        })
                        ->get();

                    $izinTanggal = [];
                    $sakitTanggal = [];
                    $cutiTanggal = [];

                    foreach ($izinBiasa as $izin) {
                        $start = new DateTime($izin->tgl_izin_dari);
                        $end = new DateTime($izin->tgl_izin_sampai);
                        $interval = \DateInterval::createFromDateString('1 day');
                        $period = new \DatePeriod($start, $interval, $end->modify('+1 day'));

                        foreach ($period as $date) {
                            $tgl = $date->format('Y-m-d');

                            // Tambahkan validasi agar hanya tanggal di bulan ini yang dihitung
                            if (date('Y-m', strtotime($tgl)) !== "{$this->tahun}-{$bulanAngka}") {
                                continue;
                            }

                            if (strtolower($izin->status) === 'i') {
                                $izinTanggal[] = $tgl;
                            } elseif (strtolower($izin->status) === 's') {
                                $sakitTanggal[] = $tgl;
                            } elseif (strtolower($izin->status) === 'c') {
                                $cutiTanggal[] = $tgl;
                            }
                        }
                    }

                    $presensi = DB::table('attendances')
                        ->where('nik', $pegawai->nik)
                        ->whereMonth('tgl_presensi', $bulan)
                        ->whereYear('tgl_presensi', $this->tahun)
                        ->get();

                    $hadir = 0;
                    $telat = 0;
                    $izin = count($izinTanggal);
                    $sakit = count($sakitTanggal);
                    $cuti = count($cutiTanggal);

                    foreach ($presensi as $pres) {
                        $tanggal = date('Y-m-d', strtotime($pres->tgl_presensi));
                        if (in_array($tanggal, $izinTanggal) || in_array($tanggal, $sakitTanggal)) {
                            continue;
                        }

                        if ($pres->jam_in) {
                            if (in_array($tanggal, $izinMasukSiang)) {
                                $hadir++;
                            } elseif ($pres->jam_in > $jamTelat) {
                                $hadir++;
                                $telat++;
                            } else {
                                $hadir++;
                            }
                        }
                    }

                    $row["hadir {$bulan}"] = $hadir;
                    $row["telat {$bulan}"] = $telat;
                    $row["izin {$bulan}"] = $izin;
                    $row["sakit {$bulan}"] = $sakit;
                    $row["cuti {$bulan}"] = $cuti;

                    $totalHadir += $hadir;
                    $totalTelat += $telat;
                    $totalIzin += $izin;
                    $totalSakit += $sakit;
                    $totalCuti += $cuti;
                }

                $row['total_hadir'] = $totalHadir;
                $row['total_telat'] = $totalTelat;
                $row['total_izin'] = $totalIzin;
                $row['total_sakit'] = $totalSakit;
                $row['total_cuti'] = $totalCuti;

                $result[] = $row;
            }

            return collect($result);
        }

        // MODE BULANAN DENGAN IZIN
        $result = [];
        $pegawais = DB::table('pegawais')->select('nik', 'nama_lengkap')->get();

        foreach ($pegawais as $pegawai) {
            $row = [
                'nik' => $pegawai->nik,
                'nama_lengkap' => $pegawai->nama_lengkap,
            ];

            $izinMasukSiang = DB::table('izin_khusus')
                ->where('status', 1)
                ->where('jenis_izin', 'masuk siang')
                ->where('nik', $pegawai->nik)
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->pluck('tanggal')
                ->map(fn($tanggal) => date('Y-m-d', strtotime($tanggal)))
                ->toArray();

            $izinBiasa = DB::table('pengajuan_izins')
                ->where('status_approved', 1)
                ->where('nik', $pegawai->nik)
                ->whereMonth('tgl_izin_dari', '<=', $this->bulan)
                ->whereMonth('tgl_izin_sampai', '>=', $this->bulan)
                ->whereYear('tgl_izin_dari', '<=', $this->tahun)
                ->whereYear('tgl_izin_sampai', '>=', $this->tahun)
                ->get();

            $izinTanggal = [];
            $sakitTanggal = [];
            $cutiTanggal = [];
            foreach ($izinBiasa as $izin) {
                $start = new DateTime($izin->tgl_izin_dari);
                $end = new DateTime($izin->tgl_izin_sampai);
                $interval = \DateInterval::createFromDateString('1 day');
                $period = new \DatePeriod($start, $interval, $end->modify('+1 day'));

                foreach ($period as $date) {
                    $tgl = $date->format('Y-m-d');
                    if (strtolower($izin->status) === 'i') {
                        $izinTanggal[] = $tgl;
                    } elseif (strtolower($izin->status) === 's') {
                        $sakitTanggal[] = $tgl;
                    } elseif (strtolower($izin->status) === 'c') {
                        $cutiTanggal[] = $tgl;
                    }
                }
            }
            $presensi = DB::table('attendances')
                ->where('nik', $pegawai->nik)
                ->whereMonth('tgl_presensi', $this->bulan)
                ->whereYear('tgl_presensi', $this->tahun)
                ->get();

            $hadir = 0;
            $telat = 0;
            $izin = 0;
            $sakit = 0;
            $cuti = 0;

            for ($i = 1; $i <= 31; $i++) {
                $value = '';
                $tanggal = date("Y-m-d", strtotime("{$this->tahun}-{$this->bulan}-{$i}"));

                $pres = $presensi->firstWhere('tgl_presensi', $tanggal);
                if (in_array($tanggal, $izinTanggal)) {
                    $value = 'I';
                    $izin++;
                } elseif (in_array($tanggal, $sakitTanggal)) {
                    $value = 'S';
                    $sakit++;
                } elseif (in_array($tanggal, $cutiTanggal)) {
                    $value = 'C';
                    $cuti++;
                } elseif ($pres && $pres->jam_in) {
                    if (in_array($tanggal, $izinMasukSiang)) {
                        $value = 'H';
                        $hadir++;
                    } elseif ($pres->jam_in > $jamTelat) {
                        $value = 'T';
                        $hadir++;
                        $telat++;
                    } else {
                        $value = 'H';
                        $hadir++;
                    }
                }

                $row["tgl_{$i}"] = $value;
            }

            $row['total_hadir'] = $hadir;
            $row['total_telat'] = $telat;
            $row['total_izin'] = $izin;
            $row['total_sakit'] = $sakit;
            $row['total_cuti'] = $cuti;
            $result[] = $row;
        }

        return collect($result);
    }

    public function headings(): array
    {
        if (empty($this->bulan)) {
            $headers = ['NIK', 'Nama Lengkap'];

            for ($i = 1; $i <= 12; $i++) {
                $headers[] = "Hadir {$i}";
                $headers[] = "Telat {$i}";
                $headers[] = "Izin {$i}";
                $headers[] = "Sakit {$i}";
                $headers[] = "Cuti {$i}";
            }

            $headers[] = 'Total Hadir';
            $headers[] = 'Total Terlambat';
            $headers[] = 'Total Izin';
            $headers[] = 'Total Sakit';
            $headers[] = 'Total Cuti';

            return $headers;
        }

        // Format bulanan
        return array_merge(
            ['NIK', 'Nama Lengkap'],
            array_map(function ($i) {
                return "Tgl {$i}";
            }, range(1, 31)),
            ['Total Hadir', 'Total Terlambat', 'Total Izin', 'Total Sakit', 'Total Cuti']
        );
    }

    public function startCell(): string
    {
        return 'A2'; // Mulai dari baris kedua
    }

    public function registerEvents(): array
    {
        if (!empty($this->bulan)) {
            return [];
        }

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->setCellValue('A1', 'NIK');
                $sheet->setCellValue('B1', 'Nama Lengkap');

                $col = 3; // kolom C
                for ($i = 1; $i <= 12; $i++) {
                    // Kolom awal dan akhir per bulan (4 kolom: Hadir, Telat, Izin, Sakit)
                    $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 4);

                    // Merge baris 1 untuk nama bulan
                    $sheet->mergeCells("{$startCol}1:{$endCol}1");
                    $sheet->setCellValue("{$startCol}1", $this->namabulan[$i]);

                    // Baris kedua: sub-kolom
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '2', 'Hadir');
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1) . '2', 'Telat');
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2) . '2', 'Izin');
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 3) . '2', 'Sakit');
                    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 4) . '2', 'Cuti');

                    $col += 5;
                }

                // Total Hadir, Terlambat, Izin, Sakit
                $labels = ['Total Hadir', 'Total Terlambat', 'Total Izin', 'Total Sakit', 'Total Cuti'];
                foreach ($labels as $label) {
                    $currentCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $sheet->mergeCells("{$currentCol}1:{$currentCol}2");
                    $sheet->setCellValue("{$currentCol}1", $label);
                    $col++;
                }

                // Merge untuk kolom A dan B
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
            },
        ];
    }
}
