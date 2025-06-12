<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
// use Str;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = DB::table('pegawais')->orderBy('nama_lengkap')->get();
        return view('pegawai.index', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $nik = $request->nik;
        $username = $request->username;
        $nama_lengkap = $request->nama_lengkap;
        $password = Hash::make('ypb12345');
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;

        // Cek apakah nik sudah ada di database
        $cekNik = DB::table('pegawais')->where('nik', $nik)->exists();
        if ($cekNik) {
            return redirect()->back()->with('warning', 'Data dengan NIK ' . $nik . ' sudah ada.');
        }

        // Jika tidak ada, lanjut simpan
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . time() . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = '';
        }

        $data = [
            'nik' => $nik,
            'username' => $username,
            'nama_lengkap' => $nama_lengkap,
            'password' => $password,
            'jabatan' => $jabatan,
            'no_hp' => $no_hp,
            'foto' => $foto,
        ];

        $simpan = DB::table('pegawais')->insert($data);
        if ($simpan) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/upload/pegawai/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            session()->forget('warning');
            return Redirect::back()->with('success', 'Data Berhasil Disimpan');
        } else {
            return Redirect::back()->with('warning', 'Data Gagal Disimpan karena kesalahan sistem.');
        }
    }




    public function edit(Request $request)
    {
        $nik = $request->nik;
        $pegawai = DB::table('pegawais')->where('nik', $nik)->first();
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update($nik, Request $request)
    {
        $nik = $request->nik;
        $username = $request->username;
        $nama_lengkap = $request->nama_lengkap;
        $password = Hash::make('ypb12345');
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $pegawai = DB::table('pegawais')->where('nik', $nik)->first();
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . time() . "." . $request->file('foto')->getClientOriginalExtension();
            // Hapus foto lama jika ada
            if (!empty($pegawai->foto)) {
                Storage::delete("public/upload/pegawai/{$pegawai->foto}");
            }
        } else {
            $foto = $pegawai->foto; // Gunakan foto lama jika tidak upload baru
        }

        try {
            $data = [
                'username' => $username,
                'nama_lengkap' => $nama_lengkap,
                'password' => $password,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
            $update = DB::table('pegawais')->where('nik', $nik)->update($data);
            if ($update) {
                if ($request->hasFile('foto')) {
                    $folderPath = "public/upload/pegawai/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function deletePegawai(Request $request)
    {
        $nik = $request->nik;
        $data = DB::table('pegawais')->where('nik', $nik)->first();

        if ($data) {
            // Hapus file gambar jika ada
            if (!empty($data->foto)) {
                Storage::delete("public/upload/pegawai/{$data->foto}");
            }

            // Hapus data pegawai
            DB::table('pegawais')->where('nik', $nik)->delete();

            return redirect('/pegawai')->with(['success' => 'Data berhasil dihapus']);
        } else {
            return redirect('/pegawai')->with(['error' => 'Data tidak ditemukan']);
        }

    }

    public function resetPassword(Request $request)
    {
        $nik = $request->nik;
        $pegawai = DB::table('pegawais')->where('nik', $nik)->first();

        if ($pegawai) {
            DB::table('pegawais')
                ->where('nik', $nik)
                ->update(['password' => Hash::make('ypb12345')]);

            return redirect('/pegawai')->with(['success' => 'Password berhasil direset']);
        } else {
            return redirect('/pegawai')->with(['error' => 'Password gagal direset']);
        }

    }
}
