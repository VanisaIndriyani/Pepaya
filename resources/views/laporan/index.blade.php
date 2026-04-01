@extends('layouts.app')

@section('title', 'Laporan | Tanam Pepaya')
@section('page_title', 'Laporan')

@section('content')
<div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Laporan Operasional</h4>
        <p class="text-muted small mb-0">Pantau ringkasan jadwal tanam, estimasi panen, dan aktivitas notifikasi.</p>
    </div>
    <a class="btn btn-success px-4 py-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" target="_blank" href="{{ route('laporan.pdf', ['from' => $from, 'to' => $to]) }}">
        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Cetak PDF
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card card-soft border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #198754 0%, #11623d 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="small opacity-75 fw-medium">Jadwal Tanam</div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-calendar-check-fill fs-5 text-white"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-white">{{ $jadwalTanam->count() }}</h3>
                <div class="small opacity-75 mt-2">Data penanaman baru</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-soft border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="small opacity-75 fw-medium">Estimasi Panen</div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-basket3-fill fs-5 text-white"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-white">{{ $estimasiPanen->count() }}</h3>
                <div class="small opacity-75 mt-2">Tanaman siap panen</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-soft border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0d6efd 0%, #084298 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="small opacity-75 fw-medium">Notifikasi</div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-whatsapp fs-5 text-white"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-white">{{ $riwayatNotifikasi->count() }}</h3>
                <div class="small opacity-75 mt-2">Pesan WhatsApp terkirim</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-funnel-fill text-success me-2"></i>Filter Laporan</h6>
            <form class="d-flex flex-wrap gap-2 align-items-end" method="GET" action="{{ route('laporan.index') }}">
                <div class="flex-grow-1">
                    <label class="form-label small fw-bold text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control rounded-3" value="{{ $from }}">
                </div>
                <div class="flex-grow-1">
                    <label class="form-label small fw-bold text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control rounded-3" value="{{ $to }}">
                </div>
                <button class="btn btn-light px-4 rounded-3 border">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </form>
        </div>
    </div>
</div>

<ul class="nav nav-pills nav-fill gap-2 mb-4 bg-white p-2 rounded-4 shadow-sm border" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-3 py-2 fw-bold" data-bs-toggle="tab" data-bs-target="#tabTanam" type="button" role="tab">
            <i class="bi bi-calendar2-check-fill me-2"></i>Jadwal Tanam
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3 py-2 fw-bold" data-bs-toggle="tab" data-bs-target="#tabPanen" type="button" role="tab">
            <i class="bi bi-basket-fill me-2"></i>Estimasi Panen
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3 py-2 fw-bold" data-bs-toggle="tab" data-bs-target="#tabNotif" type="button" role="tab">
            <i class="bi bi-whatsapp me-2"></i>Notifikasi
        </button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tabTanam" role="tabpanel">
        <div class="card card-soft border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Informasi Tanaman</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="py-3">Pemilik</th>
                                @endif
                                <th class="py-3">Tgl Tanam</th>
                                <th class="py-3">Pindah Lahan</th>
                                <th class="pe-4 py-3 text-end">Luas (m²)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwalTanam as $t)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $t->nama_tanaman }}</div>
                                        <div class="small text-muted"><i class="bi bi-geo-alt-fill me-1"></i>{{ $t->lokasi }}</div>
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <div class="fw-medium">{{ $t->user?->name }}</div>
                                            <div class="small text-muted">{{ $t->user?->email }}</div>
                                        </td>
                                    @endif
                                    <td>{{ $t->tanggal_tanam->format('d/m/Y') }}</td>
                                    <td>{{ $t->tanggal_pindah_lahan?->format('d/m/Y') ?: '-' }}</td>
                                    <td class="pe-4 text-end fw-bold">{{ number_format((float) $t->luas_lahan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data penanaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabPanen" role="tabpanel">
        <div class="card card-soft border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Informasi Tanaman</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="py-3">Pemilik</th>
                                @endif
                                <th class="py-3">Estimasi Panen</th>
                                <th class="pe-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($estimasiPanen as $t)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $badge = $t->status;
                                    if ($badge !== 'selesai' && $today->gte($t->estimasi_panen)) { $badge = 'panen'; }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $t->nama_tanaman }}</div>
                                        <div class="small text-muted"><i class="bi bi-geo-alt-fill me-1"></i>{{ $t->lokasi }}</div>
                                    </td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>
                                            <div class="fw-medium">{{ $t->user?->name }}</div>
                                            <div class="small text-muted">{{ $t->user?->email }}</div>
                                        </td>
                                    @endif
                                    <td class="fw-bold text-success">{{ $t->estimasi_panen->format('d M Y') }}</td>
                                    <td class="pe-4 text-center">
                                        <span class="badge rounded-pill bg-opacity-10 px-3 border
                                            @if($badge === 'aktif') bg-primary text-primary border-primary border-opacity-25
                                            @elseif($badge === 'panen') bg-warning text-warning border-warning border-opacity-25
                                            @else bg-success text-success border-success border-opacity-25 @endif">
                                            {{ strtoupper($badge) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data panen.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabNotif" role="tabpanel">
        <div class="card card-soft border-0 shadow-sm overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Waktu & Tanaman</th>
                                <th class="py-3">Jenis Aktivitas</th>
                                <th class="py-3">Nomor WhatsApp</th>
                                <th class="pe-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatNotifikasi as $n)
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
                                        default => 'Umum',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $n->tanaman?->nama_tanaman }}</div>
                                        <div class="small text-muted">{{ $n->tanggal_kirim?->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border fw-medium px-2">{{ $jenisText }}</span></td>
                                    <td class="fw-medium text-success"><i class="bi bi-whatsapp me-1"></i>{{ $n->nomor }}</td>
                                    <td class="pe-4 text-center">
                                        <span class="badge rounded-pill px-3 {{ $n->status === 'terkirim' ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25' }}">
                                            {{ strtoupper($n->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-5 text-muted">Tidak ada riwayat notifikasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
