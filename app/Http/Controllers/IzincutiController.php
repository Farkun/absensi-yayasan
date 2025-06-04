<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Pegawai;
use App\Notifications\PengajuanIzin;
use Illuminate\Support\Facades\Notification;

class IzincutiController extends Controller
{
    public function create()
    {
        $mastercuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.create', compact('mastercuti'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $kode_cuti = $request->kode_cuti;
        $status = "c";
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'kode_cuti' => $kode_cuti,
            'status' => $status,
            'keterangan' => $keterangan,
        ];

        $simpan = DB::table('pengajuan_izins')->insert($data);
        
        $pegawai = Pegawai::where('nik', $nik)->first();
        $userOperator = User::all();
        Notification::send($userOperator, new PengajuanIzin($pegawai));

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function edit($id)
    {
        $dataizin = DB::table('pengajuan_izins')->where('id', $id)->first();
        $mastercuti = DB::table('master_cuti')->orderBy('kode_cuti')->get();
        return view('izincuti.edit', compact('mastercuti', 'dataizin'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "c";
        $kode_cuti = $request->kode_cuti;
        $keterangan = $request->keterangan;
        try {
            $data = [
                'tgl_izin_dari' => $tgl_izin_dari,
                'tgl_izin_sampai' => $tgl_izin_sampai,
                'status' => $status,
                'kode_cuti' => $kode_cuti,
                'keterangan' => $keterangan,
            ];

            DB::table('pengajuan_izins')->where('id', $id)->update($data);
            return Redirect('/presensi/izin')->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect('/presensi/izin')->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function getmaxcuti(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $kode_cuti = $request->kode_cuti;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tahun_cuti = date('Y', strtotime($tgl_izin_dari));

        // Ambil data cuti dari master_cuti
        $cuti = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->first();

        // Jika tidak ditemukan, kembalikan 0
        if (!$cuti) {
            return 0;
        }

        // Jika kode cuti adalah C01 (cuti tahunan), baru hitung pengurangan
        if ($kode_cuti === 'C01') {
            $cuti_digunakan = DB::table('pengajuan_izins')
                ->where('kode_cuti', $kode_cuti)
                ->where('nik', $nik)
                ->where('status_approved', '1')
                ->where('status', 'c')
                ->whereYear('tgl_izin_dari', $tahun_cuti)
                ->select(DB::raw("SUM(DATEDIFF(tgl_izin_sampai, tgl_izin_dari) + 1) as total"))
                ->value('total');

            $cuti_digunakan = $cuti_digunakan ?? 0;
            $max_cuti = $cuti->jml_hari - $cuti_digunakan;
        } elseif ($kode_cuti === 'C02') {
            // Cuti kompensasi berdasarkan lembur
            $total_kompensasi = DB::table('izin_khusus')
                ->where('nik', $nik)
                ->where('jenis_izin', 'lembur')
                ->where('status', '1') // disetujui
                ->count(); // anggap 1 hari per entry

            $digunakan = DB::table('pengajuan_izins')
                ->where('nik', $nik)
                ->where('kode_cuti', 'C02')
                ->where('status_approved', '1')
                ->where('status', 'c')
                ->selectRaw("SUM(DATEDIFF(tgl_izin_sampai, tgl_izin_dari) + 1) as total")
                ->value('total') ?? 0;

            $max_cuti = max($total_kompensasi - $digunakan, 0);
        } else {
            // Untuk jenis cuti lainnya, langsung pakai dari master_cuti
            $max_cuti = $cuti->jml_hari;
        }

        return response()->json([
            'max_cuti' => $max_cuti
        ]);

    }

}
