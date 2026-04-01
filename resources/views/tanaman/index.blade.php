@extends('layouts.app')

@section('title', 'Data Tanaman | Tanam Pepaya')
@section('page_title', 'Data Tanaman')

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
            <i class="bi bi-flower1 fs-4"></i>
        </div>
        <div>
            <div class="fw-semibold">Data Tanaman</div>
            <div class="text-muted small">Kelola pembibitan, jadwal perawatan, dan estimasi panen.</div>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <form method="GET" action="{{ route('tanaman.index') }}" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Cari tanaman/lokasi/pemilik...">
            </div>
            <button class="btn btn-outline-success">Cari</button>
        </form>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tanamanCreateModal">
            <i class="bi bi-plus-circle me-1"></i>Tambah
        </button>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Total Tanaman</div>
                <div class="fs-3 fw-bold">{{ $totalTanaman }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Tanaman Aktif</div>
                <div class="fs-3 fw-bold">{{ $tanamanAktif }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Jadwal Hari Ini</div>
                <div class="fs-3 fw-bold">{{ $jadwalHariIni }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-soft stat-card">
            <div class="card-body">
                <div class="small opacity-75">Panen (30 Hari)</div>
                <div class="fs-3 fw-bold">{{ $panen30Hari }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanaman</th>
                        @if(auth()->user()->role === 'admin')
                            <th>Pemilik</th>
                        @endif
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Luas (m²)</th>
                        <th>Jadwal Hari Ini</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tanaman as $i => $t)
                        @php
                            $today = \Carbon\Carbon::today();
                            $badge = $t->status;
                            if ($badge !== 'selesai' && $today->gte($t->estimasi_panen)) { $badge = 'panen'; }
                            $days = $today->diffInDays($t->estimasi_panen, false);
                        @endphp
                        <tr>
                            <td>{{ $tanaman->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-start gap-2">
                                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center mt-1" style="width:34px;height:34px;">
                                        <i class="bi bi-flower1"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $t->nama_tanaman }}</div>
                                        <div class="small text-muted">
                                            Mode Panen: <span class="fw-semibold">{{ $t->panen_mode === 'tidak_normal' ? 'Tidak Normal' : 'Normal' }}</span>
                                        </div>
                                        <div class="small text-muted">
                                            @if($days > 0)
                                                Countdown: {{ $days }} hari lagi
                                            @else
                                                Countdown: masuk masa panen
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @if(auth()->user()->role === 'admin')
                                <td>
                                    <div class="fw-semibold">{{ $t->user?->name }}</div>
                                    <div class="small text-muted">{{ $t->user?->email }}</div>
                                </td>
                            @endif
                            <td>{{ $t->lokasi }}</td>
                            <td>
                                <div class="small text-muted">Pembibitan</div>
                                <div class="fw-semibold">{{ $t->tanggal_tanam->toDateString() }}</div>
                                <div class="small text-muted mt-1">Pindah Lahan</div>
                                <div class="fw-semibold">{{ $t->tanggal_pindah_lahan?->toDateString() ?: '-' }}</div>
                                <div class="small text-muted mt-1">Estimasi Panen</div>
                                <div class="fw-semibold">{{ $t->estimasi_panen->toDateString() }}</div>
                            </td>
                            <td>{{ number_format((float) $t->luas_lahan, 2, ',', '.') }}</td>
                            <td>
                                @if($t->jadwal_hari_ini > 0)
                                    <span class="badge text-bg-success">{{ $t->jadwal_hari_ini }} jadwal</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-soft badge-{{ $badge }}">{{ ucfirst($badge) }}</span>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ubah</button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('tanaman.status', $t) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="aktif">
                                                    <button class="dropdown-item" type="submit">Aktif</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('tanaman.status', $t) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="panen">
                                                    <button class="dropdown-item" type="submit">Panen</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('tanaman.status', $t) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="selesai">
                                                    <button class="dropdown-item" type="submit">Selesai</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-outline-success btn-sm"
                                            data-action="jadwal"
                                            data-url="{{ route('tanaman.jadwal', $t) }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#jadwalModal">
                                        <i class="bi bi-calendar2-week me-1"></i>Jadwal
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm"
                                            data-action="edit"
                                            data-id="{{ $t->id }}"
                                            data-nama="{{ $t->nama_tanaman }}"
                                            data-tanggal="{{ $t->tanggal_tanam->toDateString() }}"
                                            data-panen_mode="{{ $t->panen_mode }}"
                                            data-luas="{{ $t->luas_lahan }}"
                                            data-lokasi="{{ $t->lokasi }}"
                                            data-keterangan="{{ $t->keterangan }}"
                                            data-url="{{ route('tanaman.update', $t) }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#tanamanEditModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form method="POST" action="{{ route('tanaman.destroy', $t) }}" onsubmit="return confirm('Hapus data tanaman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 10 : 9 }}" class="text-muted text-center py-4">Belum ada data tanaman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {{ $tanaman->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="tanamanCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Tanaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('tanaman.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        @if(auth()->user()->role === 'admin')
                            <div class="col-12">
                                <label class="form-label">Pemilik</label>
                                <select name="user_id" class="form-select">
                                    <option value="">(Pilih user)</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} - {{ $u->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-6">
                            <label class="form-label">Nama Tanaman</label>
                            <input type="text" name="nama_tanaman" class="form-control" value="Pepaya">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Pembibitan (Kokeran)</label>
                            <input type="date" name="tanggal_tanam" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mode Panen</label>
                            <select name="panen_mode" class="form-select">
                                <option value="normal">Normal (1 minggu 3x)</option>
                                <option value="tidak_normal">Tidak Normal (4 hari 1x)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Luas Lahan (m²)</label>
                            <input type="number" step="0.01" name="luas_lahan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="alert alert-success mt-3 mb-0">
                        Sistem akan otomatis: pembibitan 1 bulan (pindah lahan), pertumbuhan 6 bulan (estimasi panen), perawatan berkala, dan jadwal panen 3 tahun.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tanamanEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Tanaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="tanamanEditForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Tanaman</label>
                            <input type="text" name="nama_tanaman" id="editNama" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Pembibitan (Kokeran)</label>
                            <input type="date" name="tanggal_tanam" id="editTanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mode Panen</label>
                            <select name="panen_mode" id="editPanenMode" class="form-select">
                                <option value="normal">Normal (1 minggu 3x)</option>
                                <option value="tidak_normal">Tidak Normal (4 hari 1x)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Luas Lahan (m²)</label>
                            <input type="number" step="0.01" name="luas_lahan" id="editLuas" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" id="editLokasi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="editKeterangan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="alert alert-success mt-3 mb-0">
                        Sistem akan mengatur ulang pembibitan, estimasi panen, jadwal perawatan, dan jadwal panen berdasarkan input terbaru.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="jadwalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <h5 class="modal-title">Jadwal Perawatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="jadwalBody">
                <div class="text-muted">Memuat jadwal...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action="edit"]');
        if (!btn) return;
        const form = document.getElementById('tanamanEditForm');
        form.action = btn.dataset.url;
        document.getElementById('editNama').value = btn.dataset.nama || 'Pepaya';
        document.getElementById('editTanggal').value = btn.dataset.tanggal || '';
        document.getElementById('editPanenMode').value = btn.dataset.panen_mode || 'normal';
        document.getElementById('editLuas').value = btn.dataset.luas || '';
        document.getElementById('editLokasi').value = btn.dataset.lokasi || '';
        document.getElementById('editKeterangan').value = btn.dataset.keterangan || '';
    });

    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-action="jadwal"]');
        if (!btn) return;
        const body = document.getElementById('jadwalBody');
        body.innerHTML = '<div class="text-muted">Memuat jadwal...</div>';
        try {
            const res = await fetch(btn.dataset.url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
            body.innerHTML = await res.text();
        } catch (err) {
            body.innerHTML = '<div class="alert alert-danger">Gagal memuat jadwal.</div>';
        }
    });
</script>
@endpush
