<?php

namespace App\Http\Requests;

use App\Models\Paud;
use App\Services\FotoPaudService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaudRequest extends FormRequest
{
    // Aman: rute admin sudah dijaga middleware 'auth'
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Izinkan koma desimal (-1,2654) dengan mengubahnya menjadi titik
        foreach (['latitude', 'longitude'] as $kolom) {
            if (is_string($this->input($kolom))) {
                $this->merge([$kolom => str_replace(',', '.', $this->input($kolom))]);
            }
        }
    }

    public function rules(): array
    {
        $paud = $this->route('paud');

        return [
            'nama' => ['required', 'string', 'max:255'],
            'npsn' => ['nullable', 'digits:8', Rule::unique('pauds', 'npsn')->ignore($paud)],
            'kecamatan_id' => ['required', 'integer', Rule::exists('kecamatans', 'id')],
            'kelurahan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'kode_pos' => ['nullable', 'digits:5'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'jenis_satuan' => ['nullable', Rule::in(array_keys(Paud::JENIS_SATUAN))],
            'status' => ['nullable', Rule::in(Paud::STATUS)],
            'kepala_sekolah' => ['nullable', 'string', 'max:150'],
            'telepon' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\-\s()]{6,30}$/'],
            'fotos' => ['nullable', 'array'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function withValidator($validator): void
    {
        // Batasi jumlah total foto per PAUD
        $validator->after(function ($validator) {
            $paud = $this->route('paud');
            $sudahAda = $paud ? $paud->fotos()->count() : 0;
            $baru = count($this->file('fotos', []));

            if ($sudahAda + $baru > FotoPaudService::MAKS_FOTO) {
                $validator->errors()->add(
                    'fotos',
                    'Satu PAUD maksimal memiliki ' . FotoPaudService::MAKS_FOTO
                    . " foto. Saat ini sudah ada {$sudahAda} foto."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama PAUD wajib diisi.',
            'nama.max' => 'Nama PAUD maksimal 255 karakter.',
            'npsn.digits' => 'NPSN harus terdiri dari 8 angka.',
            'npsn.unique' => 'NPSN ini sudah terdaftar pada PAUD lain.',
            'kecamatan_id.required' => 'Kecamatan wajib dipilih.',
            'kecamatan_id.exists' => 'Kecamatan yang dipilih tidak valid.',
            'kelurahan.max' => 'Kelurahan maksimal 100 karakter.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            'kode_pos.digits' => 'Kode pos harus terdiri dari 5 angka.',
            'latitude.numeric' => 'Latitude harus berupa angka, contoh: -1.2654.',
            'latitude.between' => 'Latitude harus di antara -90 dan 90.',
            'latitude.required_with' => 'Latitude wajib diisi kalau longitude diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka, contoh: 116.8312.',
            'longitude.between' => 'Longitude harus di antara -180 dan 180.',
            'longitude.required_with' => 'Longitude wajib diisi kalau latitude diisi.',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter.',
            'jenis_satuan.in' => 'Jenis satuan yang dipilih tidak valid.',
            'status.in' => 'Status yang dipilih tidak valid.',
            'kepala_sekolah.max' => 'Nama kepala sekolah maksimal 150 karakter.',
            'telepon.max' => 'Nomor telepon maksimal 30 karakter.',
            'telepon.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, tanda + - ( ).',
            'fotos.array' => 'Data foto tidak valid.',
            'fotos.*.image' => 'File harus berupa gambar.',
            'fotos.*.mimes' => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'fotos.*.max' => 'Ukuran tiap foto maksimal 2 MB.',
            'fotos.*.uploaded' => 'Foto gagal diunggah. Periksa ukuran file (maksimal 2 MB).',
        ];
    }
}