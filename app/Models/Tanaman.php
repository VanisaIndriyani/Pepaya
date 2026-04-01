<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanaman extends Model
{
    protected $table = 'tanaman';

    protected $fillable = [
        'user_id',
        'nama_tanaman',
        'tanggal_tanam',
        'tanggal_pindah_lahan',
        'estimasi_panen',
        'panen_mulai',
        'panen_sampai',
        'luas_lahan',
        'lokasi',
        'keterangan',
        'status',
        'panen_mode',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tanam' => 'date',
            'tanggal_pindah_lahan' => 'date',
            'estimasi_panen' => 'date',
            'panen_mulai' => 'date',
            'panen_sampai' => 'date',
            'luas_lahan' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalPerawatan(): HasMany
    {
        return $this->hasMany(JadwalPerawatan::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }
}
