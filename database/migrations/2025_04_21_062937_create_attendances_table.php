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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 10);
            $table->date('tgl_presensi');
            $table->time('jam_in');
            $table->time('jam_out')->nullable();
            $table->string('bukti_in', 255);
            $table->string('bukti_out', 255)->nullable();
            $table->text('location_in');
            $table->text('location_out')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('nik')->references('nik')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance');
    }
};
