<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\NotificationsController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware(['guest:pegawai','prevent-back-history'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('log');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['guest:user','prevent-back-history'])->group(function () {
    Route::get('/adm', function () {
        return view('auth.loginadmin');
    })->name('loginadm');
    Route::post('/loginadmin', [AuthController::class, 'loginadmin']);
});


Route::middleware(['auth:pegawai', 'prevent-back-history'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('create');
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    Route::get('/editProfile', [PresensiController::class, 'editProfile']);
    Route::post('presensi/{nik}/updateProfile', [PresensiController::class, 'updateProfile']);

    Route::get('/presensi/histori', [PresensiController::class, 'histori'])->name('histori');
    Route::post("/gethistori", [PresensiController::class, 'gethistori']);

    Route::get('/presensi/izin', [PresensiController::class, 'izin'])->name('izin');
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);
    Route::get('/presensi/editizin/{id}', [PresensiController::class, 'editizin'])->name('editizin');
    Route::post('/presensi/updateizin', [PresensiController::class, 'updateizin'])->name('updateizin');
    Route::post('/presensi/izin/delete', [PresensiController::class, 'deleteizin'])->name('deleteizin');

    Route::get('/presensi/izinkhusus', [PresensiController::class, 'izinkhusus'])->name('izinkhusus');
    Route::get('/presensi/buatizinkhusus', [PresensiController::class, 'buatizinkhusus']);
    Route::post('/presensi/storeizinkhusus', [PresensiController::class, 'storeizinkhusus']);
    Route::post('/presensi/cekpengajuanizinkhusus', [PresensiController::class, 'cekpengajuanizinkhusus']);
    Route::get('/presensi/editizinkhusus/{id}', [PresensiController::class, 'editizinkhusus'])->name('editizinkhusus');
    Route::post('/presensi/updateizinkhusus', [PresensiController::class, 'updateizinkhusus'])->name('updateizinkhusus');
    Route::post('/presensi/izinkhusus/delete', [PresensiController::class, 'deleteizinkhusus'])->name('deleteizinkhusus');

        //absensi izin
        Route::get('/izinabsen', [IzinabsenController::class, 'create']);
        Route::post('/izinabsen/store', [IzinabsenController::class, 'store']);
        Route::get('/izinabsen/edit/{id}', [IzinabsenController::class, 'edit'])->name('edit_izin');
        Route::post('/izinabsen/update', [IzinabsenController::class, 'update'])->name('update_izin');
        Route::post('/izinabsen/cekpengajuan', [IzinabsenController::class, 'cekPengajuan']);

        //absensi sakit 
        Route::get('/izinsakit', [IzinsakitController::class, 'create']);
        Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
        Route::get('/izinsakit/edit/{id}', [IzinsakitController::class, 'edit'])->name('edit_sakit');
        Route::post('/izinsakit/update', [IzinsakitController::class, 'update'])->name('update_sakit');

        //izin cuti 
        Route::get('/izincuti', [IzincutiController::class, 'create']);
        Route::post('/izincuti/store', [IzincutiController::class, 'store']);
        Route::get('/izincuti/edit/{id}', [IzincutiController::class, 'edit'])->name('edit_cuti');
        Route::post('/izincuti/update', [IzincutiController::class, 'update'])->name('update_cuti');
        Route::post('/izincuti/getmaxcuti', [IzincutiController::class, 'getmaxcuti'])->name('getmaxcuti');

    //delete izin,sakit,cuti
    Route::post('/presensi/izin/delete', [IzinabsenController::class, 'delete'])->name('delete_izin');
});
Route::middleware(['auth:user', 'prevent-back-history'])->group(function () {
    Route::get('/logoutadmin', [AuthController::class, 'logoutadmin']);
    Route::get('/adm/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    Route::get('/pegawai', [PegawaiController::class, 'index']);
    Route::post('/pegawai/store', [PegawaiController::class, 'store']);
    Route::post('/pegawai/delete', [PegawaiController::class, 'deletePegawai']);
    Route::post('/pegawai/edit', [PegawaiController::class, 'edit']);
    Route::post('/pegawai/{nik}/update', [PegawaiController::class, 'update']);
    Route::post('/pegawai/resetPassword', [PegawaiController::class, 'resetPassword']);

    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/showmap', [PresensiController::class, 'showmap']);

    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::post('/presensi/export-rekap-presensi', [PresensiController::class, 'exportRekapPresensi'])->name('export.rekap');

    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);

    Route::get('/presensi/dataizin', [PresensiController::class, 'dataizin']);
    Route::post('/presensi/approveizin', [PresensiController::class, 'approveizin']);
    Route::get('/presensi/{id}/batalkanizin', [PresensiController::class, 'batalkanizin']);

    Route::get('/presensi/dataizinkhusus', [PresensiController::class, 'dataizinkhusus']);
    Route::post('/presensi/approveizinkhusus', [PresensiController::class, 'approveizinkhusus']);
    Route::get('/presensi/{id}/batalkanizinkhusus', [PresensiController::class, 'batalkanizinkhusus']);

    //Cuti 
    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::post('/cuti/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::post('/cuti/delete', [CutiController::class, 'delete']);

    Route::get('/notifications/read/{id}', [NotificationsController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/historinotif', [NotificationsController::class, 'history'])->name('historinotif.index');
});

