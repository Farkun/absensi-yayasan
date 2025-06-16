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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->char('nik', 16)->primary();
            $table->string('username', 10);
            $table->string('nama_lengkap', 100);
            $table->string('jabatan', 20);
            $table->string('no_hp', 13);
            $table->text('password');
            $table->string('foto',50); 
            $table->rememberToken(); // setara dengan varchar(255) nullable
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
        Schema::dropIfExists('pegawais');
    }
};
