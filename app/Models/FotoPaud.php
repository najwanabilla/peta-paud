<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FotoPaud extends Model
{
    protected $table = 'foto_pauds';

    protected $fillable = [
        'paud_id',
        'path_foto',
        'keterangan',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    // Alamat foto yang bisa dibuka di browser: $foto->url
    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/' . $this->path_foto));
    }

    public function paud(): BelongsTo
    {
        return $this->belongsTo(Paud::class);
    }
}