<?php

namespace App\Services;

use App\Models\Kecamatan;
use App\Models\Paud;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Impor data PAUD dari Excel (banyak tab, satu tab per kecamatan).
 * Aturan: data yang meragukan TIDAK ditebak. Koordinat yang salah dikosongkan
 * dan dilaporkan, supaya admin memperbaikinya lewat peta di form PAUD.
 */
class ImporPaudService
{
    // Kotak batas kasar Kota Balikpapan
    public const LAT = [-1.40, -0.95];
    public const LNG = [116.60, 117.10];

    // Nama kelurahan baku. Kunci pencocokan: huruf kecil tanpa spasi.
    private const KELURAHAN = [
        'Baru Ilir', 'Baru Tengah', 'Baru Ulu', 'Kariangau', 'Marga Sari', 'Margo Mulyo',
        'Damai', 'Klandasan Ilir', 'Klandasan Ulu', 'Prapatan', 'Telaga Sari',
        'Damai Bahagia', 'Damai Baru', 'Gunung Bahagia', 'Sepinggan', 'Sepinggan Baru',
        'Sepinggan Raya', 'Sungai Nangka',
        'Gunung Sari Ilir', 'Gunung Sari Ulu', 'Karang Jati', 'Karang Rejo', 'Mekar Sari', 'Sumber Rejo',
        'Lamaru', 'Manggar', 'Manggar Baru', 'Teritip',
        'Batu Ampar', 'Graha Indah', 'Gunung Samarinda', 'Gunung Samarinda Baru', 'Karang Joang', 'Muara Rapak',
    ];

    // Salah ketik yang umum di data sumber
    private const ALIAS = [
        'perapatan' => 'Prapatan',
        'lemaru' => 'Lamaru',
    ];

    // Nama kolom Excel (huruf kecil) -> kunci internal
    private const KOLOM = [
        'nama satuan pendidikan' => 'nama',
        'npsn' => 'npsn',
        'bentuk pendidikan' => 'jenis',
        'alamat' => 'alamat',
        'desa' => 'kelurahan',
        'kecamatan' => 'kecamatan',
        'kode pos' => 'kodepos',
        'lintang' => 'lat',
        'bujur' => 'lng',
    ];

