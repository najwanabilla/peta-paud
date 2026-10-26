<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Paud extends Model
{
    // Pilihan jenis satuan dan status. Mau diubah/ditambah? Cukup edit di sini.
    public const JENIS_SATUAN = [
        'TK' => 'TK (Taman Kanak-kanak)',
        'KB' => 'KB (Kelompok Bermain)',
        'TPA' => 'TPA (Taman Penitipan Anak)',
        'SPS' => 'SPS (Satuan PAUD Sejenis)',
    ];

    public const STATUS = ['Negeri', 'Swasta'];

    protected $table = 'pauds';

    protected $fillable = [
        'npsn',
        'nama',
        'slug',
        'kecamatan_id',
        'kelurahan',
        'alamat',
        'kode_pos',
        'latitude',
        'longitude',
        'deskripsi',
        'jenis_satuan',
        'status',
        'kepala_sekolah',
        'telepon',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    // Alamat halaman detail memakai slug, bukan nomor id
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Membuat slug unik dari nama. Kalau sudah dipakai, ditambah -2, -3, dst.
    public static function buatSlugUnik(string $nama): string
    {
        $dasar = mb_substr(Str::slug($nama), 0, 240);
        $dasar = $dasar !== '' ? $dasar : 'paud';

        $slug = $dasar;
        $urutan = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $dasar . '-' . $urutan;
            $urutan++;
        }

        return $slug;
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoPaud::class);
    }

    public function fotoUtama(): HasOne
    {
        return $this->hasOne(FotoPaud::class)->where('is_primary', true);
    }

    // Hanya PAUD yang punya koordinat (untuk penanda di peta)
    public function scopeDenganKoordinat(Builder $query): Builder
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    public function punyaKoordinat(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}