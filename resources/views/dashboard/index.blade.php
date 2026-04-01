@extends('layouts.app')

@section('title', 'Dashboard | Tanam Pepaya')
@section('page_title', 'Dashboard')

@section('content')
<div class="welcome-header mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-1">Halo, {{ auth()->user()->name }}! 👋</h3>
            <p class="text-muted mb-0">Selamat datang kembali di sistem pemantauan perkebunan pepaya Anda.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('tanaman.index') }}" class="btn btn-success px-4 py-2 rounded-3 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Tanaman
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #198754 0%, #11623d 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-tree-fill fs-3 text-white"></i>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75 fw-medium">Total Tanaman</div>
                        <h2 class="fw-bold mb-0 text-white">{{ $totalTanaman }}</h2>
                    </div>
                </div>
                <div class="small opacity-75">Seluruh data tercatat</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #0d6efd 0%, #084298 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-check-circle-fill fs-3 text-white"></i>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75 fw-medium">Tanaman Aktif</div>
                        <h2 class="fw-bold mb-0 text-white">{{ $tanamanAktif }}</h2>
                    </div>
                </div>
                <div class="small opacity-75 text-truncate">Sedang dalam perawatan</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-calendar-event-fill fs-3 text-white"></i>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75 fw-medium">Jadwal (7 Hari)</div>
                        <h2 class="fw-bold mb-0 text-white">{{ $jadwalTanam }}</h2>
                    </div>
                </div>
                <div class="small opacity-75">Tugas perawatan mendatang</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: white;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        <i class="bi bi-basket3-fill fs-3 text-white"></i>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75 fw-medium">Panen (30 Hari)</div>
                        <h2 class="fw-bold mb-0 text-white">{{ $estimasiPanen }}</h2>
                    </div>
                </div>
                <div class="small opacity-75">Estimasi masa panen</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12 col-xl-8">
        <div class="card card-soft border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Statistik Penanaman</h5>
                <div class="badge bg-light text-dark px-3 py-2 rounded-pill border">Per Bulan</div>
            </div>
            <div class="card-body p-4">
                <canvas id="growthChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card card-soft border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Info Panen Terdekat</h5>
            </div>
            <div class="card-body p-4">
                @if($panenTerdekat)
                    <div class="next-harvest p-4 rounded-4 mb-4 text-center" style="background: rgba(25,135,84,0.05); border: 1px solid rgba(25,135,84,0.1);">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-flower1 fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $panenTerdekat->nama_tanaman }}</h5>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill me-1"></i>{{ $panenTerdekat->lokasi }}</p>
                        
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Estimasi Panen</span>
                            <span class="fw-bold text-dark">{{ $panenTerdekat->estimasi_panen->toDateString() }}</span>
                        </div>
                        <div class="progress rounded-pill mb-2" style="height: 8px;">
                            @php
                                $start = $panenTerdekat->tanggal_tanam;
                                $end = $panenTerdekat->estimasi_panen;
                                $total = $start->diffInDays($end) ?: 1;
                                $passed = $start->diffInDays(now());
                                $percent = min(100, max(0, ($passed / $total) * 100));
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="text-center small">
                            @if($percent >= 100)
                                <span class="badge bg-success">Siap Panen!</span>
                            @else
                                <span class="text-muted">{{ round($percent) }}% Menuju Panen</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-info-circle fs-1 opacity-25"></i>
                        <p class="mt-2 mb-0">Belum ada data panen.</p>
                    </div>
                @endif
                
                <a href="{{ route('tanaman.index') }}" class="btn btn-outline-success w-100 rounded-3 py-2 fw-semibold">
                    Kelola Semua Tanaman
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft border-0 shadow-sm mt-4">
    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Tanaman Terbaru</h5>
        <a href="{{ route('tanaman.index') }}" class="btn btn-light btn-sm px-3 rounded-pill border">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0">Tanaman</th>
                        <th class="py-3 border-0">Lokasi</th>
                        <th class="py-3 border-0">Tgl Tanam</th>
                        <th class="py-3 border-0">Estimasi Panen</th>
                        <th class="pe-4 py-3 border-0 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanamanTerbaru as $t)
                        @php
                            $today = \Carbon\Carbon::today();
                            $status = $t->status;
                            if ($status !== 'selesai' && $today->gte($t->estimasi_panen)) { $status = 'panen'; }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-2 me-3">
                                        <i class="bi bi-flower1"></i>
                                    </div>
                                    <div class="fw-bold text-dark">{{ $t->nama_tanaman }}</div>
                                </div>
                            </td>
                            <td>{{ $t->lokasi }}</td>
                            <td>{{ $t->tanggal_tanam->toDateString() }}</td>
                            <td>{{ $t->estimasi_panen->toDateString() }}</td>
                            <td class="pe-4 text-center">
                                <span class="badge rounded-pill bg-opacity-10 px-3 border
                                    @if($status === 'aktif') bg-primary text-primary border-primary border-opacity-25
                                    @elseif($status === 'panen') bg-warning text-warning border-warning border-opacity-25
                                    @else bg-success text-success border-success border-opacity-25 @endif">
                                    {{ strtoupper($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data tanaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Penanaman',
                    data: {!! json_encode($chartSeries) !!},
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#198754',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { weight: '500' }
                        },
                        grid: {
                            display: true,
                            drawBorder: false,
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { weight: '500' }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

