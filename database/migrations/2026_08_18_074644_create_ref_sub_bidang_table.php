<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_sub_bidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('ref_bidang')->onDelete('cascade');
            $table->string('kode_sub');
            $table->string('nama_sub_bidang');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_sub_bidang');
    }
};