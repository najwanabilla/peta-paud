<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FotoPaudController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\PaudController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// --- RUTE PUBLIK ---
Route::get('/', [PublicController::class, 'beranda'])->name('beranda');
Route::get('/peta', [PublicController::class, 'peta'])->name('peta');

// --- RUTE LOGIN ADMIN ---
Route::get('/masuk', [LoginController::class, 'create'])->name('login');
Route::post('/masuk', [LoginController::class, 'store'])->name('login.store');
Route::post('/keluar', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// --- RUTE ADMIN (Wajib Login) ---
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('kecamatan', KecamatanController::class)->except(['show']);
    Route::resource('paud', PaudController::class);

    Route::scopeBindings()->group(function () {
        Route::patch('paud/{paud}/foto/{foto}/utama', [FotoPaudController::class, 'utama'])
            ->name('paud.foto.utama');
        Route::delete('paud/{paud}/foto/{foto}', [FotoPaudController::class, 'destroy'])
            ->name('paud.foto.destroy');
    });
});