<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tanam Pepaya')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --green: #198754; --green2: #28a745; }
        body { background: #f6faf7; overflow-x: hidden; }
        .app-shell { height: 100vh; display: flex; }
        .sidebar {
            background: linear-gradient(180deg, var(--green), #157347);
            color: #fff;
            --bs-offcanvas-width: 290px;
            box-shadow: 0 18px 45px rgba(0,0,0,.18);
        }
        @media (min-width: 992px) {
            body { overflow: hidden; }
            .sidebar {
                width: 270px;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                overflow-y: auto;
                overflow-x: hidden;
            }
        }
        .sidebar::-webkit-scrollbar { width: 10px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.18); border-radius: 999px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar-content { position: relative; z-index: 1; min-height: 100%; display: flex; flex-direction: column; }
        .sidebar-header {
            position: sticky;
            top: 0;
            z-index: 6;
            padding: 14px 12px 12px;
            background: linear-gradient(180deg, rgba(25,135,84,1), rgba(21,115,71,1));
            border-bottom: 1px solid rgba(255,255,255,.14);
        }
        .logo-mark {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.18);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 22px rgba(0,0,0,.18);
        }
        .logo-mark i { font-size: 22px; color: #fff; }
        .sidebar-watermark {
            position: absolute;
            inset: 64px 0 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 0;
        }
        .sidebar-watermark .wm {
            width: 120px;
            height: 120px;
            border-radius: 26px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.14);
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotate(-10deg);
            filter: blur(.0px);
            opacity: .16;
        }
        .sidebar-watermark i { font-size: 60px; color: #fff; opacity: .95; }
        .sidebar .brand { font-weight: 800; letter-spacing: .2px; line-height: 1.05; }
        .sidebar a { color: rgba(255,255,255,.9); text-decoration: none; }
        .sidebar .nav-link { border-radius: 14px; padding: .72rem .85rem; display: flex; gap: .72rem; align-items: center; }
        .sidebar .nav-link i { width: 18px; text-align: center; opacity: .95; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.12); transform: translateX(2px); transition: .15s; }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,.22);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.14);
            position: relative;
        }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 18px;
            border-radius: 999px;
            background: rgba(255,255,255,.95);
        }
        .content { flex: 1; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        @media (min-width: 992px) {
            .content { margin-left: 270px; }
        }
        .topbar { background: #fff; border-bottom: 1px solid rgba(0,0,0,.06); position: sticky; top: 0; z-index: 1020; }
        .card-soft { border: 0; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,.06); }
        .card-soft:hover { transform: translateY(-2px); transition: .15s; }
        .stat-card { background: linear-gradient(135deg, rgba(25,135,84,.95), rgba(40,167,69,.88)); color: #fff; }
        .badge-soft { border-radius: 999px; padding: .35rem .6rem; }
        .badge-aktif { background: rgba(25,135,84,.12); color: #198754; }
        .badge-panen { background: rgba(255,193,7,.18); color: #b58100; }
        .badge-selesai { background: rgba(13,110,253,.12); color: #0d6efd; }
        .table thead th { background: #f2f7f3; border-bottom: 0; }
        .sidebar-user {
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 16px;
            padding: 12px;
        }
        @media (max-width: 991.98px) {
            .app-shell { height: 100vh; }
            main { -webkit-overflow-scrolling: touch; }
        }
    </style>
    @stack('head')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar offcanvas-lg offcanvas-start p-3" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">
        <div class="sidebar-watermark">
            <div class="wm"><i class="bi bi-leaf-fill"></i></div>
        </div>

        <div class="sidebar-content">
            <div class="offcanvas-header d-lg-none px-0 pt-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="logo-mark">
                        <i class="bi bi-flower1"></i>
                    </div>
                    <div class="brand" id="appSidebarLabel">
                        <div>Tanam Pepaya</div>
                        <div class="small opacity-75 fw-semibold">Sistem Tanam & Panen</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Close"></button>
            </div>

            <div class="sidebar-header d-none d-lg-block">
                <div class="d-flex align-items-center gap-2">
                    <div class="logo-mark">
                        <i class="bi bi-flower1"></i>
                    </div>
                    <div class="brand">
                        <div>Tanam Pepaya</div>
                        <div class="small opacity-75 fw-semibold">Sistem Tanam & Panen</div>
                    </div>
                </div>
            </div>

            <nav class="nav nav-pills flex-column gap-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="text-label">Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('tanaman.*') ? 'active' : '' }}" href="{{ route('tanaman.index') }}">
                    <i class="bi bi-flower1"></i>
                    <span class="text-label">Data Tanaman</span>
                </a>
                <a class="nav-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}" href="{{ route('notifikasi.index') }}">
                    <i class="bi bi-whatsapp"></i>
                    <span class="text-label">Notifikasi</span>
                </a>
                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="text-label">Laporan</span>
                </a>
                @if(auth()->user()->role === 'admin')
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i>
                        <span class="text-label">Data User</span>
                    </a>
                @endif
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-circle"></i>
                    <span class="text-label">Profil</span>
                </a>
            </nav>

            <div class="mt-auto pt-3 border-top border-white border-opacity-25">
                <div class="sidebar-user">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                            <i class="bi bi-person-circle fs-4"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="small opacity-75">Login sebagai</div>
                            <div class="fw-semibold text-truncate">{{ auth()->user()->name }}</div>
                            <div class="small opacity-75 text-truncate">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="small opacity-75">Role</div>
                        <span class="badge bg-white bg-opacity-25 border border-white border-opacity-25">{{ strtoupper(auth()->user()->role) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div class="content">
        <div class="topbar px-3 py-2 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-success btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar" aria-controls="appSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="fw-semibold">@yield('page_title', 'Dashboard')</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>

        <main class="p-3 p-lg-4" style="overflow-y:auto; flex: 1;">
            @yield('content')
        </main>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    <div id="appToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="appToastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const msgSuccess = @json(session('success'));
        const msgError = @json(session('error'));
        const toastEl = document.getElementById('appToast');
        const bodyEl = document.getElementById('appToastBody');
        if (!toastEl || !bodyEl) return;

        let message = msgSuccess || msgError;
        if (!message) return;

        toastEl.classList.remove('text-bg-success', 'text-bg-danger');
        toastEl.classList.add(msgError ? 'text-bg-danger' : 'text-bg-success');
        bodyEl.textContent = message;
        new bootstrap.Toast(toastEl, { delay: 3500 }).show();
    })();
</script>
@stack('scripts')
</body>
</html>
