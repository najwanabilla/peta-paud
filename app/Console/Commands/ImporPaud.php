<?php

namespace App\Console\Commands;

use App\Services\ImporPaudService;
use Illuminate\Console\Command;

class ImporPaud extends Command
{
    protected $signature = 'paud:impor
        {file : Lokasi file Excel (.xlsx)}
        {--coba : Hanya mengecek, tidak menyimpan apa pun ke database}
        {--perbaiki-tanda : Perbaiki lintang yang tanda minusnya hilang}';

    protected $description = 'Impor data PAUD dari Excel (satu tab per kecamatan)';

    public function handle(ImporPaudService $impor): int
    {
        $file = $this->argument('file');
        if (! is_file($file)) {
            $this->error("File tidak ditemukan: $file");

            return self::FAILURE;
        }

        $coba = (bool) $this->option('coba');
        $this->info($coba ? 'MODE COBA: tidak ada data yang disimpan.' : 'Mengimpor data...');

        $h = $impor->impor($file, $coba, (bool) $this->option('perbaiki-tanda'));

        $this->newLine();
        $this->table(['Hasil', 'Jumlah'], [
            ['Data baru', $h['dibuat']],
            ['Data diperbarui', $h['diperbarui']],
            ['Baris dilewati', $h['dilewati']],
            ['Tanpa koordinat (perlu diperbaiki di peta)', $h['tanpa_koordinat']],
        ]);

        if ($h['peringatan']) {
            $this->newLine();
            $this->warn(count($h['peringatan']) . ' catatan:');
            foreach ($h['peringatan'] as $p) {
                $this->line(" - $p");
            }
        }

        $this->newLine();
        $this->info('Jumlah per kelurahan:');
        foreach ($h['ringkasan'] as $kec => $kels) {
            ksort($kels);
            $this->line("<comment>$kec</comment> (" . array_sum($kels) . ')');
            foreach ($kels as $kel => $n) {
                $this->line("   $kel: $n");
            }
        }

        return self::SUCCESS;
    }
}
