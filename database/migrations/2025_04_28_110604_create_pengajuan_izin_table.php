<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 10);
            $table->date('tgl_izin');
            $table->char('status', 1);
            $table->string('keterangan', 255);
            $table->string('gambar',50)->nullable();
            $table->char('status_approved', 1)->default('0');
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
        Schema::dropIfExists('pengajuan_izin');
    }
};
