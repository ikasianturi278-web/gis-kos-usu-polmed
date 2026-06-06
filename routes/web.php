<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KosanController;

// Dashboard (halaman utama)
Route::get('/', [KosanController::class, 'dashboard'])->name('dashboard');

// Peta Kosan
Route::get('/maps', [KosanController::class, 'maps'])->name('maps');

// Data Kosan (CRUD)
Route::get('/kosan', [KosanController::class, 'index'])->name('kosan.index');
Route::get('/kosan/tambah', [KosanController::class, 'create'])->name('kosan.create');
Route::post('/kosan', [KosanController::class, 'store'])->name('kosan.store');
Route::get('/kosan/{id}', [KosanController::class, 'show'])->name('kosan.show');
Route::delete('/kosan/{id}', [KosanController::class, 'destroy'])->name('kosan.destroy');

// API untuk data peta (JSON)
Route::get('/api/kosan', [KosanController::class, 'apiKosan'])->name('api.kosan');