<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class KecamatanRequest extends FormRequest
{
    // Aman: rute admin sudah dijaga middleware 'auth'
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nama = trim((string) $this->input('nama'));

        $this->merge([
            'nama' => $nama,
            'slug' => Str::slug($nama),
            'geojson' => filled($this->input('geojson')) ? trim($this->input('geojson')) : null,
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        // Saat ubah data, $kecamatan berisi data kecamatan. Saat tambah baru, kosong.
        $kecamatan = $this->route('kecamatan');
        $sedangUbah = $kecamatan !== null;

        return [
            'nama' => ['required', 'string', 'max:100', Rule::unique('kecamatans', 'nama')->ignore($kecamatan)],
            'slug' => ['required', 'string', 'max:120', Rule::unique('kecamatans', 'slug')->ignore($kecamatan)],
            // Warna wajib dipilih saat tambah baru, dan dilarang dikirim saat ubah
            'warna' => $sedangUbah
                ? ['prohibited']
                : ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['boolean'],
            'geojson' => [
                'nullable',
                'string',
                'max:5000000',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $this->periksaGeojson($value, $fail);
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama kecamatan wajib diisi.',
            'nama.max' => 'Nama kecamatan maksimal 100 karakter.',
            'nama.unique' => 'Nama kecamatan ini sudah ada.',
            'slug.required' => 'Nama kecamatan harus berisi huruf atau angka.',
            'slug.unique' => 'Nama ini menghasilkan alamat (slug) yang sudah dipakai kecamatan lain.',
            'warna.required' => 'Warna wajib dipilih.',
            'warna.regex' => 'Format warna tidak benar.',
            'warna.prohibited' => 'Warna tidak bisa diubah setelah kecamatan dibuat.',
            'geojson.max' => 'Data GeoJSON terlalu besar (maksimal sekitar 5 MB).',
        ];
    }

    private function periksaGeojson(?string $value, \Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        $data = json_decode($value, true);

        if (! is_array($data) || ! isset($data['type'])) {
            $fail('GeoJSON tidak valid. Pastikan isinya berformat JSON yang benar.');
            return;
        }

        $tipeBoleh = ['FeatureCollection', 'Feature', 'Polygon', 'MultiPolygon'];

        if (! in_array($data['type'], $tipeBoleh, true)) {
            $fail('Jenis GeoJSON harus Feature, FeatureCollection, Polygon, atau MultiPolygon.');
            return;
        }

        $titik = $this->titikPertama($data);

        if ($titik === null) {
            $fail('GeoJSON tidak berisi koordinat.');
            return;
        }

        [$lon, $lat] = $titik;

        // Leaflet memakai WGS84 (EPSG:4326) dengan urutan [longitude, latitude]
        if ($lon < -180 || $lon > 180 || $lat < -90 || $lat > 90) {
            $fail('Koordinat di luar rentang. Pastikan GeoJSON memakai WGS84 (EPSG:4326) dengan urutan [longitude, latitude].');
        }
    }

    private function titikPertama(mixed $data): ?array
    {
        if (! is_array($data)) {
            return null;
        }

        if (isset($data['features'][0])) {
            return $this->titikPertama($data['features'][0]);
        }

        if (isset($data['geometry'])) {
            return $this->titikPertama($data['geometry']);
        }

        if (isset($data['coordinates'])) {
            return $this->cariKoordinat($data['coordinates']);
        }

        return null;
    }

    private function cariKoordinat(mixed $koordinat): ?array
    {
        if (! is_array($koordinat) || $koordinat === []) {
            return null;
        }

        if (isset($koordinat[0], $koordinat[1]) && is_numeric($koordinat[0]) && is_numeric($koordinat[1])) {
            return [(float) $koordinat[0], (float) $koordinat[1]];
        }

        return $this->cariKoordinat($koordinat[0]);
    }
}