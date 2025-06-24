<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cuti;

class CutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cuti::insert([
            [
                'kode_cuti' => 'C05',
                'nama_cuti' => 'Tahunan',
                'jml_hari' => 12
            ],
            [
                'kode_cuti' => 'C06',
                'nama_cuti' => 'Cuti Kompensasi',
                'jml_hari' => 0
            ]
        ]);
    }
}
