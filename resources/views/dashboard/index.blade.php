@extends('layouts.app')

@section('title', 'Dashboard | Tanam Pepaya')
@section('page_title', 'Dashboard')

@section('content')
<div class="row g-3">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Total Data Tanaman</div>
                        <div class="fs-3 fw-bold">{{ $totalTanaman }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2">
                        <i class="bi bi-flower1 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Tanaman Aktif</div>
                        <div class="fs-3 fw-bold">{{ $tanamanAktif }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Jadwal Perawatan (7 hari)</div>
                        <div class="fs-3 fw-bold">{{ $jadwalTanam }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2">
                        <i class="bi bi-calendar2-week fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="small opacity-75">Estimasi Panen (30 hari)</div>
                        <div class="fs-3 fw-bold">{{ $estimasiPanen }}</div>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-3 p-2">
                        <i class="bi bi-basket fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12 col-xl-8">
        <div class="card card-soft">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="fw-semibold">Grafik Pertumbuhan (Data Tanam / Bulan)</div>
                    <div class="text-muted small">Chart.js</div>
                </div>
                <canvas id="growthChart" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card card-soft">
            <div class="card-body">
                <div class="fw-semibold mb-2">Countdown Panen</div>
                @if($countdownPanen)
                    <div class="p-3 rounded-4" style="background: rgba(25,135,84,.08);">
                        <div class="fw-bold">{{ $countdownPanen['nama'] }}</div>
                        <div class="text-muted small">{{ $countdownPanen['lokasi'] }}</div>
                        <div class="mt-2 d-flex align-items-center justify-content-between">
                            <div class="small text-muted">Estimasi</div>
                            <div class="fw-semibold">{{ $countdownPanen['estimasi'] }}</div>
                        </div>
                        <div class="mt-2">
                            @if($countdownPanen['days'] > 0)
                                <div class="display-6 fw-bold text-success">{{ $countdownPanen['days'] }}</div>
                                <div class="text-muted">hari lagi</div>
                            @else
                                <div class="display-6 fw-bold text-warning">0</div>
                                <div class="text-muted">sudah masuk masa panen</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-muted">Belum ada data tanaman.</div>
                @endif
                <div class="mt-3">
                    <a href="{{ route('tanaman.index') }}" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle me-1"></i>Tambah / Kelola Tanaman
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft mt-3">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="fw-semibold">Tanaman Terbaru</div>
            <a href="{{ route('tanaman.index') }}" class="btn btn-outline-success btn-sm">Lihat Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Tanaman</th>
                        <th>Lokasi</th>
                        <th>Tanggal Tanam</th>
                        <th>Estimasi Panen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanamanTerbaru as $t)
                        @php
                            $today = \Carbon\Carbon::today();
                            $badge = $t->status;
                            if ($badge !== 'selesai' && $today->gte($t->estimasi_panen)) { $badge = 'panen'; }
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $t->nama_tanaman }}</td>
                            <td>{{ $t->lokasi }}</td>
                            <td>{{ $t->tanggal_tanam->toDateString() }}</td>
                            <td>{{ $t->estimasi_panen->toDateString() }}</td>
                            <td>
                                <span class="badge badge-soft badge-{{ $badge }}">{{ ucfirst($badge) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center py-4">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const labels = @json($chartLabels);
    const series = @json($chartSeries);
    const ctx = document.getElementById('growthChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Jumlah Tanam',
                    data: series,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,.15)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }
</script>
@endpush

