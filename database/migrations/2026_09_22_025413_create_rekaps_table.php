<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekaps', function (Blueprint $table) {
            $table->id();
            
            // Kolom Baku / Umum
            $table->string('kode_sub_bidang'); // Contoh: 'A01', 'A02'
            $table->string('tahun');           // Contoh: '2026'
            $table->string('kawasan');         // Contoh: 'TWA Wera', 'CA Gunung Dako'
            
            // Kolom Dinamis (Penyimpan data kustom dari tiap sub-bidang)
            $table->json('data_kustom')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekaps');
    }
};