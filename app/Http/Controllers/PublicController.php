<?php

namespace App\Http\Controllers;

use App\Models\Paud;
use App\Models\Kecamatan;

class PublicController extends Controller
{
    // Halaman Dashboard / Beranda
    public function beranda()
    {
        $totalPaud = Paud::count();
        $totalKecamatan = Kecamatan::count();
        $totalKelurahan = Paud::whereNotNull('kelurahan')->distinct('kelurahan')->count('kelurahan');

        // Data Grafik Kecamatan
        $grafikKecamatan = Paud::select('kecamatan_id')
            ->selectRaw('count(*) as total')
            ->groupBy('kecamatan_id')
            ->with('kecamatan')
            ->get()
            ->map(function($item) {
                return [
                    'nama' => $item->kecamatan ? $item->kecamatan->nama : 'Lainnya',
                    'total' => $item->total
                ];
            });

        // Data Grafik Jenis
        $grafikJenis = Paud::select('jenis_satuan')
            ->selectRaw('count(*) as total')
            ->whereNotNull('jenis_satuan')
            ->groupBy('jenis_satuan')
            ->get();

        return view('public.beranda', compact('totalPaud', 'totalKecamatan', 'totalKelurahan', 'grafikKecamatan', 'grafikJenis'));
    }

    // Halaman Peta Interaktif
    public function peta()
    {
        // Ambil data Kecamatan untuk Dropdown Filter
        $kecamatan = Kecamatan::orderBy('nama')->get();
        
        // Ambil data PAUD yang punya koordinat beserta relasi kecamatannya
        $pauds = Paud::with('kecamatan')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
        
        return view('public.peta', compact('kecamatan', 'pauds'));
    }
}