<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubBidang extends Model
{
    use HasFactory;

    protected $table = 'ref_sub_bidang';
    protected $guarded = [];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}