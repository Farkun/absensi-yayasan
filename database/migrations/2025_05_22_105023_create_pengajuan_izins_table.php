<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_izins', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 10); 
            $table->date('tgl_izin_dari');
            $table->date('tgl_izin_sampai');
            $table->char('status', 1)->comment('i: izin, s: sakit');
            $table->char('kode_cuti', 3)->nullable(); 
            $table->string('keterangan', 255)->nullable();
            $table->string('gambar', 50)->nullable();
            $table->string('doc_sid', 255)->nullable(); 
            $table->char('status_approved', 1)->default('0')->comment('0: Pending, 1: Disetujui, 2: Ditolak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_izins');
    }
};
