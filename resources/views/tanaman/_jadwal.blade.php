<div class="mb-2">
    <div class="fw-semibold">{{ $tanaman->nama_tanaman }} <span class="text-muted">({{ $tanaman->lokasi }})</span></div>
    <div class="small text-muted">
        Pembibitan: {{ $tanaman->tanggal_tanam->toDateString() }}
        @if($tanaman->tanggal_pindah_lahan)
            | Pindah Lahan: {{ $tanaman->tanggal_pindah_lahan->toDateString() }}
        @endif
        | Estimasi Panen: {{ $tanaman->estimasi_panen->toDateString() }}
        @if($tanaman->panen_sampai)
            | Panen s/d: {{ $tanaman->panen_sampai->toDateString() }}
        @endif
    </div>
</div>
<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Terkirim</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tanaman->jadwalPerawatan as $j)
                @php
                    $jenisText = match ($j->jenis) {
                        'pindah_lahan' => 'Pindah Lahan',
                        'pupuk_kandang' => 'Pemupukan - Pupuk Kandang',
                        'pupuk_kimia' => 'Pemupukan - Pupuk Kimia',
                        'semprot_hama' => 'Penyemprotan Hama',
                        'semprot_rumput' => 'Semprot Rumput Bedeng',
                        'timbun_bedeng' => 'Timbun Bedeng',
                        'panen' => 'Panen',
                        default => ucfirst(str_replace('_', ' ', $j->jenis)),
                    };
                @endphp
                <tr>
                    <td>{{ $j->tanggal->toDateString() }}</td>
                    <td>{{ $jenisText }}</td>
                    <td>
                        <span class="badge {{ $j->status === 'terkirim' ? 'text-bg-success' : ($j->status === 'gagal' ? 'text-bg-danger' : 'text-bg-secondary') }}">
                            {{ ucfirst($j->status) }}
                        </span>
                    </td>
                    <td>{{ $j->sent_at ? $j->sent_at->format('Y-m-d H:i') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted text-center py-3">Belum ada jadwal.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
