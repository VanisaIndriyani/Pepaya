<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Tanam Pepaya</title>
    <style>
        @page { 
            margin: 0cm; 
        }
        body { 
            font-family: "DejaVu Sans", sans-serif; 
            color: #1e293b; 
            font-size: 10px; 
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .page-wrapper {
            padding: 1.5cm;
        }
        .header-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4cm;
            background: #f0fdf4;
            z-index: -1;
            border-bottom: 2px solid #16a34a;
        }
        .header { 
            display: table; 
            width: 100%; 
            margin-bottom: 20px;
        }
        .header-left { 
            display: table-cell; 
            width: 65%; 
            vertical-align: middle; 
        }
        .header-right { 
            display: table-cell; 
            width: 35%; 
            vertical-align: middle; 
            text-align: right; 
        }
        .brand { 
            font-size: 22px; 
            font-weight: 900; 
            color: #166534; 
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }
        .sub { 
            font-size: 10px; 
            color: #475569; 
            font-weight: 600;
        }
        .meta-box {
            margin-top: 15px;
            font-size: 9px;
            color: #64748b;
        }
        .meta-item { margin-bottom: 2px; }
        .meta-label { font-weight: 700; color: #334155; }

        .kpi-container {
            display: table;
            width: 100%;
            margin: 20px 0;
            border-spacing: 10px 0;
        }
        .kpi-card {
            display: table-cell;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            width: 33.33%;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .kpi-label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 800;
            margin-bottom: 5px;
        }
        .kpi-value {
            font-size: 18px;
            font-weight: 900;
            color: #166534;
        }

        .section { margin-top: 30px; }
        .section-header {
            border-left: 4px solid #16a34a;
            padding-left: 10px;
            margin-bottom: 15px;
        }
        .section-title { 
            font-size: 12px; 
            font-weight: 800; 
            color: #064e3b; 
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        th { 
            background: #f8fafc; 
            color: #334155; 
            font-weight: 800; 
            font-size: 9px;
            text-transform: uppercase;
            padding: 10px 12px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }
        td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #f1f5f9; 
            vertical-align: middle; 
        }
        tr:last-child td { border-bottom: none; }
        
        .row-even { background: #ffffff; }
        .row-odd { background: #fcfdfd; }

        .tanaman-name { font-weight: 700; color: #0f172a; font-size: 10px; }
        .lokasi-text { font-size: 8px; color: #64748b; margin-top: 2px; }
        
        .badge { 
            display: inline-block; 
            padding: 3px 10px; 
            border-radius: 6px; 
            font-size: 8px; 
            font-weight: 800; 
            text-transform: uppercase;
        }
        .b-aktif { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .b-panen { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .b-selesai { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .b-terkirim { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .b-gagal { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        .right { text-align: right; }
        .center { text-align: center; }
        .nowrap { white-space: nowrap; }
        .muted { color: #94a3b8; }

        .footer { 
            position: fixed; 
            bottom: 0.8cm; 
            left: 1.5cm; 
            right: 1.5cm; 
            font-size: 8px; 
            color: #94a3b8; 
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header-bg"></div>
    <div class="page-wrapper">
        <div class="header">
            <div class="header-left">
                <div class="brand">TANAM PEPAYA</div>
                <div class="sub">MANAGEMENT & HARVEST INFORMATION SYSTEM</div>
                <div class="meta-box">
                    <div class="meta-item"><span class="meta-label">LAPORAN:</span> Ringkasan Operasional Perkebunan</div>
                    <div class="meta-item"><span class="meta-label">PERIODE:</span> {{ $from }} — {{ $to }}</div>
                </div>
            </div>
            <div class="header-right">
                <div class="meta-box">
                    <div class="meta-item"><span class="meta-label">DICETAK PADA:</span> {{ $printedAt }}</div>
                    <div class="meta-item"><span class="meta-label">OPERATOR:</span> {{ $printedBy }}</div>
                </div>
            </div>
        </div>

        <div class="kpi-container">
            <div class="kpi-card">
                <div class="kpi-label">Total Jadwal Tanam</div>
                <div class="kpi-value">{{ $jadwalTanam->count() }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Estimasi Masa Panen</div>
                <div class="kpi-value">{{ $estimasiPanen->count() }}</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Notifikasi Terkirim</div>
                <div class="kpi-value">{{ $riwayatNotifikasi->count() }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">
                <div class="section-title">I. Jadwal Penanaman</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25px;" class="center">#</th>
                        <th>Informasi Tanaman</th>
                        <th class="center">Pembibitan</th>
                        <th class="center">Pindah Lahan</th>
                        <th class="right">Luas (m²)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwalTanam as $i => $t)
                        <tr class="{{ $i % 2 == 0 ? 'row-even' : 'row-odd' }}">
                            <td class="center muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="tanaman-name">{{ $t->nama_tanaman }}</div>
                                <div class="lokasi-text">{{ $t->lokasi }}</div>
                            </td>
                            <td class="center nowrap">{{ $t->tanggal_tanam->format('d M Y') }}</td>
                            <td class="center nowrap">{{ $t->tanggal_pindah_lahan?->format('d M Y') ?: '—' }}</td>
                            <td class="right fw-bold">{{ number_format((float) $t->luas_lahan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="center muted py-4">Tidak ditemukan data penanaman pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <div class="section-header">
                <div class="section-title">II. Estimasi Panen</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25px;" class="center">#</th>
                        <th>Informasi Tanaman</th>
                        <th class="center">Tgl Tanam</th>
                        <th class="center">Estimasi Panen</th>
                        <th class="center" style="width: 80px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estimasiPanen as $i => $t)
                        @php
                            $today = \Carbon\Carbon::today();
                            $badge = $t->status;
                            if ($badge !== 'selesai' && $today->gte($t->estimasi_panen)) { $badge = 'panen'; }
                        @endphp
                        <tr class="{{ $i % 2 == 0 ? 'row-even' : 'row-odd' }}">
                            <td class="center muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="tanaman-name">{{ $t->nama_tanaman }}</div>
                                <div class="lokasi-text">{{ $t->lokasi }}</div>
                            </td>
                            <td class="center nowrap">{{ $t->tanggal_tanam->format('d M Y') }}</td>
                            <td class="center nowrap"><strong>{{ $t->estimasi_panen->format('d M Y') }}</strong></td>
                            <td class="center">
                                <span class="badge b-{{ $badge }}">{{ $badge }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="center muted py-4">Tidak ditemukan data estimasi panen pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section" style="page-break-before: always;">
            <div class="section-header">
                <div class="section-title">III. Riwayat Notifikasi WhatsApp</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 25px;" class="center">#</th>
                        <th>Waktu & Tanaman</th>
                        <th>Jenis Aktivitas</th>
                        <th class="center">Nomor Tujuan</th>
                        <th class="center" style="width: 70px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatNotifikasi as $i => $n)
                        @php
                            $jenis = $n->jadwalPerawatan?->jenis;
                            $jenisText = match ($jenis) {
                                'pindah_lahan' => 'Pindah Lahan',
                                'pupuk_kandang' => 'Pemupukan Kandang',
                                'pupuk_kimia' => 'Pemupukan Kimia',
                                'semprot_hama' => 'Penyemprotan Hama',
                                'semprot_rumput' => 'Semprot Rumput',
                                'timbun_bedeng' => 'Timbun Bedeng',
                                'panen' => 'Masa Panen',
                                default => 'Notifikasi Umum',
                            };
                        @endphp
                        <tr class="{{ $i % 2 == 0 ? 'row-even' : 'row-odd' }}">
                            <td class="center muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="tanaman-name">{{ $n->tanaman?->nama_tanaman ?: '—' }}</div>
                                <div class="lokasi-text">{{ $n->tanggal_kirim?->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="fw-medium">{{ $jenisText }}</td>
                            <td class="center nowrap">{{ $n->nomor }}</td>
                            <td class="center">
                                <span class="badge {{ $n->status === 'terkirim' ? 'b-terkirim' : 'b-gagal' }}">{{ $n->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="center muted py-4">Tidak ada riwayat pengiriman notifikasi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh <strong>Sistem Tanam Pepaya</strong> • Dicetak pada {{ $printedAt }} • Halaman 1 dari 1
    </div>
</body>
</html>