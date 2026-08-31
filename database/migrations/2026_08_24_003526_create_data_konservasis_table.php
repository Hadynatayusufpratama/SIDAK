<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_konservasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_bidang_id')->constrained('ref_sub_bidang')->onDelete('cascade');
            $table->integer('tahun');
            $table->integer('bulan')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('jumlah')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_konservasi');
    }
};