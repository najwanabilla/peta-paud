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

        $denganKoordinat = $totalPaud - $tanpaKoordinat;

        $persenKoordinat = $totalPaud > 0
            ? (int) round($denganKoordinat / $totalPaud * 100)
            : 0;

        // Jumlah PAUD per jenis satuan (KB, TK, TPA, SPS)
        $jenisHitung = Paud::whereNotNull('jenis_satuan')
            ->selectRaw('jenis_satuan, count(*) as jumlah')
            ->groupBy('jenis_satuan')
            ->pluck('jumlah', 'jenis_satuan');

        $perJenis = collect(Paud::JENIS_SATUAN)
            ->map(fn ($label, $kode) => [
                'kode' => $kode,
                'label' => $label,
                'jumlah' => (int) ($jenisHitung[$kode] ?? 0),
            ])
            ->values();

        $jenisKosong = $totalPaud - $perJenis->sum('jumlah');

        return view('admin.dashboard', [
            'totalPaud' => $totalPaud,
            'tanpaKoordinat' => $tanpaKoordinat,
            'denganKoordinat' => $denganKoordinat,
            'persenKoordinat' => $persenKoordinat,
            'kecamatanAktif' => Kecamatan::aktif()->count(),
            'perKecamatan' => Kecamatan::withCount('pauds')->orderBy('nama')->get(),
            'perJenis' => $perJenis,
            'jenisKosong' => $jenisKosong,
            'paudTerbaru' => Paud::with('kecamatan:id,nama,warna')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get(),
        ]);
    }
}