<?php

namespace App\Exports;

use App\Models\Paud;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaudExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // Menggunakan return type yang sesuai agar tidak FatalError
    public function query(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Relations\Relation
    {
        $kata = trim((string) $this->request->query('q'));
        $kecamatanId = $this->request->query('kecamatan');
        $koordinat = $this->request->query('koordinat');

        return Paud::query()
            ->with('kecamatan')
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
            ->orderBy('nama');
    }

    public function headings(): array
    {
        // 'Status' sudah dihapus dari sini
        return [
            'No', 
            'NPSN', 
            'Nama PAUD', 
            'Jenis Satuan', 
            'Kecamatan', 
            'Kelurahan', 
            'Alamat', 
            'Latitude', 
            'Longitude'
        ];
    }

    public function map($paud): array
    {
        static $no = 0;
        $no++;

        // Data status sudah dihapus dari sini
        return [
            $no,
            $paud->npsn,
            $paud->nama,
            $paud->jenis_satuan,
            $paud->kecamatan->nama ?? '-',
            $paud->kelurahan,
            $paud->alamat,
            $paud->latitude,
            $paud->longitude,
        ];
    }
}