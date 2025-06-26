<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Exports\RekapPresensiExport;
use App\Exports\LaporanPresensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Pegawai;
use App\Notifications\IzinKhusus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;


class PresensiController extends Controller
{
    public function create()
    {

        $hariIni = date("Y-m-d");
        $nik = Auth::guard('pegawai')->user()->nik;
        $cek = DB::table('attendances')->where('tgl_presensi', $hariIni)->where('nik', $nik)->count();
        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_presensi = date('Y-m-d');
        $jam = date("H:i:s");

        $cek_pengajuan = DB::table('pengajuan_izins')
            ->where('nik', $nik)
            ->where('status_approved', 1)
            ->whereDate('tgl_izin_dari', '<=', $tgl_presensi)
            ->whereDate('tgl_izin_sampai', '>=', $tgl_presensi)
            ->first();

        if ($cek_pengajuan) {
            // Konversi kode status ke teks
            $jenis = match (strtolower($cek_pengajuan->status)) {
                'i' => 'Izin',
                's' => 'Sakit',
                'c' => 'Cuti',
            // default => 'PENGAJUAN'
            };

            echo "error|Tidak bisa absen karena Anda memiliki pengajuan $jenis yang disetujui pada tanggal ini|";
            return;
        }


        $lok_kantor = DB::table('konfigurasi_lokasi')->where('id', 1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];

        $jamKerja = DB::table('jam_kerja')->first();
        $awalMasuk = $jamKerja->awal_jam_masuk;
        $jamMasuk = $jamKerja->jam_masuk;
        $akhirMasuk = $jamKerja->akhir_jam_masuk;
        $jamPulang = $jamKerja->jam_pulang;

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('attendances')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        $ket = $cek > 0 ? "out" : "in";
        $image = $request->image;
        // $folderPath = "public/upload/absensi/";
        $folderPath = "upload/absensi";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        // $file = $folderPath . $fileName;
        $file = "public/{$folderPath}/{$fileName}";
        $absolutePath = storage_path("app/public/" . $folderPath);
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        $izin = DB::table('izin_khusus')
            ->where('nik', $nik)
            ->where('tanggal', $tgl_presensi)
            ->where('status', '1')
            ->first();

        if ($radius > $lok_kantor->radius) {
            echo "error|Maaf anda berada diluar Radius, Jarak anda " . $radius . " meter dari kantor|";
            return;
        }
        if ($ket == "in") {
            if ($izin && $izin->jenis_izin == 'masuk siang') {
                if ($jam < $izin->jam_izin) {
                    echo "error|Belum masuk waktu absen Anda sesuai izin (masuk siang jam " . date('H:i', strtotime($izin->jam_izin)) . ")|";
                    return;
                }
            } else {
                if ($jam < $awalMasuk) {
                    echo "error|Belum bisa melakukan absen masuk|";
                    return;
                } elseif ($jam > $akhirMasuk) {
                    echo "error|Maaf anda tidak dapat melakukan absen masuk (lewat dari batas waktu)|";
                    return;
                }
            }
        }
        if ($ket == "out") {
            if ($izin && $izin->jenis_izin == 'pulang awal') {
                if ($jam < $izin->jam_izin) {
                    echo "error|Maaf anda belum bisa absen pulang sesuai jam izin yang diberikan (" . substr($izin->jam_izin, 0, 5) . ")|";
                    return;
                }
            } else {
                if ($jam < $jamPulang) {
                    echo "error|Maaf belum waktunya absen pulang|";
                    return;
                }
            }
        }
        if ($ket == "out") {
            $data_pulang = [
                'jam_out' => $jam,
                'bukti_out' => $fileName,
                'location_out' => $lokasi
            ];
            $update = DB::table('attendances')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->update($data_pulang);

            if ($update && $image_base64) {
                echo "success|Terima kasih, Hati-hati dijalan!|out";
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf Absensi anda Gagal, Silahkan coba lagi atau hubungi IT|out";
            }
        } else {
            $data = [
                'nik' => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_in' => $jam,
                'bukti_in' => $fileName,
                'location_in' => $lokasi
            ];

            $simpan = DB::table('attendances')->insert($data);
            if ($simpan) {
                if ($jam > $jamMasuk) {
                    echo "success|Terima kasih, Selamat bekerja!|in";
                }
                Storage::put($file, $image_base64);
            } else {
                echo "error|Maaf Absensi anda Gagal, Silahkan coba lagi atau hubungi IT|in";
            }
        }

    }

    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editProfile()
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $pegawai = DB::table('pegawais')->where('nik', $nik)->first();
        return view('presensi.editProfile', compact('pegawai'));
    }

    public function updateProfile(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $pegawai = DB::table('pegawais')->where('nik', $nik)->first();

        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $foto = null;

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            $foto = $nik . "." . time() . "." . $request->file('foto')->getClientOriginalExtension();
            // Hapus foto lama jika ada
            // if (!empty($pegawai->foto)) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete("upload/pegawai/{$pegawai->foto}");
                // Storage::delete("app/public/upload/pegawai/{$pegawai->foto}");
            }
        } else {
            $foto = $pegawai->foto; // Gunakan foto lama jika tidak upload baru
        }

        $data = [
            'nama_lengkap' => $nama_lengkap,
            'no_hp' => $no_hp,
            'foto' => $foto
        ];

        // Cek apakah user ingin mengubah password
        // if (!empty($request->new_password)) {
        if ($request->new_password) {
            // Validasi password lama
            if (!Hash::check($request->old_password, $pegawai->password)) {
                return Redirect::back()->with('error', 'Password lama tidak sesuai.');
            }

            // Validasi konfirmasi password baru
            if ($request->new_password !== $request->confirm_new_password) {
                return Redirect::back()->with('error', 'Konfirmasi password baru tidak cocok.');
            }

            // Jika semua validasi ok, update password baru
            $data['password'] = Hash::make($request->new_password);
        }

        // Update data di database
        $update = DB::table('pegawais')->where('nik', $nik)->update($data);

        if ($update && $foto) {
            if ($request->hasFile('foto')) {
                $folderPath = storage_path("app/public/upload/pegawai");
                if (!File::exists($folderPath))
                    File::makeDirectory($folderPath);
                $request->file('foto')->move($folderPath, $foto);
            }
            return Redirect::back()->with('success', 'Data berhasil diupdate.');
        } else {
            return Redirect::back()->with('error', 'Data gagal diupdate.');
        }
    }

    public function histori()
    {

        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('namabulan'));
    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('pegawai')->user()->nik;

        $histori = DB::table('attendances')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nik', $nik)
            ->orderBy('tgl_presensi')
            ->get();

        return view('presensi.gethistori', compact('histori'));
    }

    // public function izin()
    // {
    //     $nik = Auth::guard('pegawai')->user()->nik;
    //     $dataizin = DB::table('pengajuan_izin')->where('nik', $nik)->get();
    //     return view('presensi.izin', compact('dataizin'));
    // }
    public function izin()
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $dataizin = DB::table('pengajuan_izins')
            ->leftJoin('master_cuti', 'pengajuan_izins.kode_cuti', '=', 'master_cuti.kode_cuti')
            ->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataizin'));
    }

    public function buatizin()
    {
        return view('presensi.buatizin');
    }

    public function storeizin(Request $request)
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;
        $gambar = null;

        if ($request->hasFile('gambar')) {
            $folderPath = "upload/izin";
            $file = $request->file('gambar');

            // Format nama file: nik-tglizin-status
            $tglPresensi = date('Ymd', strtotime($tgl_izin)); // Format tanggal jadi Ymd (contoh: 20250428)
            $cleanStatus = preg_replace('/[^A-Za-z0-9]/', '', $status); // Hapus karakter aneh di status

            $baseName = $nik . '-' . $tglPresensi . '-' . $cleanStatus;
            $baseName = substr($baseName, 0, 45); // Biar aman < 50 karakter setelah tambah extension

            $extension = $file->getClientOriginalExtension();
            $filename = $baseName . '.' . $extension;

            // Simpan file
            $target_path = storage_path('/app/public/' . $folderPath);
            if (!File::exists($target_path))
                File::makeDirectory($target_path);
            $file->move($target_path, $filename);

            // Set path untuk disimpan ke database
            $gambar = $folderPath . '/' . $filename;
        }

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan,
            'gambar' => $gambar
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    // public function cekpengajuanizin(Request $request)
    // {
    //     $tgl_izin = $request->tgl_izin;
    //     $nik = Auth::guard('pegawai')->user()->nik;

    //     $cek = DB::table('pengajuan_izin')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
    //     return $cek;
    // }
    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin_dari = $request->tgl_izin_dari;
        $nik = Auth::guard('pegawai')->user()->nik;

        $cek = DB::table('pengajuan_izins')->where('nik', $nik)->where('tgl_izin_dari', $tgl_izin_dari)->count();
        return $cek;
    }

    // public function editizin($id)
    // {
    //     $izin = DB::table('pengajuan_izin')->where('id', $id)->first();

    //     if (!$izin) {
    //         return redirect('/presensi/izin')->with(['error' => 'Data tidak ditemukan']);
    //     }

    //     return view('presensi.editizin', compact('izin'));
    // }

    // public function updateizin(Request $request)
    // {
    //     $id = $request->id;
    //     $izin = DB::table('pengajuan_izin')->where('id', $id)->first();

    //     if (!$izin) {
    //         return redirect('/presensi/izin')->with(['error' => 'Data tidak ditemukan']);
    //     }

    //     $nik = Auth::guard('pegawai')->user()->nik;
    //     $tgl_izin = $request->tgl_izin;
    //     $status = $request->status;
    //     $keterangan = $request->keterangan;
    //     $gambar = $izin->gambar;

    //     // Handle gambar baru
    //     if ($request->hasFile('gambar')) {
    //         $folderPath = "upload/izin";
    //         $file = $request->file('gambar');

    //         // Format nama file baru
    //         $tglPresensiBaru = date('Ymd', strtotime($tgl_izin));
    //         $cleanStatus = preg_replace('/[^A-Za-z0-9]/', '', $status);
    //         $baseName = $nik . '-' . $tglPresensiBaru . '-' . $cleanStatus;
    //         $baseName = substr($baseName, 0, 45);
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = $baseName . '.' . $extension;

    //         // Jika tanggal sama, hapus gambar lama
    //         if ($tgl_izin == $izin->tgl_izin && $gambar && Storage::disk('public')->exists($gambar)) {
    //             Storage::disk('public')->delete($gambar);
    //         }
    //         $target_path = storage_path('/app/public/' . $folderPath);
    //         if (!File::exists($target_path))
    //             File::makeDirectory($target_path);
    //         $file->move($target_path, $filename);
    //         $gambar = $folderPath . '/' . $filename;
    //     }

    //     $update = DB::table('pengajuan_izin')->where('id', $id)->update([
    //         'tgl_izin' => $tgl_izin,
    //         'status' => $status,
    //         'keterangan' => $keterangan,
    //         'gambar' => $gambar
    //     ]);

    //     if ($update) {
    //         return redirect('/presensi/izin')->with(['success' => 'Data berhasil diupdate']);
    //     } else {
    //         return redirect('/presensi/izin')->with(['error' => 'Data gagal diupdate']);
    //     }
    // }


    // public function deleteizin(Request $request)
    // {
    //     $id = $request->id;
    //     $data = DB::table('pengajuan_izin')->where('id', $id)->first();

    //     if ($data) {
    //         // Hapus file gambar jika ada
    //         if ($data->gambar) {
    //             Storage::disk('public')->delete($data->gambar);
    //         }

    //         // Hapus data izin
    //         DB::table('pengajuan_izin')->where('id', $id)->delete();

    //         return redirect('/presensi/izin')->with(['success' => 'Data berhasil dihapus']);
    //     } else {
    //         return redirect('/presensi/izin')->with(['error' => 'Data tidak ditemukan']);
    //     }
    // }

    public function izinkhusus()
    {
        $nik = Auth::guard('pegawai')->user()->nik;
        $izinkhusus = DB::table('izin_khusus')->where('nik', $nik)->get();
        return view('presensi.izinkhusus', compact('izinkhusus'));
    }

    public function buatizinkhusus()
    {
        return view('presensi.buatizinkhusus');
    }

    public function storeizinkhusus(Request $request)
    {
        // $request->validate([
        //     'tanggal' => 'required|date',
        //     'jenis_izin' => 'required|string|max:255',
        //     'jam_izin' => 'required|date_format:H:i',
        //     'alasan' => 'nullable|string|max:500',
        // ]);

        // $nik = Auth::guard('pegawai')->user()->nik;
        // $tanggal = $request->tanggal;
        // $jenis_izin = $request->jenis_izin;
        // $jam_izin = $request->jam_izin . ':00';
        // $alasan = $request->alasan;

        // $data = [
        //     'nik' => $nik,
        //     'tanggal' => $tanggal,
        //     'jenis_izin' => $jenis_izin,
        //     'jam_izin' => $jam_izin,
        //     'alasan' => $alasan
        // ];

        $jenis_izin = $request->jenis_izin;

        $rules = [
            'tanggal' => 'required|date',
            'jenis_izin' => 'required|string|max:255',
            'alasan' => 'nullable|string|max:500',
        ];

        // Jika bukan 'lembur', jam_izin wajib dan format harus H:i
        if (strtolower($jenis_izin) !== 'lembur') {
            $rules['jam_izin'] = 'required|date_format:H:i';
        } else {
            // Jika 'lembur', jam_izin bisa null, jadi aturan nullable dan format opsional
            $rules['jam_izin'] = 'nullable|date_format:H:i';
        }

        $validated = $request->validate($rules);

        $nik = Auth::guard('pegawai')->user()->nik;
        $tanggal = $validated['tanggal'];
        $jenis_izin = $validated['jenis_izin'];
        $alasan = $validated['alasan'] ?? null;

        // Jika 'lembur', jam_izin jadi null, kalau bukan ambil dari input dan tambahkan ':00'
        $jam_izin = null;
        if (strtolower($jenis_izin) !== 'lembur' && isset($validated['jam_izin'])) {
            $jam_izin = $validated['jam_izin'] . ':00';
        }

        $data = [
            'nik' => $nik,
            'tanggal' => $tanggal,
            'jenis_izin' => $jenis_izin,
            'jam_izin' => $jam_izin,
            'alasan' => $alasan
        ];


        $simpan = DB::table('izin_khusus')->insert($data);

        $pegawai = Pegawai::where('nik', $nik)->first();
        $userOperator = User::all();
        Notification::send($userOperator, new IzinKhusus($pegawai));

        if ($simpan) {
            return redirect('/presensi/izinkhusus')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('/presensi/izinkhusus')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function editizinkhusus($id)
    {
        $izinkhusus = DB::table('izin_khusus')->where('id', $id)->first();

        if (!$izinkhusus) {
            return redirect('/presensi/izinkhusus')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('presensi.editizinkhusus', compact('izinkhusus'));
    }

    public function updateizinkhusus(Request $request)
    {
        $id = $request->id;
        $izinkhusus = DB::table('izin_khusus')->where('id', $id)->first();

        if (!$izinkhusus) {
            return redirect('/presensi/izinkhusus')->with(['error' => 'Data tidak ditemukan']);
        }

        $nik = Auth::guard('pegawai')->user()->nik;
        $tanggal = $request->tanggal;
        $jenis_izin = $request->jenis_izin;
        $jam_izin = $jam_izin = Carbon::parse($request->jam_izin)->format('H:i:s');
        $alasan = $request->alasan;


        $update = DB::table('izin_khusus')->where('id', $id)->update([
            'tanggal' => $tanggal,
            'jenis_izin' => $jenis_izin,
            'jam_izin' => $jam_izin,
            'alasan' => $alasan
        ]);

        if ($update) {
            return redirect('/presensi/izinkhusus')->with(['success' => 'Data berhasil diupdate']);
        } else {
            return redirect('/presensi/izinkhusus')->with(['error' => 'Data gagal diupdate']);
        }
    }


    public function deleteizinkhusus(Request $request)
    {
        $id = $request->id;
        $data = DB::table('izin_khusus')->where('id', $id)->first();

        if ($data) {
            // Hapus data izin
            DB::table('izin_khusus')->where('id', $id)->delete();

            return redirect('/presensi/izinkhusus')->with(['success' => 'Data berhasil dihapus']);
        } else {
            return redirect('/presensi/izinkhusus')->with(['error' => 'Data tidak ditemukan']);
        }
    }

    public function cekpengajuanizinkhusus(Request $request)
    {
        $tanggal = $request->tanggal;
        $nik = Auth::guard('pegawai')->user()->nik;

        $cek = DB::table('izin_khusus')->where('nik', $nik)->where('tanggal', $tanggal)->count();
        return $cek;
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $attendance = DB::table('attendances')
            ->select('attendances.*', 'nama_lengkap', 'jabatan')
            ->join('pegawais', 'attendances.nik', '=', 'pegawais.nik')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getpresensi', compact('attendance'));
    }

    public function showmap(Request $request)
    {
        $id = $request->id;
        $attendance = DB::table('attendances')->where('id', $id)
            ->join('pegawais', 'attendances.nik', '=', 'pegawais.nik')
            ->first();
        return view('presensi.showmap', compact('attendance'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $pegawai = DB::table('pegawais')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'pegawai'));
    }

    public function cetaklaporan(Request $request)
    {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        if ($request->has('exportexcel')) {
            $namaFile = 'Laporan_Presensi_' . $nik . '_' . $bulan . '_' . $tahun . '.xlsx';
            return Excel::download(new LaporanPresensiExport($nik, $bulan, $tahun), $namaFile);
        }

        $jamKerja = DB::table('jam_kerja')->first();
        $jamkantor = $jamKerja->jam_masuk;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $pegawai = DB::table('pegawais')->where('nik', $nik)->first();
        $attendance = DB::table('attendances')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_presensi) = ?', [$tahun])
            ->orderBy('tgl_presensi')
            ->get();
        $izinMasukSiang = DB::table('izin_khusus')
            ->select('nik', 'tanggal')
            ->where('status', 1)
            ->where('jenis_izin', 'masuk siang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy('nik');
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'pegawai', 'attendance', 'jamkantor', 'izinMasukSiang'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }

    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $jamKerja = DB::table('jam_kerja')->first();
        $jamkantor = $jamKerja->jam_masuk;
        $namabulan = [
            "",
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];

        if ($bulan) {
            // Cetak satu bulan saja
            $rekap = $this->getRekapPresensi($bulan, $tahun);
            $izinMasukSiang = DB::table('izin_khusus')
                ->select('nik', 'tanggal')
                ->where('status', 1)
                ->where('jenis_izin', 'masuk siang')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get()
                ->groupBy('nik');

            $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfDay();
            $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();
            $izinData = DB::table('pengajuan_izins')
                ->select('nik', 'tgl_izin_dari', 'tgl_izin_sampai', 'status_approved', 'status')
                ->where('status_approved', 1)
                ->where(function ($query) use ($awalBulan, $akhirBulan) {
                    $query->whereDate('tgl_izin_dari', '<=', $akhirBulan)
                        ->whereDate('tgl_izin_sampai', '>=', $awalBulan);
                })
                ->get()
                ->groupBy('nik');

            return view('presensi.cetakrekap', compact('rekap', 'bulan', 'tahun', 'namabulan', 'jamkantor', 'izinMasukSiang', 'izinData'));
        } else {
            // Cetak semua bulan dalam satu tahun
            $rekap_perbulan = [];

            for ($i = 1; $i <= 12; $i++) {
                $rekap_perbulan[$i] = $this->getRekapPresensi($i, $tahun);
            }

            $izinMasukSiang = DB::table('izin_khusus')
                ->select('nik', 'tanggal')
                ->where('status', 1)
                ->where('jenis_izin', 'masuk siang')
                ->whereYear('tanggal', $tahun)
                ->get()
                ->groupBy(function ($item) {
                    return $item->nik . '-' . date('m', strtotime($item->tanggal));
                });

            $awalTahun = Carbon::create($tahun, 1, 1)->startOfDay();
            $akhirTahun = Carbon::create($tahun, 12, 31)->endOfDay();
            $izinData = DB::table('pengajuan_izins')
                ->select('nik', 'tgl_izin_dari', 'tgl_izin_sampai', 'status_approved', 'status')
                ->where('status_approved', 1)
                ->whereDate('tgl_izin_dari', '<=', $akhirTahun)
                ->whereDate('tgl_izin_sampai', '>=', $awalTahun)
                ->get()
                ->groupBy(function ($item) {
                    return $item->nik . '-' . date('m', strtotime($item->tgl_izin_dari));
                });

            return view('presensi.cetakrekap', compact('rekap_perbulan', 'tahun', 'namabulan', 'jamkantor', 'izinMasukSiang', 'izinData'));
        }
    }

    private function getRekapPresensi($bulan, $tahun)
    {
        return DB::table('attendances')
            ->selectRaw('attendances.nik,nama_lengkap,
            MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_1,
            MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_2,
            MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_3,
            MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_4,
            MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_5,
            MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_6,
            MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_7,
            MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_8,
            MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_9,
            MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_10,
            MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_11,
            MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_12,
            MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_13,
            MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_14,
            MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_15,
            MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_16,
            MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_17,
            MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_18,
            MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_19,
            MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_20,
            MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_21,
            MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_22,
            MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_23,
            MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_24,
            MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_25,
            MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_26,
            MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_27,
            MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_28,
            MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_29,
            MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_30,
            MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_in,"-",IFNULL(jam_out,"00:00:00")),"")) as tgl_31')
            ->join('pegawais', 'attendances.nik', '=', 'pegawais.nik')
            ->whereMonth('tgl_presensi', $bulan)
            ->whereYear('tgl_presensi', $tahun)
            ->groupBy('attendances.nik', 'nama_lengkap')
            ->get();
    }

    // public function dataizin()
    // {
    //     $dataizin = DB::table('pengajuan_izin')
    //         ->join('pegawais', 'pengajuan_izin.nik', '=', 'pegawais.nik')
    //         ->orderByDesc('tgl_izin')
    //         ->get();
    //     return view('presensi.dataizin', compact('dataizin'));
    // }
    public function dataizin()
    {
        $dataizin = DB::table('pengajuan_izins')
            ->join('pegawais', 'pengajuan_izins.nik', '=', 'pegawais.nik')
            ->orderByDesc('id')
            ->get();
        return view('presensi.dataizin', compact('dataizin'));
    }

    // public function approveizin(Request $request)
    // {
    //     $status_approved = $request->status_approved;
    //     $id_izinsaki_form = $request->id_izinsakit_form;
    //     $update = DB::table('pengajuan_izin')->where('id', $id_izinsaki_form)->update([
    //         'status_approved' => $status_approved
    //     ]);
    //     if ($update) {
    //         return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
    //     } else {
    //         return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
    //     }
    // }
    public function approveizin(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsaki_form = $request->id_izinsakit_form;
        $update = DB::table('pengajuan_izins')->where('id', $id_izinsaki_form)->update([
            'status_approved' => $status_approved
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function batalkanizin($id)
    {
        $update = DB::table('pengajuan_izins')->where('id', $id)->update([
            'status_approved' => 0
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function dataizinkhusus()
    {
        $dataizinkhusus = DB::table('izin_khusus')
            ->join('pegawais', 'izin_khusus.nik', '=', 'pegawais.nik')
            ->orderByDesc('tanggal')
            ->get();
        return view('presensi.dataizinkhusus', compact('dataizinkhusus'));
    }

    public function approveizinkhusus(Request $request)
    {
        $status = $request->status;
        $id_izin_form = $request->id_izin_form;
        $update = DB::table('izin_khusus')->where('id', $id_izin_form)->update([
            'status' => $status
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function batalkanizinkhusus($id)
    {
        $update = DB::table('izin_khusus')->where('id', $id)->update([
            'status' => 0
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function exportRekapPresensi(Request $request)
    {
        $namabulan = [
            "", // index 0
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (empty($tahun)) {
            return back()->with('error', 'Silakan pilih tahun terlebih dahulu.');
        }

        // Jika semua bulan (value bulan adalah "")
        if ($bulan === "") {
            $rekap = collect(); // kosongkan karena akan diambil di export class
            $bulan = null; // diset null agar diproses sebagai "semua bulan"
            return Excel::download(
                new RekapPresensiExport($rekap, $bulan, $tahun, $namabulan),
                'rekap-presensi-tahun-' . $tahun . '.xlsx'
            );
        }

        // Jika bulan tertentu
        $rekap = $this->getRekapPresensi($bulan, $tahun);
        return Excel::download(
            new RekapPresensiExport($rekap, $bulan, $tahun, $namabulan),
            'rekap-presensi-' . $namabulan[(int) $bulan] . '-' . $tahun . '.xlsx'
        );
    }

}