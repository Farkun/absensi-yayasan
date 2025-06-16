<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Pegawai;
use App\Notifications\PengajuanIzin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;


class IzinabsenController extends Controller
{
    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "i";
        $keterangan = $request->keterangan;
        $gambar = null;

        if ($request->hasFile('gambar')) {
            $folderPath = "upload/izin";
            $file = $request->file('gambar');

            // Format nama file: nik-tglizin-status
            $tglPresensi = date('Ymd', strtotime($tgl_izin_dari)); // Format tanggal jadi Ymd (contoh: 20250428)
            $cleanStatus = preg_replace('/[^A-Za-z0-9]/', '', $status); // Hapus karakter aneh di status

            $baseName = $nik . '-' . $tglPresensi . '-' . $cleanStatus;
            $baseName = substr($baseName, 0, 45); // Biar aman < 50 karakter setelah tambah extension

            $extension = $file->getClientOriginalExtension();
            $filename = $baseName . '.' . $extension;

            // Simpan file
            $target_path = storage_path('/app/public/'.$folderPath);
            if (!File::exists($target_path)) File::makeDirectory($target_path);
            $file->move($target_path, $filename);

            // Set path untuk disimpan ke database
            $gambar = $folderPath .'/'. $filename;
        }

        $data = [
            'nik' => $nik,
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan,
            'gambar' => $gambar
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
        $izin = DB::table('pengajuan_izins')->where('id', $id)->first();

        if (!$izin) {
            return redirect('/presensi/izin')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('izin.edit', compact('izin'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $izin = DB::table('pengajuan_izins')->where('id', $id)->first();

        if (!$izin) {
            return redirect('/presensi/izin')->with(['error' => 'Data tidak ditemukan']);
        }

        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_izin_dari = $request->tgl_izin_dari;
        $tgl_izin_sampai = $request->tgl_izin_sampai;
        $status = "i";
        $keterangan = $request->keterangan;
        $gambar = $izin->gambar;

        // Handle gambar baru
        if ($request->hasFile('gambar')) {
            $folderPath = "upload/izin";
            $file = $request->file('gambar');

            // Format nama file baru
            $tglPresensiBaru = date('Ymd', strtotime($tgl_izin_dari));
            $cleanStatus = preg_replace('/[^A-Za-z0-9]/', '', $status);
            $baseName = $nik . '-' . $tglPresensiBaru . '-' . $cleanStatus;
            $baseName = substr($baseName, 0, 45);
            $extension = $file->getClientOriginalExtension();
            $filename = $baseName . '.' . $extension;

            // Jika tanggal sama, hapus gambar lama
            if ($tgl_izin_dari == $izin->tgl_izin_dari && $gambar && Storage::disk('public')->exists($gambar)) {
                Storage::disk('public')->delete($gambar);
            }

            $target_path = storage_path('/app/public/'.$folderPath);
            if (!File::exists($target_path)) File::makeDirectory($target_path);
            $file->storeAs($target_path, $filename);
            $gambar = $folderPath .'/'. $filename;
        }

        $update = DB::table('pengajuan_izins')->where('id', $id)->update([
            'tgl_izin_dari' => $tgl_izin_dari,
            'tgl_izin_sampai' => $tgl_izin_sampai,
            'status' => $status,
            'keterangan' => $keterangan,
            'gambar' => $gambar
        ]);

        if ($update) {
            return redirect('/presensi/izin')->with(['success' => 'Data berhasil diupdate']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data gagal diupdate']);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $data = DB::table('pengajuan_izins')->where('id', $id)->first();

        if ($data) {
            // Hapus file gambar jika ada
            if ($data->gambar) {
                Storage::disk('public')->delete($data->gambar);
            }

            // Hapus data izin
            DB::table('pengajuan_izins')->where('id', $id)->delete();

            return redirect('/presensi/izin')->with(['success' => 'Data berhasil dihapus']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data tidak ditemukan']);
        }
    }

    public function cekPengajuan(Request $request)
    {
        $dari = $request->tgl_izin_dari;
        $sampai = $request->tgl_izin_sampai;
        $nik = Auth::guard('pegawai')->user()->nik;
        $id = $request->id; // ambil id jika ada (untuk edit)

        $cek = DB::table('pengajuan_izins')
            ->where(function ($query) use ($dari, $sampai) {
                $query->whereBetween('tgl_izin_dari', [$dari, $sampai])
                    ->orWhereBetween('tgl_izin_sampai', [$dari, $sampai]);
            })
            ->where('nik', $nik)
            ->when($id, function ($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['ada' => $cek]);
    }



}
