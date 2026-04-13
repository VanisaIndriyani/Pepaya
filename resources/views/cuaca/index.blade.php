@extends('layouts.app')

@section('title', 'Informasi Cuaca | Tanam Pepaya')
@section('page_title', 'Informasi Cuaca')

@section('content')
<div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Informasi Cuaca</h4>
        <p class="text-muted small mb-0">Pantau cuaca untuk membantu menentukan waktu tanam dan perawatan tanaman.</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
            <i class="bi bi-geo-alt-fill me-1"></i>{{ number_format($lat, 4) }}, {{ number_format($lon, 4) }}
        </span>
        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-3 py-2 rounded-pill">
            <i class="bi bi-clock-fill me-1"></i>{{ $timezone }}
        </span>
    </div>
</div>

@if(! $ok)
    <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div class="fw-semibold">Gagal mengambil data cuaca.</div>
        <div class="small opacity-75">{{ $error }}</div>
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-7">
        <div class="card card-soft border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-bold">Cuaca Saat Ini</div>
                        <div class="text-muted small">Ringkasan kondisi terbaru dari layanan cuaca.</div>
                    </div>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                        <i class="bi bi-cloud-sun-fill me-1 text-warning"></i>{{ $current['desc'] }}
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4 border bg-white">
                            <div class="text-muted small mb-1">Suhu</div>
                            <div class="fw-bold fs-5">{{ $current['temp_c'] !== null ? $current['temp_c'].'°C' : '-' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4 border bg-white">
                            <div class="text-muted small mb-1">Kelembaban</div>
                            <div class="fw-bold fs-5">{{ $current['humidity'] !== null ? $current['humidity'].'%' : '-' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4 border bg-white">
                            <div class="text-muted small mb-1">Curah Hujan</div>
                            <div class="fw-bold fs-5">{{ $current['precip_mm'] !== null ? $current['precip_mm'].' mm' : '-' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-4 border bg-white">
                            <div class="text-muted small mb-1">Hujan</div>
                            <div class="fw-bold fs-5">{{ $current['rain_mm'] !== null ? $current['rain_mm'].' mm' : '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="fw-bold mb-2">Rekomendasi</div>
                    @if(count($recommendations['warnings']) > 0)
                        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-3">
                            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i>Peringatan</div>
                            <ul class="mb-0">
                                @foreach($recommendations['warnings'] as $w)
                                    <li>{{ $w }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-success border-0 rounded-4 shadow-sm mb-0">
                        <div class="fw-bold mb-1"><i class="bi bi-lightbulb-fill me-1"></i>Saran</div>
                        <ul class="mb-0">
                            @foreach($recommendations['suggestions'] as $s)
                                <li>{{ $s }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card card-soft border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="fw-bold mb-3">Perkiraan {{ count($forecast) }} Hari</div>
                <div class="d-flex flex-column gap-2">
                    @forelse($forecast as $d)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-4 border bg-white">
                            <div class="min-w-0">
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($d['date'])->translatedFormat('D, d M Y') }}</div>
                                <div class="text-muted small text-truncate">{{ $d['desc'] }}</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $d['temp_min'] }}° / {{ $d['temp_max'] }}°</div>
                                <div class="small text-muted">{{ $d['precip_mm'] }} mm</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Belum ada data perkiraan cuaca.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="fw-bold mb-1">Ubah Lokasi</div>
                <div class="text-muted small">Masukkan koordinat (latitude & longitude) jika ingin menampilkan cuaca lokasi lain.</div>
            </div>
            <form class="d-flex flex-wrap gap-2 align-items-end" method="GET" action="{{ route('cuaca.index') }}">
                <div>
                    <label class="form-label small fw-bold text-muted mb-1">Latitude</label>
                    <input type="number" step="0.0001" name="lat" class="form-control rounded-3" value="{{ $lat }}">
                </div>
                <div>
                    <label class="form-label small fw-bold text-muted mb-1">Longitude</label>
                    <input type="number" step="0.0001" name="lon" class="form-control rounded-3" value="{{ $lon }}">
                </div>
                <div>
                    <label class="form-label small fw-bold text-muted mb-1">Hari</label>
                    <input type="number" min="1" max="7" name="days" class="form-control rounded-3" value="{{ $days }}">
                </div>
                <button class="btn btn-success px-4 rounded-3">
                    <i class="bi bi-cloud-arrow-down-fill me-1"></i>Tampilkan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

