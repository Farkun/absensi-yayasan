<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CutiController extends Controller
{
    public function index() {
        $cuti = DB::table('master_cuti')->orderBy('kode_cuti', 'asc')->get();
        return view('cuti.index', compact('cuti'));
    }

    public function store(Request $request) {
        $kode_cuti = $request->kode_cuti;
        $nama_cuti = $request->nama_cuti;
        $jml_hari = $request->jml_hari;

        $cekcuti = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->count();
        if($cekcuti>0){
            return Redirect::back()->with(['warning' => 'Data kode Cuti Sudah Ada']);
        }

        try {
            DB::table('master_cuti')->insert([
                'kode_cuti' => $kode_cuti,
                'nama_cuti' => $nama_cuti,
                'jml_hari' => $jml_hari
            ]);
            return Redirect::back()->with(['succes' => 'Data Berhasil Disimpan']);
        } catch (\exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $kode_cuti = $request->kode_cuti;
        $cuti = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->first();
        return view('cuti.edit', compact('cuti'));
    }

    public function update($kode_cuti, Request $request)
    {
        $nama_cuti = $request->nama_cuti;
        $jml_hari = $request->jml_hari;

        try {
            $data = [
                'nama_cuti' => $nama_cuti,
                'jml_hari' => $jml_hari,
            ];
            $update = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->update($data);
            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function delete(Request $request)
    {
        $kode_cuti = $request->kode_cuti;
        $data = DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->first();

        if ($data) {
            DB::table('master_cuti')->where('kode_cuti', $kode_cuti)->delete();

            return redirect('/cuti')->with(['success' => 'Data berhasil dihapus']);
        } else {
            return redirect('/cuti')->with(['error' => 'Data tidak ditemukan']);
        }

    }

    
}
