<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            ['nama' => 'Balikpapan Selatan', 'warna' => '#FACC15'],
            ['nama' => 'Balikpapan Kota', 'warna' => '#F87171'],
            ['nama' => 'Balikpapan Tengah', 'warna' => '#4ADE80'],
            ['nama' => 'Balikpapan Barat', 'warna' => '#60A5FA'],
            ['nama' => 'Balikpapan Utara', 'warna' => '#C084FC'],
            ['nama' => 'Balikpapan Timur', 'warna' => '#FB923C'],
        ];

        foreach ($daftar as $item) {
            // firstOrCreate: kalau sudah ada, tidak menimpa warna yang diubah admin
            Kecamatan::firstOrCreate(
                ['slug' => Str::slug($item['nama'])],
                ['nama' => $item['nama'], 'warna' => $item['warna'], 'is_active' => true]
            );
        }
    }
}