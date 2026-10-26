<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KecamatanRequest;
use App\Models\Kecamatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KecamatanController extends Controller
{
    public function index(): View
    {
        $daftar = Kecamatan::withCount('pauds')->orderBy('nama')->get();

        return view('admin.kecamatan.index', ['daftar' => $daftar]);
    }

    public function create(): View
    {
        $kecamatan = new Kecamatan(['warna' => '#38BDF8', 'is_active' => true]);

        return view('admin.kecamatan.form', ['kecamatan' => $kecamatan]);
    }

    public function store(KecamatanRequest $request): RedirectResponse
    {
        $kecamatan = Kecamatan::create($request->validated());

        return redirect()
            ->route('admin.kecamatan.index')
            ->with('sukses', "Kecamatan {$kecamatan->nama} berhasil ditambahkan.");
    }

    public function edit(Kecamatan $kecamatan): View
    {
        return view('admin.kecamatan.form', ['kecamatan' => $kecamatan]);
    }

    public function update(KecamatanRequest $request, Kecamatan $kecamatan): RedirectResponse
    {
        $kecamatan->update($request->validated());

        return redirect()
            ->route('admin.kecamatan.index')
            ->with('sukses', "Kecamatan {$kecamatan->nama} berhasil diperbarui.");
    }

    public function destroy(Kecamatan $kecamatan): RedirectResponse
    {
        // Kecamatan yang masih punya PAUD tidak boleh dihapus
        if ($kecamatan->pauds()->exists()) {
            return back()->with(
                'galat',
                "Kecamatan {$kecamatan->nama} tidak bisa dihapus karena masih memiliki data PAUD."
            );
        }

        $nama = $kecamatan->nama;
        $kecamatan->delete();

        return redirect()
            ->route('admin.kecamatan.index')
            ->with('sukses', "Kecamatan {$nama} berhasil dihapus.");
    }
}