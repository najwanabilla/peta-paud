<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Paud;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalPaud = Paud::count();

        // PAUD yang belum punya koordinat lengkap (latitude atau longitude kosong)
        $tanpaKoordinat = Paud::where(function ($query) {
            $query->whereNull('latitude')->orWhereNull('longitude');
        })->count();

        $kecamatanAktif = Kecamatan::aktif()->count();

        $perKecamatan = Kecamatan::withCount('pauds')->orderBy('nama')->get();

        return view('admin.dashboard', [
            'totalPaud' => $totalPaud,
            'tanpaKoordinat' => $tanpaKoordinat,
            'kecamatanAktif' => $kecamatanAktif,
            'perKecamatan' => $perKecamatan,
        ]);
    }
}