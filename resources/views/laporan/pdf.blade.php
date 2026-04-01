<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Tanam Pepaya</title>
    <style>
        @page { margin: 22px 22px 28px; }
        body { font-family: "DejaVu Sans", sans-serif; color: #0f172a; font-size: 11px; }
        .header { display: table; width: 100%; }
        .header-left { display: table-cell; width: 60%; vertical-align: top; }
        .header-right { display: table-cell; width: 40%; vertical-align: top; text-align: right; }
        .brand { font-size: 18px; font-weight: 800; color: #166534; line-height: 1.1; }
        .sub { font-size: 11px; color: #475569; margin-top: 2px; }
        .meta { font-size: 10px; color: #475569; margin-top: 2px; }
        .divider { height: 2px; background: #16a34a; margin: 12px 0 14px; }
        .section { margin: 14px 0 10px; }
        .section-title { font-size: 12px; font-weight: 800; color: #14532d; margin: 0 0 8px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; }
        .b-aktif { background: #dcfce7; color: #166534; }
        .b-panen { background: #fef9c3; color: #854d0e; }
        .b-selesai { background: #dbeafe; color: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { text-align: left; background: #f0fdf4; color: #14532d; font-weight: 800; border-bottom: 1px solid #bbf7d0; }
        .muted { color: #64748b; }
        .right { text-align: right; }
        .nowrap { white-space: nowrap; }
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; font-size: 9px; color: #64748b; text-align: center; }
        .card { border: 1px solid #dcfce7; background: #f0fdf4; border-radius: 10px; padding: 10px; margin-top: 10px; }
        .kpi { display: table; width: 100%; }
        .kpi-item { display: table-cell; width: 33.33%; padding: 6px 8px; }
        .kpi-label { font-size: 9px; color: #64748b; }
        .kpi-value { font-size: 14px; font-weight: 900; color: #14532d; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="brand">Tanam Pepaya</div>
            <div class="sub">Laporan Jadwal Tanam, Estimasi Panen, dan Riwayat Notifikasi</div>
            <div class="meta">Periode: <span class="nowrap">{{ $from }}</span> s/d <span class="nowrap">{{ $to }}</span></div>
        </div>
        <div class="header-right">
            <div class="meta">Dicetak: {{ $printedAt }}</div>
            <div class="meta">Oleh: {{ $printedBy }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="card">
        <div class="kpi">
            <div class="kpi-item">
                <div class="kpi-label">Jumlah Jadwal Tanam</div>
                <div class="kpi-value">{{ $jadwalTanam->count() }}</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-label">Estimasi Panen</div>
                <div class="kpi-value">{{ $estimasiPanen->count() }}</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-label">Riwayat Notifikasi</div>
                <div class="kpi-value">{{ $riwayatNotifikasi->count() }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Jadwal Tanam</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 32px;">No</th>
                    <th>Tanaman</th>
                    <th>Lokasi</th>
                    <th class="nowrap">Pembibitan</th>
                    <th class="nowrap">Pindah Lahan</th>
                    <th class="right nowrap">Luas (m²)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalTanam as $i => $t)
                    <tr>
                        <td class="muted">{{ $i + 1 }}</td>
                        <td><strong>{{ $t->nama_tanaman }}</strong></td>
                        <td>{{ $t->lokasi }}</td>
                        <td class="nowrap">{{ $t->tanggal_tanam->toDateString() }}</td>
                        <td class="nowrap">{{ $t->tanggal_pindah_lahan?->toDateString() ?: '-' }}</td>
                        <td class="right nowrap">{{ number_format((float) $t->luas_lahan, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Estimasi Panen</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 32px;">No</th>
                    <th>Tanaman</th>
                    <th>Lokasi</th>
                    <th class="nowrap">Pembibitan</th>
                    <th class="nowrap">Pindah Lahan</th>
                    <th class="nowrap">Estimasi Panen</th>
                    <th style="width: 90px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($estimasiPanen as $i => $t)
                    @php
                        $today = \Carbon\Carbon::today();
                        $badge = $t->status;
                        if ($badge !== 'selesai' && $today->gte($t->estimasi_panen)) { $badge = 'panen'; }
                    @endphp
                    <tr>
                        <td class="muted">{{ $i + 1 }}</td>
                        <td><strong>{{ $t->nama_tanaman }}</strong></td>
                        <td>{{ $t->lokasi }}</td>
                        <td class="nowrap">{{ $t->tanggal_tanam->toDateString() }}</td>
                        <td class="nowrap">{{ $t->tanggal_pindah_lahan?->toDateString() ?: '-' }}</td>
                        <td class="nowrap">{{ $t->estimasi_panen->toDateString() }}</td>
                        <td>
                            <span class="badge b-{{ $badge }}">{{ ucfirst($badge) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Riwayat Notifikasi</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 32px;">No</th>
                    <th class="nowrap">Tanggal</th>
                    <th>Tanaman</th>
                    <th style="width: 120px;">Jenis</th>
                    <th style="width: 120px;">Nomor</th>
                    <th style="width: 70px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatNotifikasi as $i => $n)
                    @php
                        $jenis = $n->jadwalPerawatan?->jenis;
                        $jenisText = match ($jenis) {
                            'pindah_lahan' => 'Pindah Lahan',
                            'pupuk_kandang' => 'Pemupukan - Pupuk Kandang',
                            'pupuk_kimia' => 'Pemupukan - Pupuk Kimia',
                            'semprot_hama' => 'Penyemprotan Hama',
                            'semprot_rumput' => 'Semprot Rumput Bedeng',
                            'timbun_bedeng' => 'Timbun Bedeng',
                            'panen' => 'Panen',
                            default => '-',
                        };
                    @endphp
                    <tr>
                        <td class="muted">{{ $i + 1 }}</td>
                        <td class="nowrap">{{ $n->tanggal_kirim?->format('Y-m-d H:i') }}</td>
                        <td>
                            <strong>{{ $n->tanaman?->nama_tanaman }}</strong>
                            <div class="muted">{{ $n->tanaman?->lokasi }}</div>
                        </td>
                        <td>{{ $jenisText }}</td>
                        <td class="nowrap">{{ $n->nomor }}</td>
                        <td>
                            <span class="badge {{ $n->status === 'terkirim' ? 'b-aktif' : 'b-panen' }}">{{ ucfirst($n->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Tanam Pepaya • Laporan Sistem Informasi Tanam & Panen • Periode {{ $from }} s/d {{ $to }}
    </div>
</body>
</html>
