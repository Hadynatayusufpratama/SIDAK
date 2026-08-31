<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'ref_bidang';
    protected $guarded = [];

    public function subBidang()
    {
        return $this->hasMany(SubBidang::class, 'bidang_id');
    }
}