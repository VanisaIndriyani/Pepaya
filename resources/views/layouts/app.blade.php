<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tanam Pepaya')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --green: #198754; 
            --green2: #28a745; 
            --sidebar-width: 280px;
            --bg-light: #f8fbf9;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-light); 
            overflow-x: hidden; 
            color: #2d3436;
        }
        .app-shell { height: 100vh; display: flex; }
        .sidebar {
            background: linear-gradient(180deg, #11623d 0%, #198754 100%);
            color: #fff;
            --bs-offcanvas-width: var(--sidebar-width);
            box-shadow: 10px 0 30px rgba(0,0,0,.04);
            border-right: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (min-width: 992px) {
            body { overflow: hidden; }
            .sidebar {
                width: var(--sidebar-width);
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                overflow-y: auto;
                overflow-x: hidden;
            }
        }
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 999px; }
        .sidebar-content { position: relative; z-index: 1; min-height: 100%; display: flex; flex-direction: column; padding: 1.5rem 1rem; }
        
        .brand-section {
            padding: 0.5rem 0.5rem 2rem 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-box {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .logo-box i { font-size: 24px; color: #fff; }
        .brand-text { line-height: 1.2; }
        .brand-name { font-weight: 800; font-size: 1.15rem; letter-spacing: -0.02em; }
        .brand-sub { font-size: 0.75rem; opacity: 0.8; font-weight: 500; }

        .nav-section-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            opacity: 0.5;
            margin: 1.5rem 0 0.75rem 0.75rem;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            font-weight: 600;
            font-size: 0.925rem;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            margin-bottom: 4px;
        }
        .sidebar .nav-link i { font-size: 1.25rem; opacity: 0.8; transition: all 0.2s; }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar .nav-link:hover i { opacity: 1; }
        .sidebar .nav-link.active {
            background: #fff;
            color: var(--green);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .sidebar .nav-link.active i { opacity: 1; color: var(--green); }

        .content { flex: 1; height: 100vh; overflow: hidden; display: flex; flex-direction: column; }
        @media (min-width: 992px) {
            .content { margin-left: var(--sidebar-width); }
        }
        
        .topbar { 
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1020;
            padding: 0.75rem 1.5rem;
        }
        .card-soft { 
            border: 0; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-soft:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0,0,0,0.06); }
        
        .user-card-sidebar {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1rem;
            margin-top: auto;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.15);
            border: 0;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.25); color: #fff; }

        main { padding: 2rem; }
        @media (max-width: 768px) {
            main { padding: 1rem; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #dcdde1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #bdc3c7; }
    </style>
    @stack('head')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar offcanvas-lg offcanvas-start shadow-sm" tabindex="-1" id="appSidebar">
        <div class="sidebar-content">
            <div class="brand-section">
                <div class="logo-box">
                    <i class="bi bi-flower1"></i>
                </div>
                <div class="brand-text">
                    <div class="brand-name">Tanam Pepaya</div>
                    <div class="brand-sub text-uppercase">Management System</div>
                </div>
                <button type="button" class="btn-close btn-close-white d-lg-none ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar"></button>
            </div>

            <div class="nav-section-label">Main Menu</div>
            <nav class="nav nav-pills flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('tanaman.*') ? 'active' : '' }}" href="{{ route('tanaman.index') }}">
                    <i class="bi bi-tree-fill"></i>
                    <span>Data Tanaman</span>
                </a>
                <a class="nav-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}" href="{{ route('notifikasi.index') }}">
                    <i class="bi bi-whatsapp"></i>
                    <span>Notifikasi</span>
                </a>
                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Laporan</span>
                </a>

                <div class="nav-section-label">Settings</div>
                @if(auth()->user()->role === 'admin')
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Manajemen User</span>
                    </a>
                @endif
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Profil Saya</span>
                </a>
            </nav>

            <div class="user-card-sidebar mt-5">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; color: var(--green);">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-truncate" style="font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                        <div class="small opacity-75 text-truncate" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="content">
        <header class="topbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light btn-sm d-lg-none rounded-3 shadow-sm border" type="button" data-bs-toggle="offcanvas" data-bs-target="#appSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="page-info">
                    <h5 class="fw-bold mb-0" style="letter-spacing: -0.01em;">@yield('page_title', 'Dashboard')</h5>
                    <div class="small text-muted d-none d-sm-block">{{ date('l, d F Y') }}</div>
                </div>
            </div>
            
            <div class="topbar-actions d-flex align-items-center gap-2">
                <div class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold border border-success border-opacity-10">
                    <i class="bi bi-shield-fill-check me-1"></i>
                    {{ strtoupper(auth()->user()->role) }}
                </div>
            </div>
        </header>

        <main style="overflow-y:auto; flex: 1;">
            <div class="container-fluid">
                @yield('content')
            </div>
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
