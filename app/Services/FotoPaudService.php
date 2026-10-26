<?php

namespace App\Services;

use App\Models\FotoPaud;
use App\Models\Paud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FotoPaudService
{
    public const MAKS_FOTO = 5;

    /**
     * Simpan foto yang diunggah. Nama file dibuat acak oleh Laravel,
     * jadi nama asli dari komputer pengguna tidak pernah dipakai.
     */
    public function simpan(Paud $paud, array $files): void
    {
        $sudahAdaUtama = $paud->fotos()->where('is_primary', true)->exists();

        foreach ($files as $file) {
            $path = $file->store('foto-paud', 'public');

            $paud->fotos()->create([
                'path_foto' => $path,
                'is_primary' => ! $sudahAdaUtama,
            ]);

            // Foto pertama otomatis jadi foto utama
            $sudahAdaUtama = true;
        }
    }

    public function jadikanUtama(FotoPaud $foto): void
    {
        DB::transaction(function () use ($foto) {
            FotoPaud::where('paud_id', $foto->paud_id)->update(['is_primary' => false]);
            $foto->update(['is_primary' => true]);
        });
    }

    public function hapus(FotoPaud $foto): void
    {
        $paud = $foto->paud;
        $adalahUtama = $foto->is_primary;
        $path = $foto->path_foto;

        $foto->delete();
        Storage::disk('public')->delete($path);

        // Kalau yang dihapus foto utama, foto tertua yang tersisa jadi foto utama
        if ($adalahUtama) {
            $paud->fotos()->orderBy('id')->first()?->update(['is_primary' => true]);
        }
    }

    // Hapus PAUD sekaligus file-file fotonya dari folder
    public function hapusPaud(Paud $paud): void
    {
        $daftarPath = $paud->fotos()->pluck('path_foto')->all();

        $paud->delete();

        Storage::disk('public')->delete($daftarPath);
    }
}