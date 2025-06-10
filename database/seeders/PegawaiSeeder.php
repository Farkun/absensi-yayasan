<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;
class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * 
     */
    public function run():void
    {
        Pegawai::create([
            'nik' => '1234',
            'username' => 'pegawai',
            'nama_lengkap' => 'Pegawai Example',
            'jabatan' => 'User Testing',
            'no_hp' => '088888888099',
            'password' => Hash::make('ypb12345'),
            'foto' => ''
        ]);
    }
}
