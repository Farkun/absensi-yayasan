<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JamKerja;

class JamKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JamKerja::create([
            'awal_jam_masuk' => '07:30:00',
            'jam_masuk' => '09:00:00',
            'akhir_jam_masuk' => '10:30:00',
            'jam_pulang' => '17:00:00',
        ]);
    }
}
