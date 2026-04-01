<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'tanaman_id',
        'jadwal_perawatan_id',
        'nomor',
        'pesan',
        'tanggal_kirim',
        'status',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kirim' => 'datetime',
        ];
    }

    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function jadwalPerawatan(): BelongsTo
    {
        return $this->belongsTo(JadwalPerawatan::class, 'jadwal_perawatan_id');
    }
}
