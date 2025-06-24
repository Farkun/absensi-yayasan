<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;
    protected $table = 'master_cuti';
    protected $fillable = [
        'kode_cuti',
        'nama_cuti',
        'jml_hari'
    ]; 
}
