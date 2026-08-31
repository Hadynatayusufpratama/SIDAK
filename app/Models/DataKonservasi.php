<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKonservasi extends Model
{
    use HasFactory;

    protected $table = 'data_konservasi';
    protected $guarded = [];

    public function subBidang()
    {
        return $this->belongsTo(SubBidang::class, 'sub_bidang_id');
    }
}