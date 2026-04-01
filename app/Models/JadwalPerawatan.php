<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalPerawatan extends Model
{
    protected $table = 'jadwal_perawatan';

    protected $fillable = [
        'tanaman_id',
        'jenis',
        'tanggal',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'sent_at' => 'datetime',
        ];
    }

    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'jadwal_perawatan_id');
    }
}
