@extends('layouts.app')

@section('title', 'Data User | Tanam Pepaya')
@section('page_title', 'Data User')

@section('content')
<div class="d-flex flex-column flex-sm-row gap-3 justify-content-between align-items-sm-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kelola User</h4>
        <p class="text-muted small mb-0">Halaman ini digunakan untuk mengelola akses pengguna sistem.</p>
    </div>
    <button class="btn btn-success px-3 py-2 shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#userCreateModal">
        <i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru
    </button>
</div>

<div class="card card-soft border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">User</th>
                        <th class="py-3 d-none d-md-table-cell">Kontak</th>
                        <th class="py-3 text-center">Role</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        <div class="small text-muted">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="small fw-medium">{{ $u->phone ?: '-' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill {{ $u->role === 'admin' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 text-{{ $u->role === 'admin' ? 'success' : 'secondary' }} border border-{{ $u->role === 'admin' ? 'success' : 'secondary' }} border-opacity-25 px-3">
                                    {{ strtoupper($u->role) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-light btn-sm rounded-3 border"
                                            data-action="edit-user"
                                            data-url="{{ route('users.update', $u) }}"
                                            data-name="{{ $u->name }}"
                                            data-email="{{ $u->email }}"
                                            data-phone="{{ $u->phone }}"
                                            data-role="{{ $u->role }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userEditModal"
                                            title="Edit User">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <form method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-light btn-sm rounded-3 border" type="submit" title="Hapus User">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    <i class="bi bi-people fs-1 opacity-25"></i>
                                </div>
                                <div class="fw-medium text-muted">Belum ada data user.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        <div class="d-flex justify-content-center justify-content-md-end">
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

<div class="modal fade" id="userCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-3" required placeholder="Masukkan nama">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <input type="email" name="email" class="form-control rounded-3" required placeholder="email@contoh.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WhatsApp</label>
                            <input type="text" name="phone" class="form-control rounded-3" placeholder="628xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" class="form-select rounded-3" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3" required placeholder="Minimal 6 karakter">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 rounded-3 shadow-sm">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="userEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Perbarui Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="userEditForm">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" id="uName" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <input type="email" name="email" id="uEmail" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WhatsApp</label>
                            <input type="text" name="phone" id="uPhone" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" id="uRole" class="form-select rounded-3" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Password (opsional)</label>
                            <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak ingin diubah">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-3 border" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 rounded-3 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action="edit-user"]');
        if (!btn) return;
        const form = document.getElementById('userEditForm');
        form.action = btn.dataset.url;
        document.getElementById('uName').value = btn.dataset.name || '';
        document.getElementById('uEmail').value = btn.dataset.email || '';
        document.getElementById('uPhone').value = btn.dataset.phone || '';
        document.getElementById('uRole').value = btn.dataset.role || 'user';
        form.querySelector('input[name="password"]').value = '';
    });
</script>
@endpush

