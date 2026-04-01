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
            line-height: 1.4;
            background: #ffffff;
        }
        .header-section {
            background: #f0fdf4;
            padding: 30px 40px;
            border-bottom: 3px solid #16a34a;
        }
        .header-table { 
            width: 100%; 
            border-collapse: collapse;
            border: none;
        }
        .header-table td {
            padding: 0;
            border: none;
            vertical-align: top;
        }
        .brand { 
            font-size: 24px; 
            font-weight: bold; 
            color: #166534; 
            margin: 0;
            padding: 0;
            line-height: 1;
        }
        .sub-brand { 
            font-size: 10px; 
            color: #166534; 
            font-weight: bold;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }
        .meta-container {
            margin-top: 20px;
        }
        .meta-text {
            font-size: 9px;
            color: #475569;
            margin-bottom: 3px;
        }
        .meta-label { font-weight: bold; color: #334155; }
        
        .page-content {
            padding: 30px 40px;
        }

        .kpi-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: separate;
            border-spacing: 15px 0;
            margin-left: -15px;
            margin-right: -15px;
        }
        .kpi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            width: 33.33%;
        }
        .kpi-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .kpi-value {
            font-size: 20px;
            font-weight: bold;
            color: #166534;
            margin: 0;
        }

        .section-title-box {
            border-left: 4px solid #16a34a;
            padding: 2px 0 2px 12px;
            margin-bottom: 15px;
            margin-top: 25px;
        }
        .section-title { 
            font-size: 11px; 
            font-weight: bold; 
            color: #064e3b; 
            text-transform: uppercase;
        }

        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .data-table th { 
            background: #f8fafc; 
            color: #475569; 
            font-weight: bold; 
            font-size: 9px;
            padding: 10px 12px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }
        .data-table td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 9px;
            color: #334155;
        }
        .data-table tr:nth-child(even) { background: #fcfdfd; }
        
        .tanaman-name { font-weight: bold; color: #0f172a; margin-bottom: 2px; }
        .lokasi-sub { font-size: 8px; color: #64748b; }
        
        .badge { 
            padding: 3px 8px; 
            border-radius: 5px; 
            font-size: 8px; 
            font-weight: bold;
            display: inline-block;
        }
        .b-aktif { background: #dcfce7; color: #166534; }
        .b-panen { background: #fef9c3; color: #854d0e; }
        .b-selesai { background: #f1f5f9; color: #475569; }
        .b-terkirim { background: #dcfce7; color: #166534; }
        .b-gagal { background: #fee2e2; color: #b91c1c; }

        .footer { 
            position: fixed; 
            bottom: 30px; 
            left: 40px; 
            right: 40px; 
            font-size: 8px; 
            color: #94a3b8; 
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header-section">
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand">TANAM PEPAYA</div>
                    <div class="sub-brand">MANAGEMENT & HARVEST INFORMATION SYSTEM</div>
                    
                    <div class="meta-container">
                        <div class="meta-text"><span class="meta-label">LAPORAN:</span> Ringkasan Operasional Perkebunan</div>
                        <div class="meta-text"><span class="meta-label">PERIODE:</span> {{ $from }} — {{ $to }}</div>
                    </div>
                </td>
                <td class="text-right">
                    <div class="meta-container">
                        <div class="meta-text"><span class="meta-label">DICETAK PADA:</span> {{ $printedAt }}</div>
                        <div class="meta-text"><span class="meta-label">OPERATOR:</span> {{ $printedBy }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-content">
        <table class="kpi-table">
            <tr>
                <td class="kpi-card">
                    <div class="kpi-label">TOTAL JADWAL TANAM</div>
                    <div class="kpi-value">{{ $jadwalTanam->count() }}</div>
                </td>
                <td class="kpi-card">
                    <div class="kpi-label">ESTIMASI MASA PANEN</div>
                    <div class="kpi-value">{{ $estimasiPanen->count() }}</div>
                </td>
                <td class="kpi-card">
                    <div class="kpi-label">NOTIFIKASI TERKIRIM</div>
                    <div class="kpi-value">{{ $riwayatNotifikasi->count() }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title-box">
            <div class="section-title">I. JADWAL PENANAMAN</div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20px;" class="text-center">#</th>
                    <th>Informasi Tanaman</th>
                    <th class="text-center">Pembibitan</th>
                    <th class="text-center">Pindah Lahan</th>
                    <th class="text-right">Luas (m²)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalTanam as $i => $t)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <div class="tanaman-name">{{ $t->nama_tanaman }}</div>
                            <div class="lokasi-sub">{{ $t->lokasi }}</div>
                        </td>
                        <td class="text-center">{{ $t->tanggal_tanam->format('d M Y') }}</td>
                        <td class="text-center">{{ $t->tanggal_pindah_lahan?->format('d M Y') ?: '—' }}</td>
                        <td class="text-right fw-bold">{{ number_format((float) $t->luas_lahan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Tidak ada data penanaman.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title-box">
            <div class="section-title">II. ESTIMASI PANEN</div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20px;" class="text-center">#</th>
                    <th>Informasi Tanaman</th>
                    <th class="text-center">Tgl Tanam</th>
                    <th class="text-center">Estimasi Panen</th>
                    <th class="text-center" style="width: 80px;">Status</th>
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
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <div class="tanaman-name">{{ $t->nama_tanaman }}</div>
                            <div class="lokasi-sub">{{ $t->lokasi }}</div>
                        </td>
                        <td class="text-center">{{ $t->tanggal_tanam->format('d M Y') }}</td>
                        <td class="text-center fw-bold">{{ $t->estimasi_panen->format('d M Y') }}</td>
                        <td class="text-center">
                            <span class="badge b-{{ $badge }}">{{ strtoupper($badge) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Tidak ada data estimasi panen.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="page-break-before: always;"></div>
        
        <div class="section-title-box" style="margin-top: 0;">
            <div class="section-title">III. RIWAYAT NOTIFIKASI WHATSAPP</div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20px;" class="text-center">#</th>
                    <th>Waktu & Tanaman</th>
                    <th>Jenis Aktivitas</th>
                    <th class="text-center">Nomor Tujuan</th>
                    <th class="text-center" style="width: 70px;">Status</th>
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
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <div class="tanaman-name">{{ $n->tanaman?->nama_tanaman ?: '—' }}</div>
                            <div class="lokasi-sub">{{ $n->tanggal_kirim?->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="fw-bold">{{ $jenisText }}</td>
                        <td class="text-center">{{ $n->nomor }}</td>
                        <td class="text-center">
                            <span class="badge {{ $n->status === 'terkirim' ? 'b-terkirim' : 'b-gagal' }}">{{ strtoupper($n->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">Tidak ada riwayat notifikasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dokumen otomatis oleh <strong>Sistem Tanam Pepaya</strong> • Dicetak: {{ $printedAt }} • Halaman 1 dari 1
    </div>
</body>
</html>