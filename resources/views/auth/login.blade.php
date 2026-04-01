<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Tanam Pepaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --green: #198754; --green2: #28a745; }
        body {
            background:
                radial-gradient(900px circle at 15% 12%, rgba(25,135,84,.18), transparent 55%),
                radial-gradient(900px circle at 92% 30%, rgba(40,167,69,.14), transparent 52%),
                #ffffff;
        }
        .login-shell { min-height: 100vh; display: flex; align-items: center; padding: 28px 0; }
        .login-card { border: 0; border-radius: 22px; box-shadow: 0 22px 55px rgba(0,0,0,.10); overflow: hidden; }
        .login-left {
            background: linear-gradient(155deg, rgba(25,135,84,1), rgba(21,115,71,1));
            color: #fff;
            position: relative;
        }
        .login-left::before {
            content: '';
            position: absolute;
            inset: -30% -30% auto auto;
            width: 420px;
            height: 420px;
            border-radius: 999px;
            background: rgba(255,255,255,.10);
            transform: rotate(10deg);
        }
        .login-left::after {
            content: '';
            position: absolute;
            inset: auto auto -35% -35%;
            width: 520px;
            height: 520px;
            border-radius: 999px;
            background: rgba(0,0,0,.10);
        }
        .login-left > * { position: relative; z-index: 1; }
        .brand-badge { width: 58px; height: 58px; border-radius: 18px; background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.18); color: #fff; box-shadow: 0 14px 26px rgba(0,0,0,.18); }
        .brand-title { font-weight: 900; letter-spacing: .2px; line-height: 1.05; }
        .brand-sub { opacity: .8; font-weight: 600; }
        .btn-green { background: var(--green); border-color: var(--green); }
        .btn-green:hover { background: #157347; border-color: #157347; }
        .form-control, .form-select { border-radius: 14px; padding: .7rem .85rem; }
        .btn { border-radius: 14px; padding: .7rem .9rem; }
        .help-link { color: #198754; text-decoration: none; }
        .help-link:hover { text-decoration: underline; }
        .demo-card { background: rgba(25,135,84,.06); border: 1px solid rgba(25,135,84,.12); border-radius: 16px; padding: 12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="login-shell">
        <div class="row justify-content-center w-100">
            <div class="col-12 col-lg-9 col-xl-8">
                <div class="card login-card">
                    <div class="row g-0">
                        <div class="col-lg-5 login-left p-4 p-lg-5 d-flex align-items-center justify-content-center text-center">
                            <div>
                                <div class="brand-badge d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="bi bi-flower1 fs-3"></i>
                                </div>
                                <div class="h4 mb-1 brand-title">Tanam Pepaya</div>
                                <div class="small brand-sub">Sistem Tanam &amp; Panen</div>
                            </div>
                        </div>
                        <div class="col-lg-7 p-4 p-lg-5 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="h5 mb-0 fw-bold">Masuk</div>
                                </div>
                                <div class="text-success d-none d-lg-block"><i class="bi bi-shield-check"></i></div>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.process') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" style="border-radius:14px 0 0 14px;">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus style="border-radius:0 14px 14px 0;">
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" style="border-radius:14px 0 0 14px;">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" name="password" id="loginPassword" class="form-control" required style="border-radius:0;">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-radius:0 14px 14px 0;">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-green text-white w-100 mt-3">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                                </button>
                            </form>

                            <div class="mt-3">
                                <a class="help-link small" data-bs-toggle="collapse" href="#demoAkun" role="button" aria-expanded="false" aria-controls="demoAkun">
                                    Lihat akun demo
                                </a>
                                <div class="collapse mt-2" id="demoAkun">
                                    <div class="demo-card small text-muted">
                                        <div><span class="fw-semibold">Admin</span>: admin@gmail.com / password</div>
                                        <div><span class="fw-semibold">User</span>: user@gmail.com / password</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const btn = document.getElementById('togglePassword');
        const input = document.getElementById('loginPassword');
        if (!btn || !input) return;
        btn.addEventListener('click', function () {
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
            btn.innerHTML = isText ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });
    })();
</script>
</body>
</html>
