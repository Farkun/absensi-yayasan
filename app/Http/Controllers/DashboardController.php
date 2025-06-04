<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date('Y-m-d');
        $bulanini = date('m') * 1;
        $tahunini = date('Y');
        $jamKerja = DB::table('jam_kerja')->first();
        $jamMasuk = $jamKerja->jam_masuk;
        $nik = Auth::guard('pegawai')->user()->nik;
        $presensihariini = DB::table('attendances')->where('nik', $nik)->where('tgl_presensi', $hariini)->first();
        $historibulanini = DB::table('attendances')->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();

        // $rekappresensi = DB::table('attendances')
        //     ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "' . $jamMasuk . '",1,0)) as jmlterlambat')
        //     ->where('nik', $nik)
        //     ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
        //     ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
        //     ->first();

        $rekappresensi = DB::table('attendances as a')
            ->leftJoin('izin_khusus as i', function ($join) use ($nik) {
                $join->on('a.tgl_presensi', '=', 'i.tanggal')
                    ->where('i.nik', '=', $nik)
                    ->where('i.status', '=', 1)
                    ->where('i.jenis_izin', '=', 'masuk siang');
            })
            ->selectRaw('
                    COUNT(a.nik) as jmlhadir,
                    SUM(
                        CASE 
                            WHEN i.id IS NULL AND a.jam_in > ? THEN 1 
                            ELSE 0 
                        END
                    ) as jmlterlambat
                ', [$jamMasuk])
            ->where('a.nik', $nik)
            ->whereRaw('MONTH(a.tgl_presensi) = ?', [$bulanini])
            ->whereRaw('YEAR(a.tgl_presensi) = ?', [$tahunini])
            ->first();

        $leaderboard = DB::table('attendances')
            ->join('pegawais', 'attendances.nik', '=', 'pegawais.nik')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();

        // $rekapizin = DB::table('pengajuan_izin')
        //     ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
        //     ->where('nik', $nik)
        //     ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
        //     ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
        //     ->where('status_approved', 1)
        //     ->first();
        $rekapizin = DB::table('pengajuan_izins')
            ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit,SUM(IF(status="c",1,0)) as jmlcuti')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin_dari)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_izin_dari)="' . $tahunini . '"')
            ->where('status_approved', 1)
            ->first();

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('dashboard.dashboard', compact(
            'presensihariini',
            'historibulanini',
            'namabulan',
            'bulanini',
            'tahunini',
            'rekappresensi',
            'leaderboard',
            'rekapizin',
            'jamMasuk'
        ));
    }

    public function dashboardadmin()
    {

        $hariini = date("Y-m-d");
        $jamKerja = DB::table('jam_kerja')->first();
        $jamMasuk = $jamKerja->jam_masuk;
        $rekappresensi = DB::table('attendances as a')
            ->leftJoin('izin_khusus as i', function ($join) use ($hariini) {
                $join->on('a.nik', '=', 'i.nik') // penting: pastikan berdasarkan nik
                    ->where('i.tanggal', '=', $hariini)
                    ->where('i.status', '=', 1)
                    ->where('i.jenis_izin', '=', 'masuk siang');
            })
            ->selectRaw('
            COUNT(a.nik) as jmlhadir,
            SUM(
                CASE 
                    WHEN i.id IS NULL AND a.jam_in > ? THEN 1 
                    ELSE 0 
                END
            ) as jmlterlambat', [$jamMasuk])
            ->where('a.tgl_presensi', $hariini)
            ->first();

        // $rekapizin = DB::table('pengajuan_izin')
        //     ->selectRaw('SUM(IF(status="i",1,0)) as jmlizin,SUM(IF(status="s",1,0)) as jmlsakit')
        //     ->where('tgl_izin', $hariini)
        //     ->where('status_approved', 1)
        //     ->first();

        $rekapizin = DB::table('pengajuan_izins')
            ->selectRaw('
                COUNT(DISTINCT CASE WHEN status = "i" THEN nik END) as jmlizin,
                COUNT(DISTINCT CASE WHEN status = "s" THEN nik END) as jmlsakit,
                COUNT(DISTINCT CASE WHEN status = "c" THEN nik END) as jmlcuti
            ')
            ->whereDate('tgl_izin_dari', '<=', $hariini)
            ->whereDate('tgl_izin_sampai', '>=', $hariini)
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));
    }
}
