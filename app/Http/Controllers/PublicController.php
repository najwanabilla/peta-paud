<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Paud;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    private const LAT_MIN = -1.45;
    private const LAT_MAX = -1.00;
    private const LNG_MIN = 116.65;
    private const LNG_MAX = 117.10;

    // HALAMAN BERANDA
    public function beranda()
    {
        $kecamatans = Kecamatan::aktif()
            ->orderBy('nama')
            ->get(['id', 'nama', 'slug']);

        return view('public.beranda', compact('kecamatans'));
    }

    // DAFTAR DAN PENCARIAN PAUD
    public function daftar(Request $request)
    {
        $request->validate([
            'jenis' => 'nullable|in:TK,KB,TPA,SPS',
            'q' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
        ]);

        $kecamatans = Kecamatan::aktif()
            ->orderBy('nama')
            ->get(['id', 'nama', 'slug']);

        $idKecamatanAktif = $kecamatans->pluck('id');

        $query = Paud::with('kecamatan')
            ->whereIn('kecamatan_id', $idKecamatanAktif);

        // FILTER JENIS SEKOLAH
        if ($request->filled('jenis')) {
            $query->where('jenis_satuan', $request->jenis);
        }

        // FILTER KECAMATAN
        if ($request->filled('kecamatan')) {
            $query->whereHas('kecamatan', function ($q) use ($request) {
                $q->where('slug', $request->kecamatan);
            });
        }

        // PENCARIAN NAMA, ALAMAT, ATAU KELURAHAN
        if ($request->filled('q')) {
            $kataKunci = trim($request->q);

            $query->where(function ($q) use ($kataKunci) {
                $q->where('nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('alamat', 'like', '%' . $kataKunci . '%')
                    ->orWhere('kelurahan', 'like', '%' . $kataKunci . '%');
            });
        }

        $pauds = $query->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        $jenisTerpilih = $request->jenis;
        $kataKunci = $request->q;
        $kecamatanTerpilih = $request->kecamatan;

        return view('public.daftar', compact(
            'pauds',
            'jenisTerpilih',
            'kataKunci',
            'kecamatans',
            'kecamatanTerpilih'
        ));
    }

    // DETAIL SATU SEKOLAH
    public function detail(Paud $paud)
    {
        $kecamatanAktif = Kecamatan::aktif()
            ->whereKey($paud->kecamatan_id)
            ->exists();

        abort_unless($kecamatanAktif, 404);

        $paud->load([
            'kecamatan',
            'fotos',
            'fotoUtama',
        ]);

        $punyaLokasiValid =
            $paud->punyaKoordinat()
            && $paud->latitude >= self::LAT_MIN
            && $paud->latitude <= self::LAT_MAX
            && $paud->longitude >= self::LNG_MIN
            && $paud->longitude <= self::LNG_MAX;

        return view('public.detail', compact(
            'paud',
            'punyaLokasiValid'
        ));
    }

    // HALAMAN PETA SEBARAN
    public function peta()
    {
        $kecamatan = Kecamatan::aktif()
            ->orderBy('nama')
            ->get(['id', 'nama', 'slug', 'warna']);

        $idAktif = $kecamatan->pluck('id')->all();

        $totalPaud = Paud::whereIn('kecamatan_id', $idAktif)
            ->count();

        $pauds = Paud::query()
            ->whereIn('kecamatan_id', $idAktif)
            ->whereBetween('latitude', [
                self::LAT_MIN,
                self::LAT_MAX,
            ])
            ->whereBetween('longitude', [
                self::LNG_MIN,
                self::LNG_MAX,
            ])
            ->orderBy('nama')
            ->get([
                'id',
                'nama',
                'slug',
                'kecamatan_id',
                'alamat',
                'jenis_satuan',
                'latitude',
                'longitude',
            ])
            ->map(fn ($p) => [
                'id' => $p->id,
                'nama' => $p->nama,
                'slug' => $p->slug,
                'kecamatan_id' => $p->kecamatan_id,
                'alamat' => $p->alamat,
                'jenis' => $p->jenis_satuan,
                'lat' => $p->latitude,
                'lng' => $p->longitude,
            ])
            ->values();

        return view('public.peta', compact(
            'kecamatan',
            'pauds',
            'totalPaud'
        ));
    }
}
