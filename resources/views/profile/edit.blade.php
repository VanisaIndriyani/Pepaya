@extends('layouts.app')

@section('title', 'Profil | Tanam Pepaya')
@section('page_title', 'Profil')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card card-soft border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-circle fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Pengaturan Profil</h4>
                            <p class="text-muted small mb-0">Kelola informasi pribadi dan keamanan akun Anda.</p>
                        </div>
                    </div>
                    <div>
                        <span class="badge rounded-pill {{ $user->role === 'admin' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 text-{{ $user->role === 'admin' ? 'success' : 'secondary' }} border border-{{ $user->role === 'admin' ? 'success' : 'secondary' }} border-opacity-25 px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i>{{ strtoupper($user->role) }}
                        </span>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-light p-3 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Informasi Dasar</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat Email</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required placeholder="email@contoh.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="text" name="phone" class="form-control rounded-3 border-start-0" value="{{ old('phone', $user->phone) }}" placeholder="628xxxxxxxxxx">
                                </div>
                                <div class="form-text mt-1">Digunakan untuk mengirimkan notifikasi jadwal perawatan.</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak diubah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Hanya isi jika Anda ingin mengganti password lama Anda.</div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-light px-4 py-2 rounded-3 border">Batal</button>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 shadow-sm">
                            <i class="bi bi-save-fill me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

