<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Lokasi::create([
            'lokasi_kantor' => '-6.5570523, 106.7689701',
            'radius' => 200,
        ]);

    }
}
