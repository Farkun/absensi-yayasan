<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Redirect;

class KonfigurasiController extends Controller
{
    public function lokasikantor() {
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('konfigurasi.lokasikantor', compact('lok_kantor'));
    }

    public function updatelokasikantor(Request $request) {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasi')->where('id',1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius
        ]);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning'=>'Data Gagal Diupdate']);
        }
    }

    public function jamkerja() {
        $jam_kantor = DB::table('jam_kerja')->where('id', 1)->first();
        return view('konfigurasi.jamkerja',compact('jam_kantor'));
    }

    public function updatejamkerja(Request $request) {
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        $update = DB::table('jam_kerja')->where('id',1)->update([
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuk' => $jam_masuk,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulang' => $jam_pulang
        ]);

        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning'=>'Data Gagal Diupdate']);
        }
    }
}
