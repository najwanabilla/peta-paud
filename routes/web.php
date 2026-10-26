<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FotoPaudController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\PaudController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Halaman publik (sementara masih halaman bawaan Laravel, diganti di Tahap 7)
Route::view('/', 'welcome')->name('beranda');

// Login admin (tidak ada pendaftaran)
Route::get('/masuk', [LoginController::class, 'create'])->name('login');
Route::post('/masuk', [LoginController::class, 'store'])->name('login.store');
Route::post('/keluar', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Semua rute admin wajib login
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('kecamatan', KecamatanController::class)->except(['show']);
    Route::resource('paud', PaudController::class);

    // scopeBindings memastikan foto yang dihapus memang milik PAUD di alamat tersebut
    Route::scopeBindings()->group(function () {
        Route::patch('paud/{paud}/foto/{foto}/utama', [FotoPaudController::class, 'utama'])
            ->name('paud.foto.utama');
        Route::delete('paud/{paud}/foto/{foto}', [FotoPaudController::class, 'destroy'])
            ->name('paud.foto.destroy');
    });
});