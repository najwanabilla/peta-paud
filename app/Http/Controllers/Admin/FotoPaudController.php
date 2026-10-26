<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FotoPaud;
use App\Models\Paud;
use App\Services\FotoPaudService;
use Illuminate\Http\RedirectResponse;

class FotoPaudController extends Controller
{
    public function __construct(private FotoPaudService $layananFoto)
    {
    }

    public function utama(Paud $paud, FotoPaud $foto): RedirectResponse
    {
        $this->layananFoto->jadikanUtama($foto);

        return back()->with('sukses', 'Foto utama berhasil diganti.');
    }

    public function destroy(Paud $paud, FotoPaud $foto): RedirectResponse
    {
        $this->layananFoto->hapus($foto);

        return back()->with('sukses', 'Foto berhasil dihapus.');
    }
}