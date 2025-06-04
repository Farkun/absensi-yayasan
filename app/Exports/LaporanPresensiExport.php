<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanPresensiExport implements FromView
{
    protected $nik, $bulan, $tahun;

    public function __construct($nik, $bulan, $tahun)
    {
        $this->nik = $nik;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $jamKerja = \DB::table('jam_kerja')->first();
        $jamkantor = $jamKerja->jam_masuk;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $pegawai = \DB::table('pegawais')->where('nik', $this->nik)->first();
        $attendance = \DB::table('attendances')
            ->where('nik', $this->nik)
            ->whereRaw('MONTH(tgl_presensi) = ?', [$this->bulan])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$this->tahun])
            ->orderBy('tgl_presensi')
            ->get();
         $izinMasukSiang = \DB::table('izin_khusus')
                ->select('nik', 'tanggal')
                ->where('status', 1)
                ->where('jenis_izin', 'masuk siang')
                ->whereMonth('tanggal', $this->bulan)
                ->whereYear('tanggal', $this->tahun)
                ->get()
                ->groupBy('nik');
        return view('presensi.lapresensiexcel', [
            'pegawai' => $pegawai,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'namabulan' => $namabulan,
            'attendance' => $attendance,
            'jamkantor' => $jamkantor,
            'izinMasukSiang' => $izinMasukSiang,
        ]);
    }
}

