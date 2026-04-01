@extends('layouts.app')

@section('title', 'Notifikasi | Tanam Pepaya')
@section('page_title', 'Data Notifikasi')

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
        <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
            <i class="bi bi-whatsapp fs-4"></i>
        </div>
        <div>
            <div class="fw-semibold">Notifikasi WhatsApp</div>
            <div class="text-muted small">Riwayat pengiriman berdasarkan jadwal.</div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <form method="POST" action="{{ route('notifikasi.kirimHariIni') }}">
            @csrf
            <button class="btn btn-success">
                <i class="bi bi-send me-1"></i>Kirim Hari Ini
            </button>
        </form>
    </div>
</div>

<div class="card card-soft">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 56px;">No</th>
                        <th>Tanggal Kirim</th>
                        <th>Tanaman</th>
                        <th>Jenis</th>
                        <th>Nomor</th>
                        <th>Pesan</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 84px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifikasi as $i => $n)
                        <tr>
                            <td class="text-muted">{{ $notifikasi->firstItem() + $i }}</td>
                            <td>{{ $n->tanggal_kirim?->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $n->tanaman?->nama_tanaman }}</div>
                                <div class="small text-muted">{{ $n->tanaman?->lokasi }}</div>
                            </td>
                            <td>
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
                                <span class="badge text-bg-light border">{{ $jenisText }}</span>
                            </td>
                            <td class="text-nowrap">{{ $n->nomor }}</td>
                            <td style="max-width: 420px;">
                                <div class="text-truncate" title="{{ $n->pesan }}">{{ $n->pesan }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $n->status === 'terkirim' ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ ucfirst($n->status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-outline-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#notifikasiDetailModal"
                                    data-tanaman="{{ $n->tanaman?->nama_tanaman }}"
                                    data-lokasi="{{ $n->tanaman?->lokasi }}"
                                    data-tanggal="{{ $n->tanggal_kirim?->format('Y-m-d H:i') }}"
                                    data-nomor="{{ $n->nomor }}"
                                    data-status="{{ $n->status }}"
                                    data-jenis="{{ $jenisText }}"
                                    data-pesan='@json($n->pesan)'
                                    data-response='@json($n->response)'
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-muted text-center py-4">Belum ada notifikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            {{ $notifikasi->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="notifikasiDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header">
                <div>
                    <div class="modal-title fw-semibold">Detail Notifikasi</div>
                    <div class="text-muted small" id="ndMeta"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Tanaman</div>
                        <div class="fw-semibold" id="ndTanaman"></div>
                        <div class="small text-muted" id="ndLokasi"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Status</div>
                        <div><span class="badge" id="ndStatus"></span></div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted">Pesan</div>
                        <div class="p-3 rounded-4" style="background: rgba(25,135,84,.06); border: 1px solid rgba(25,135,84,.12); white-space: pre-wrap;" id="ndPesan"></div>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('show.bs.modal', function (event) {
        const modal = event.target;
        if (!modal || modal.id !== 'notifikasiDetailModal') return;
        const button = event.relatedTarget;
        if (!button) return;

        const tanaman = button.getAttribute('data-tanaman') || '-';
        const lokasi = button.getAttribute('data-lokasi') || '-';
        const tanggal = button.getAttribute('data-tanggal') || '-';
        const nomor = button.getAttribute('data-nomor') || '-';
        const status = button.getAttribute('data-status') || '-';
        const jenis = button.getAttribute('data-jenis') || '-';

        let pesan = '';
        let response = '';
        try { pesan = JSON.parse(button.getAttribute('data-pesan') || '""') || ''; } catch (e) { pesan = ''; }
        try { response = JSON.parse(button.getAttribute('data-response') || '""') || ''; } catch (e) { response = ''; }

        modal.querySelector('#ndTanaman').textContent = tanaman;
        modal.querySelector('#ndLokasi').textContent = lokasi;
        modal.querySelector('#ndMeta').textContent = `${tanggal} • ${jenis} • ${nomor}`;

        const badge = modal.querySelector('#ndStatus');
        badge.textContent = status;
        badge.className = 'badge ' + (status === 'terkirim' ? 'text-bg-success' : 'text-bg-danger');

        modal.querySelector('#ndPesan').textContent = pesan || '-';
        modal.querySelector('#ndResponse').textContent = response || '-';
    });
</script>
@endpush
