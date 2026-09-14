<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KonservasiController;

// Pengunjung dialihkan ke Landing Page SIDAK BKSDA
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ROUTE SIDAK BKSDA (Wajib Login)
Route::middleware(['auth', 'verified'])->group(function () {
    // Berikan SATU nama route saja di sini:
    Route::get('/dashboard', [KonservasiController::class, 'dashboard'])->name('konservasi.dashboard');
    
    Route::get('/konservasi', [KonservasiController::class, 'index'])->name('konservasi.index');
    Route::get('/konservasi/create', [KonservasiController::class, 'create'])->name('konservasi.create');
    Route::post('/konservasi', [KonservasiController::class, 'store'])->name('konservasi.store');
    
    // ROUTE TAMBAHAN UNTUK EDIT & UPDATE DATA
    Route::get('/konservasi/{id}/edit', [KonservasiController::class, 'edit'])->name('konservasi.edit');
    Route::put('/konservasi/{id}', [KonservasiController::class, 'update'])->name('konservasi.update');
    
    // ROUTE TAMBAHAN UNTUK HAPUS DATA
    Route::delete('/konservasi/{id}', [KonservasiController::class, 'destroy'])->name('konservasi.destroy');

    Route::get('/peta', [KonservasiController::class, 'peta'])->name('konservasi.peta');

    // Route AJAX untuk memuat Sub-Bidang
    Route::get('/get-sub-bidang/{bidang_id}', [KonservasiController::class, 'getSubBidang']);
});

// Panggil file route autentikasi dari Breeze
require __DIR__.'/auth.php';