    public function impor(string $file, bool $cobaSaja = false, bool $perbaikiTanda = false): array
    {
        $hasil = [
            'dibuat' => 0, 'diperbarui' => 0, 'dilewati' => 0, 'tanpa_koordinat' => 0,
            'peringatan' => [], 'ringkasan' => [],
        ];

        $reader = IOFactory::createReaderForFile($file);
        $reader->setReadDataOnly(true);
        $buku = $reader->load($file);

        $kecamatan = [];
        foreach (Kecamatan::all() as $k) {
            $kecamatan[self::kunci($k->nama)] = $k;
        }

        DB::beginTransaction();
        try {
            foreach ($buku->getAllSheets() as $sheet) {
                $baris = $sheet->toArray(null, true, false, false);
                $judul = $sheet->getTitle();
                $peta = $this->petakanKolom(array_shift($baris) ?? []);

                if (! isset($peta['nama'], $peta['kecamatan'])) {
                    $hasil['peringatan'][] = "Tab \"$judul\": kolom Nama/Kecamatan tidak ditemukan, tab dilewati.";
                    continue;
                }

                foreach ($baris as $i => $r) {
                    $no = $i + 2; // nomor baris di Excel
                    $d = [];
                    foreach ($peta as $kunci => $idx) {
                        $d[$kunci] = $r[$idx] ?? null;
                    }

                    $nama = self::teks($d['nama'] ?? null);
                    if ($nama === null) {
                        continue; // baris kosong
                    }
                    $lokasi = "$judul baris $no ($nama)";

                    $kec = $kecamatan[self::kunci((string) ($d['kecamatan'] ?? ''))] ?? null;
                    if (! $kec) {
                        $hasil['dilewati']++;
                        $hasil['peringatan'][] = "$lokasi: kecamatan \"{$d['kecamatan']}\" tidak ada di database, baris dilewati.";
                        continue;
                    }

                    $npsn = self::npsn($d['npsn'] ?? null);
                    if ($npsn === false) {
                        $hasil['peringatan'][] = "$lokasi: NPSN \"{$d['npsn']}\" bukan 8 angka, NPSN dikosongkan.";
                        $npsn = null;
                    }

                    $jenis = strtoupper((string) self::teks($d['jenis'] ?? null));
                    if (! isset(Paud::JENIS_SATUAN[$jenis])) {
                        if ($jenis !== '') {
                            $hasil['peringatan'][] = "$lokasi: jenis \"$jenis\" tidak dikenal, jenis dikosongkan.";
                        }
                        $jenis = null;
                    }

                    $kodePos = self::kodePos($d['kodepos'] ?? null);
                    if ($kodePos !== null && ! preg_match('/^761\d\d$/', $kodePos)) {
                        $hasil['peringatan'][] = "$lokasi: kode pos $kodePos tidak lazim untuk Balikpapan (diimpor apa adanya, mohon dicek).";
                    }

                    [$lat, $lng, $alasan] = self::koordinat($d['lat'] ?? null, $d['lng'] ?? null, $perbaikiTanda);
                    if ($alasan) {
                        $hasil['peringatan'][] = "$lokasi: $alasan";
                    }
                    if ($lat === null) {
                        $hasil['tanpa_koordinat']++;
                    }

                    $kelurahan = self::kelurahan($d['kelurahan'] ?? null);

                    $data = [
                        'nama' => $nama,
                        'kecamatan_id' => $kec->id,
                        'kelurahan' => $kelurahan,
                        'alamat' => self::teks($d['alamat'] ?? null),
                        'kode_pos' => $kodePos,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'jenis_satuan' => $jenis,
                    ];

                    $paud = $npsn !== null
                        ? Paud::firstOrNew(['npsn' => $npsn])
                        : Paud::firstOrNew(['nama' => $nama, 'kelurahan' => $kelurahan]);

                    $baru = ! $paud->exists;
                    if ($baru) {
                        $paud->slug = Paud::buatSlugUnik($nama . ($npsn ? '-' . $npsn : ''));
                    }
                    $paud->fill($data + ['npsn' => $npsn])->save();

                    $baru ? $hasil['dibuat']++ : $hasil['diperbarui']++;
                    $hasil['ringkasan'][$kec->nama][$kelurahan ?? '(kosong)'] =
                        ($hasil['ringkasan'][$kec->nama][$kelurahan ?? '(kosong)'] ?? 0) + 1;
                }
            }

            $cobaSaja ? DB::rollBack() : DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $hasil;
    }

    private function petakanKolom(array $judul): array
    {
        $peta = [];
        foreach ($judul as $idx => $nama) {
            $k = mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $nama)));
            if (isset(self::KOLOM[$k])) {
                $peta[self::KOLOM[$k]] = $idx;
            }
        }

        return $peta;
    }

    // --- Pembersih data (sengaja static dan tanpa database, supaya mudah diuji) ---

    public static function kunci(string $s): string
    {
        return mb_strtolower(preg_replace('/\s+/u', '', $s));
    }

    public static function teks($v): ?string
    {
        if ($v === null) {
            return null;
        }
        $s = trim(preg_replace('/\s+/u', ' ', (string) $v));

        return $s === '' ? null : $s;
    }

    /** @return string|null|false  null = kosong, false = tidak valid */
    public static function npsn($v)
    {
        if ($v === null || trim((string) $v) === '') {
            return null;
        }
        $s = is_numeric($v) ? sprintf('%.0f', (float) $v) : preg_replace('/\D/', '', (string) $v);

        return preg_match('/^\d{8}$/', $s) ? $s : false;
    }

    public static function kodePos($v): ?string
    {
        if ($v === null || trim((string) $v) === '') {
            return null;
        }
        $s = is_numeric($v) ? sprintf('%.0f', (float) $v) : preg_replace('/\D/', '', (string) $v);

        return preg_match('/^\d{5}$/', $s) ? $s : null;
    }

    public static function kelurahan($v): ?string
    {
        $s = self::teks($v);
        if ($s === null) {
            return null;
        }
        $k = self::kunci($s);
        if (isset(self::ALIAS[$k])) {
            return self::ALIAS[$k];
        }
        foreach (self::KELURAHAN as $baku) {
            if (self::kunci($baku) === $k) {
                return $baku;
            }
        }

        return mb_convert_case($s, MB_CASE_TITLE, 'UTF-8');
    }

    /** Ubah teks/angka koordinat jadi float. null jika tidak bisa dibaca dengan pasti. */
    public static function angka($v): ?float
    {
        if ($v === null) {
            return null;
        }
        if (is_int($v) || is_float($v)) {
            return (float) $v;
        }
        $s = preg_replace('/\s+/u', '', (string) $v);
        $s = rtrim($s, ',.');
        if ($s === '' || ! preg_match('/^[+-]?[\d.,]+$/', $s)) {
            return null;
        }
        if (str_contains($s, ',') && str_contains($s, '.')) {
            return null; // ambigu, mis. "116.8309626,698"
        }
        if (substr_count($s, ',') === 1) {
            $s = str_replace(',', '.', $s);
        }
        if (substr_count($s, '.') > 1 || substr_count($s, ',') > 1) {
            return null;
        }

        return is_numeric($s) ? (float) $s : null;
    }

    /** Perbaiki "116.8309626,698" jadi 116.8309626 (koma nyasar di ekor). */
    private static function angkaKomaNyasar($v): ?float
    {
        if ($v === null) {
            return null;
        }
        $s = preg_replace('/\s+/u', '', (string) $v);
        if (! preg_match('/^[+-]?[\d.]+\.[\d]+,/', $s)) {
            return null;
        }
        $s = substr($s, 0, (int) strrpos($s, ','));

        return is_numeric($s) ? (float) $s : null;
    }

    /** Kalau nilai di luar batas, coba bagi 10 bertahap sampai masuk. */
    private static function bagiKeBatas(float $x, array $batas): ?float
    {
        if ($x >= $batas[0] && $x <= $batas[1]) {
            return $x;
        }
        $try = $x;
        for ($i = 1; $i <= 8; $i++) {
            $try /= 10;
            if ($try >= $batas[0] && $try <= $batas[1]) {
                return $try;
            }
        }

        return null;
    }

    /** @return array{0: ?float, 1: ?float, 2: ?string}  lat, lng, alasan */
    public static function koordinat($lat, $lng, bool $perbaikiTanda = false): array
    {
        $a = self::angka($lat);
        $b = self::angka($lng);
        $catatan = [];

        // 1) Perbaiki koma nyasar di ekor, mis. "116.8309626,698" -> 116.8309626
        if ($a === null) {
            $fix = self::angkaKomaNyasar($lat);
            if ($fix !== null) {
                $catatan[] = "lintang: koma nyasar diperbaiki (\"$lat\" menjadi $fix)";
                $a = $fix;
            }
        }
        if ($b === null) {
            $fix = self::angkaKomaNyasar($lng);
            if ($fix !== null) {
                $catatan[] = "bujur: koma nyasar diperbaiki (\"$lng\" menjadi $fix)";
                $b = $fix;
            }
        }

        // 2) Perbaiki titik desimal yang hilang, mis. 11685422 -> 116.85422
        if ($a !== null) {
            $fix = self::bagiKeBatas($a, self::LAT);
            if ($fix !== null && $fix !== $a) {
                $catatan[] = "lintang: titik desimal dipulihkan ($a menjadi $fix)";
                $a = $fix;
            }
        }
        if ($b !== null) {
            $fix = self::bagiKeBatas($b, self::LNG);
            if ($fix !== null && $fix !== $b) {
                $catatan[] = "bujur: titik desimal dipulihkan ($b menjadi $fix)";
                $b = $fix;
            }
        }

        if ($a === null && $b === null && self::teks($lat) === null && self::teks($lng) === null) {
            return [null, null, 'koordinat kosong.'];
        }
        if ($a === null || $b === null) {
            return [null, null, "koordinat tidak terbaca (lintang: \"$lat\", bujur: \"$lng\"), dikosongkan."];
        }

        $dalam = fn ($x, $y) => $x >= self::LAT[0] && $x <= self::LAT[1] && $y >= self::LNG[0] && $y <= self::LNG[1];

        if ($dalam($a, $b)) {
            return [$a, $b, $catatan ? implode('; ', $catatan) . ', mohon dicek.' : null];
        }
        if ($perbaikiTanda && $dalam(-$a, $b)) {
            $note = "tanda minus lintang diperbaiki ($a menjadi " . -$a . ')';

            return [-$a, $b, $note . ', mohon dicek.'];
        }

        return [null, null, "koordinat di luar Balikpapan (lintang $a, bujur $b), dikosongkan."
            . ($catatan ? ' Catatan: ' . implode('; ', $catatan) . '.' : '')];
    }
}