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
        Schema::create('izin_khusus', function (Blueprint $table) {
            $table->id();
            $table->char('nik',10);
            $table->date('tanggal');
            $table->string('jenis_izin'); // ex: 'Setengah Hari', 'Pulang Awal'
            $table->time('jam_izin')->nullable(); // contoh: jam masuk 13:00 jika setengah hari
            $table->text('alasan')->nullable();
            $table->char('status', 1)->default('0');
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
        Schema::dropIfExists('izin_khusus');
    }
};
