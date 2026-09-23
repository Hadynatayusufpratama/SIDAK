<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekap extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_sub_bidang',
        'tahun',
        'kawasan',
        'data_kustom',
    ];

    // Otomatis ubah JSON ke Array PHP dan sebaliknya
    protected $casts = [
        'data_kustom' => 'array',
    ];
}