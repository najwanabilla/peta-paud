<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaudRequest;
use App\Models\Kecamatan;
use App\Models\Paud;
use App\Services\FotoPaudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaudController extends Controller
{
    public function __construct(private FotoPaudService $layananFoto)
    {
    }

    public function index(Request $request): View
    {
        $kata = trim((string) $request->query('q'));
        $kecamatanId = $request->query('kecamatan');
        $koordinat = $request->query('koordinat');

        $daftar = Paud::with(['kecamatan:id,nama,warna', 'fotoUtama'])
            ->when($kata !== '', function ($query) use ($kata) {
                $query->where(function ($sub) use ($kata) {
                    $sub->where('nama', 'like', "%{$kata}%")
                        ->orWhere('npsn', 'like', "%{$kata}%")
                        ->orWhere('alamat', 'like', "%{$kata}%")
                        ->orWhere('kelurahan', 'like', "%{$kata}%");
                });
            })
            ->when(filled($kecamatanId), fn ($query) => $query->where('kecamatan_id', $kecamatanId))
            ->when($koordinat === 'ada', fn ($query) => $query->denganKoordinat())
            ->when($koordinat === 'belum', function ($query) {
                $query->where(function ($sub) {
                    $sub->whereNull('latitude')->orWhereNull('longitude');
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('admin.paud.index', [
            'daftar' => $daftar,
            'kecamatans' => Kecamatan::orderBy('nama')->get(['id', 'nama']),
            'adaFilter' => $kata !== '' || filled($kecamatanId) || filled($koordinat),
        ]);
    }

    public function create(): View
    {
        return view('admin.paud.form', [
            'paud' => new Paud(),
            'kecamatans' => Kecamatan::orderBy('nama')->get(['id', 'nama']),
            'jenisSatuan' => Paud::JENIS_SATUAN,
            'statusList' => Paud::STATUS,
            'maksFoto' => FotoPaudService::MAKS_FOTO,
        ]);
    }

    public function store(PaudRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('fotos');
        $data['slug'] = Paud::buatSlugUnik($data['nama']);

        $paud = Paud::create($data);

        if ($request->hasFile('fotos')) {
            $this->layananFoto->simpan($paud, $request->file('fotos'));
        }

        return redirect()
            ->route('admin.paud.show', $paud)
            ->with('sukses', "PAUD {$paud->nama} berhasil ditambahkan.");
    }

    public function show(Paud $paud): View
    {
        $paud->load([
            'kecamatan',
            'fotos' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('id'),
        ]);

        return view('admin.paud.show', ['paud' => $paud]);
    }

    public function edit(Paud $paud): View
    {
        $paud->load(['fotos' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('id')]);

        return view('admin.paud.form', [
            'paud' => $paud,
            'kecamatans' => Kecamatan::orderBy('nama')->get(['id', 'nama']),
            'jenisSatuan' => Paud::JENIS_SATUAN,
            'statusList' => Paud::STATUS,
            'maksFoto' => FotoPaudService::MAKS_FOTO,
        ]);
    }

    public function update(PaudRequest $request, Paud $paud): RedirectResponse
    {
        // Slug sengaja tidak diubah supaya alamat halaman PAUD tetap stabil
        $paud->update($request->safe()->except('fotos'));

        if ($request->hasFile('fotos')) {
            $this->layananFoto->simpan($paud, $request->file('fotos'));
        }

        return redirect()
            ->route('admin.paud.show', $paud)
            ->with('sukses', "Data PAUD {$paud->nama} berhasil diperbarui.");
    }

    public function destroy(Paud $paud): RedirectResponse
    {
        $nama = $paud->nama;

        $this->layananFoto->hapusPaud($paud);

        return redirect()
            ->route('admin.paud.index')
            ->with('sukses', "PAUD {$nama} berhasil dihapus.");
    }
}