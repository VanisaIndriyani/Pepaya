@extends('layouts.app')

@section('title', 'Informasi Cuaca | Tanam Pepaya')
@section('page_title', 'Informasi Cuaca')

@push('head')
<style>
    .wx-hero {
        border-radius: 28px;
        overflow: hidden;
        position: relative;
        color: #fff;
        padding: 22px 22px 18px;
        background: radial-gradient(1200px 400px at 50% -10%, rgba(255,255,255,.22), rgba(255,255,255,0)),
            linear-gradient(180deg, rgba(11,87,120,.92), rgba(14,61,88,.92));
        box-shadow: 0 18px 55px rgba(0,0,0,.12);
    }
    .wx-hero.wx-rain {
        background: radial-gradient(1200px 400px at 50% -10%, rgba(255,255,255,.18), rgba(255,255,255,0)),
            linear-gradient(180deg, rgba(58,80,98,.96), rgba(33,52,67,.96));
    }
    .wx-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(700px 240px at 20% 0%, rgba(255,255,255,.18), rgba(255,255,255,0));
        opacity: .9;
        pointer-events: none;
    }
    .wx-rain .wx-rain-overlay {
        position: absolute;
        inset: -40px -40px -40px -40px;
        background-image: linear-gradient(120deg, rgba(255,255,255,.0) 0%, rgba(255,255,255,.0) 42%, rgba(255,255,255,.22) 43%, rgba(255,255,255,.0) 46%, rgba(255,255,255,.0) 100%);
        background-size: 18px 18px;
        opacity: .35;
        transform: rotate(10deg);
        animation: wx-rain 1.1s linear infinite;
        pointer-events: none;
    }
    @keyframes wx-rain {
        0% { background-position: 0 0; }
        100% { background-position: 0 120px; }
    }
    .wx-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.18);
        font-weight: 700;
        font-size: .85rem;
        backdrop-filter: blur(10px);
    }
    .wx-loc {
        margin-top: 18px;
        text-align: center;
    }
    .wx-city {
        font-weight: 900;
        letter-spacing: -0.02em;
        font-size: clamp(28px, 4.2vw, 44px);
        line-height: 1.05;
    }
    .wx-sub {
        margin-top: 6px;
        font-weight: 700;
        opacity: .85;
        letter-spacing: .12em;
        text-transform: uppercase;
        font-size: .78rem;
    }
    .wx-temp {
        margin-top: 18px;
        font-weight: 300;
        letter-spacing: -0.03em;
        font-size: clamp(64px, 7vw, 92px);
        line-height: 1;
        text-align: center;
    }
    .wx-desc {
        margin-top: 6px;
        font-weight: 700;
        opacity: .9;
        text-align: center;
        font-size: 1.05rem;
    }
    .wx-hilo {
        margin-top: 6px;
        text-align: center;
        opacity: .9;
        font-weight: 700;
        letter-spacing: .02em;
    }
    .wx-card {
        border-radius: 22px;
        background: rgba(255,255,255,.75);
        border: 1px solid rgba(0,0,0,.05);
        box-shadow: 0 14px 35px rgba(0,0,0,.05);
        backdrop-filter: blur(16px);
    }
    .wx-card-dark {
        border-radius: 22px;
        background: rgba(255,255,255,.10);
        border: 1px solid rgba(255,255,255,.18);
        box-shadow: 0 16px 40px rgba(0,0,0,.10);
        backdrop-filter: blur(16px);
        color: #fff;
    }
    .wx-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        padding-bottom: 6px;
    }
    .wx-scroll::-webkit-scrollbar { height: 8px; }
    .wx-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,.12); border-radius: 999px; }
    .wx-item {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 78px;
        padding: 12px 10px;
        border-radius: 18px;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.10);
        margin-right: 10px;
    }
    .wx-item .t {
        font-weight: 800;
        opacity: .95;
        font-size: .86rem;
    }
    .wx-item .i { font-size: 1.25rem; opacity: .95; }
    .wx-item .p { font-size: .75rem; opacity: .85; font-weight: 800; color: rgba(255,255,255,.9); }
    .wx-item .v { font-size: 1.1rem; font-weight: 900; }
    .wx-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 18px;
        background: rgba(255,255,255,.65);
        border: 1px solid rgba(0,0,0,.05);
    }
    .wx-row + .wx-row { margin-top: 10px; }
    .wx-day { width: 72px; font-weight: 900; color: #0f172a; }
    .wx-icon { width: 30px; text-align: center; color: #0f172a; opacity: .9; }
    .wx-mm { width: 70px; text-align: right; color: #64748b; font-weight: 800; }
    .wx-min { width: 44px; text-align: right; color: #64748b; font-weight: 900; }
    .wx-max { width: 44px; text-align: right; color: #0f172a; font-weight: 900; }
    .wx-bar {
        flex: 1;
        height: 8px;
        border-radius: 999px;
        background: rgba(15, 23, 42, .08);
        position: relative;
        overflow: hidden;
    }
    .wx-bar > span {
        position: absolute;
        top: 0;
        bottom: 0;
        border-radius: 999px;
        background: linear-gradient(90deg, rgba(59,130,246,.9), rgba(16,185,129,.9));
    }
    .wx-title {
        font-weight: 900;
        letter-spacing: .1em;
        text-transform: uppercase;
        font-size: .72rem;
        opacity: .7;
    }
</style>
@endpush

@section('content')
@if(! $ok)
    <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div class="fw-semibold">Gagal mengambil data cuaca.</div>
        <div class="small opacity-75">{{ $error }}</div>
    </div>
@endif

@php
    $code = (int) ($current['code'] ?? 0);
    $isRain = in_array($code, [51,53,55,56,57,61,63,65,66,67,80,81,82,95,96,99], true);
    $tempsForScale = array_map(fn ($d) => (float) ($d['temp_max'] ?? 0), $forecast);
    $minsForScale = array_map(fn ($d) => (float) ($d['temp_min'] ?? 0), $forecast);
    $globalMin = count($minsForScale) ? min($minsForScale) : 0;
    $globalMax = count($tempsForScale) ? max($tempsForScale) : 0;
    $range = max(1, $globalMax - $globalMin);
@endphp

<div class="wx-hero {{ $isRain ? 'wx-rain' : '' }} mb-4">
    @if($isRain)
        <div class="wx-rain-overlay"></div>
    @endif
    <div class="d-flex justify-content-between align-items-center">
        <span class="wx-pill">
            <i class="bi bi-clock-fill"></i>{{ $now->format('H.i') }}
        </span>
        <span class="wx-pill">
            <i class="bi bi-geo-alt-fill"></i>{{ number_format($lat, 4) }}, {{ number_format($lon, 4) }}
        </span>
    </div>

    <div class="wx-loc">
        <div class="wx-city">{{ $locationName }}</div>
        <div class="wx-sub">{{ $locationSub }}</div>
        <div class="wx-temp">
            {{ $current['temp_c'] !== null ? round((float) $current['temp_c']) : '-' }}°
        </div>
        <div class="wx-desc">{{ $current['desc'] }}</div>
        <div class="wx-hilo">
            T:{{ $todayMax !== null ? round((float) $todayMax) : '-' }}°&nbsp;&nbsp;R:{{ $todayMin !== null ? round((float) $todayMin) : '-' }}°
        </div>
    </div>

    <div class="mt-4">
        <div class="wx-card-dark p-3">
            <div class="wx-title mb-2">Sekarang</div>
            <div class="wx-scroll">
                @forelse($hourlyItems as $it)
                    <div class="wx-item">
                        <div class="t">{{ $it['label'] }}</div>
                        <div class="i"><i class="bi {{ $it['icon'] }}"></i></div>
                        @if($it['type'] === 'hour')
                            @if($it['pop'] !== null && (int) $it['pop'] > 0)
                                <div class="p">{{ (int) $it['pop'] }}%</div>
                            @else
                                <div class="p">&nbsp;</div>
                            @endif
                            <div class="v">{{ round((float) $it['temp']) }}°</div>
                        @else
                            <div class="p">{{ $it['text'] }}</div>
                            <div class="v">&nbsp;</div>
                        @endif
                    </div>
                @empty
                    <div class="text-center opacity-75 py-2">Belum ada data jam-jaman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-7">
        <div class="wx-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="fw-bold">Ringkasan</div>
                    <div class="text-muted small">Kondisi saat ini dan indikator cuaca.</div>
                </div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                    <i class="bi {{ $current['icon'] }} me-1"></i>{{ $timezoneLabel }}
                </span>
            </div>

            <div class="row g-3">
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
                        <div class="text-muted small mb-1">Kelembapan Tanah</div>
                        <div class="fw-bold fs-5">
                            {{ $current['soil_moisture'] !== null ? round(((float) $current['soil_moisture']) * 100).'%' : '-' }}
                        </div>
                        @if($current['soil_moisture_depth'])
                            <div class="small text-muted mt-1">{{ $current['soil_moisture_depth'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 rounded-4 border bg-white">
                        <div class="text-muted small mb-1">Kondisi</div>
                        <div class="fw-bold fs-6 text-truncate">{{ $current['desc'] }}</div>
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

    <div class="col-12 col-lg-5">
        <div class="wx-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="fw-bold">Ramalan {{ count($forecast) }} Hari</div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                    <i class="bi bi-calendar-week-fill me-1"></i>{{ $days }} hari
                </span>
            </div>

            @forelse($forecast as $d)
                @php
                    $minT = (float) $d['temp_min'];
                    $maxT = (float) $d['temp_max'];
                    $left = (($minT - $globalMin) / $range) * 100;
                    $width = max(6, (($maxT - $minT) / $range) * 100);
                @endphp
                <div class="wx-row">
                    <div class="wx-day">{{ \Carbon\Carbon::parse($d['date'])->translatedFormat('D') }}</div>
                    <div class="wx-icon"><i class="bi {{ $d['icon'] }}"></i></div>
                    <div class="wx-mm">{{ round((float) $d['precip_mm']) }} mm</div>
                    <div class="wx-min">{{ round($minT) }}°</div>
                    <div class="wx-bar"><span style="left: {{ $left }}%; width: {{ $width }}%;"></span></div>
                    <div class="wx-max">{{ round($maxT) }}°</div>
                </div>
            @empty
                <div class="text-center text-muted py-4">Belum ada data ramalan.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="wx-card p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="fw-bold mb-1">Ubah Lokasi</div>
            <div class="text-muted small">Masukkan koordinat atau ubah nama lokasi agar tampilan menyesuaikan daerahmu.</div>
        </div>
        <form class="d-flex flex-wrap gap-2 align-items-end" method="GET" action="{{ route('cuaca.index') }}">
            <div>
                <label class="form-label small fw-bold text-muted mb-1">Nama</label>
                <input type="text" name="name" class="form-control rounded-3" value="{{ $locationName }}">
            </div>
            <div>
                <label class="form-label small fw-bold text-muted mb-1">Wilayah</label>
                <input type="text" name="sub" class="form-control rounded-3" value="{{ $locationSub }}">
            </div>
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
@endsection
