@extends('layouts.app')

@section('title', 'Laporan | Tanam Pepaya')
@section('page_title', 'Laporan')

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
            <i class="bi bi-clipboard-data fs-4"></i>
        </div>
        <div>
            <div class="fw-semibold">Laporan</div>
            <div class="text-muted small">Ringkasan jadwal tanam, estimasi panen, dan notifikasi.</div>
        </div>
    </div>
    <a class="btn btn-outline-success" target="_blank" href="{{ route('laporan.pdf', ['from' => $from, 'to' => $to]) }}">
        <i class="bi bi-printer me-1"></i>Cetak PDF
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-md-4">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Jadwal Tanam</div>
                <div class="fs-3 fw-bold">{{ $jadwalTanam->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Estimasi Panen</div>
                <div class="fs-3 fw-bold">{{ $estimasiPanen->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Riwayat Notifikasi</div>
                <div class="fs-3 fw-bold">{{ $riwayatNotifikasi->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft mb-3">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div class="fw-semibold">Filter Periode</div>
            <form class="d-flex flex-wrap gap-2 align-items-end" method="GET" action="{{ route('laporan.index') }}">
                <div>
                    <label class="form-label mb-1">Dari</label>
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div>
                    <label class="form-label mb-1">Sampai</label>
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <button class="btn btn-success">
                    <i class="bi bi-funnel me-1"></i>Terapkan
                </button>
            </form>
        </div>
    </div>
</div>

<ul class="nav nav-pills gap-2 mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabTanam" type="button" role="tab">
            <i class="bi bi-calendar2-check me-1"></i>Jadwal Tanam
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabPanen" type="button" role="tab">
            <i class="bi bi-basket me-1"></i>Estimasi Panen
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabNotif" type="button" role="tab">
            <i class="bi bi-whatsapp me-1"></i>Riwayat Notifikasi
        </button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tabTanam" role="tabpanel">
        <div class="card card-soft">
            <div class="card-body">
                <div class="fw-semibold mb-2">Jadwal Tanam</div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th>Tanaman</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Pemilik</th>
                                @endif
                                <th>Lokasi</th>
                                <th>Pembibitan</th>
                                <th>Pindah Lahan</th>
                                <th class="text-end">Luas (m²)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalTanam as $i => $t)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $t->nama_tanaman }}</td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <div class="fw-semibold">{{ $t->user?->name }}</div>
                                            <div class="small text-muted">{{ $t->user?->email }}</div>
                                        </td>
                                    @endif
                                    <td>{{ $t->lokasi }}</td>
                                    <td>{{ $t->tanggal_tanam->toDateString() }}</td>
                                    <td>{{ $t->tanggal_pindah_lahan?->toDateString() ?: '-' }}</td>
                                    <td class="text-end">{{ number_format((float) $t->luas_lahan, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}" class="text-muted text-center py-4">Tidak ada data pada rentang tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabPanen" role="tabpanel">
        <div class="card card-soft">
            <div class="card-body">
                <div class="fw-semibold mb-2">Estimasi Panen</div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th>Tanaman</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Pemilik</th>
                                @endif
                                <th>Lokasi</th>
                                <th>Pembibitan</th>
                                <th>Pindah Lahan</th>
                                <th>Estimasi Panen</th>
                                <th>Status</th>
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
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $t->nama_tanaman }}</td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <div class="fw-semibold">{{ $t->user?->name }}</div>
                                            <div class="small text-muted">{{ $t->user?->email }}</div>
                                        </td>
                                    @endif
                                    <td>{{ $t->lokasi }}</td>
                                    <td>{{ $t->tanggal_tanam->toDateString() }}</td>
                                    <td>{{ $t->tanggal_pindah_lahan?->toDateString() ?: '-' }}</td>
                                    <td>{{ $t->estimasi_panen->toDateString() }}</td>
                                    <td><span class="badge badge-soft badge-{{ $badge }}">{{ ucfirst($badge) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="text-muted text-center py-4">Tidak ada data pada rentang tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabNotif" role="tabpanel">
        <div class="card card-soft">
            <div class="card-body">
                <div class="fw-semibold mb-2">Riwayat Notifikasi</div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 56px;">No</th>
                                <th>Tanggal</th>
                                <th>Tanaman</th>
                                <th>Jenis</th>
                                <th>Nomor</th>
                                <th>Status</th>
                                <th>Pesan</th>
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
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td class="text-nowrap">{{ $n->tanggal_kirim?->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $n->tanaman?->nama_tanaman }}</div>
                                        <div class="small text-muted">{{ $n->tanaman?->lokasi }}</div>
                                    </td>
                                    <td><span class="badge text-bg-light border">{{ $jenisText }}</span></td>
                                    <td class="text-nowrap">{{ $n->nomor }}</td>
                                    <td>
                                        <span class="badge {{ $n->status === 'terkirim' ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ ucfirst($n->status) }}
                                        </span>
                                    </td>
                                    <td style="max-width: 520px;">
                                        <div class="text-truncate" title="{{ $n->pesan }}">{{ $n->pesan }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-muted text-center py-4">Tidak ada data pada rentang tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
