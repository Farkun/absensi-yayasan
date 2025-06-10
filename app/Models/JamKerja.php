<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamKerja extends Model
{
    use HasFactory;

    protected $table = 'jam_kerja'; 

    protected $fillable = [
        'awal_jam_masuk',
        'jam_masuk',
        'akhir_jam_masuk',
        'jam_pulang',
    ];

    protected $casts = [
        'awal_jam_masuk' => 'datetime:H:i',
        'jam_masuk' => 'datetime:H:i',
        'akhir_jam_masuk' => 'datetime:H:i',
        'jam_pulang' => 'datetime:H:i',
    ];
}
