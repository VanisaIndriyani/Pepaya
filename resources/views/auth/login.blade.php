<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Tanam Pepaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --green: #198754; 
            --green-dark: #11623d;
            --bg-light: #f8fbf9;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }
        .login-container {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
        }
        .login-card {
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .login-hero {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green) 100%);
            padding: 60px;
            color: #fff;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-hero::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .login-hero::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            background: rgba(0,0,0,0.1);
            border-radius: 50%;
        }
        .hero-logo {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .hero-logo i { font-size: 32px; color: #fff; }
        .hero-title { font-weight: 800; font-size: 2.2rem; line-height: 1.1; margin-bottom: 16px; }
        .hero-text { font-size: 1.1rem; opacity: 0.9; font-weight: 500; line-height: 1.6; }
        
        .login-form-side { padding: 60px; }
        .form-header { margin-bottom: 40px; }
        .form-header h2 { font-weight: 800; color: #1a1a1a; margin-bottom: 8px; }
        .form-header p { color: #666; font-weight: 500; }
        
        .form-label { font-weight: 600; color: #444; font-size: 0.9rem; margin-bottom: 8px; }
        .input-group-custom {
            background: #f3f4f6;
            border: 2px solid transparent;
            border-radius: 16px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            transition: all 0.3s;
        }
        .input-group-custom:focus-within {
            background: #fff;
            border-color: var(--green);
            box-shadow: 0 0 0 4px rgba(25,135,84,0.1);
        }
        .input-group-custom i { color: #9ca3af; font-size: 1.2rem; }
        .input-group-custom input {
            background: transparent;
            border: 0;
            width: 100%;
            padding: 14px 12px;
            font-weight: 600;
            color: #1a1a1a;
            outline: none;
        }
        .input-group-custom input::placeholder { color: #9ca3af; }
        
        .btn-login {
            background: var(--green);
            color: #fff;
            border: 0;
            width: 100%;
            padding: 16px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 24px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(25,135,84,0.2);
        }
        .btn-login:hover {
            background: var(--green-dark);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(25,135,84,0.3);
            color: #fff;
        }
        
        .demo-accounts {
            margin-top: 32px;
            background: #f9fafb;
            border-radius: 20px;
            padding: 20px;
            border: 1px dashed #e5e7eb;
        }
        .demo-title { font-weight: 700; font-size: 0.85rem; color: #4b5563; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .demo-item { font-size: 0.8rem; color: #6b7280; margin-bottom: 4px; display: flex; justify-content: space-between; }
        .demo-item strong { color: #1f2937; }

        @media (max-width: 991px) {
            .login-hero { display: none; }
            .login-form-side { padding: 40px; }
            .login-container { max-width: 500px; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="row g-0">
            <!-- Hero Side -->
            <div class="col-lg-5 login-hero">
                <div class="hero-logo">
                    <i class="bi bi-flower1"></i>
                </div>
                <h1 class="hero-title">Optimalkan Hasil Panen Anda.</h1>
                <p class="hero-text">Sistem manajemen penanaman pepaya cerdas untuk membantu Anda memantau setiap tahap pertumbuhan secara real-time.</p>
                
                <div class="mt-5 pt-4 border-top border-white border-opacity-10 d-none d-lg-block">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-10 rounded-circle p-2"><i class="bi bi-shield-fill-check"></i></div>
                        <div class="small fw-medium">Keamanan Data Terjamin</div>
                    </div>
                </div>
            </div>
            
            <!-- Form Side -->
            <div class="col-lg-7 login-form-side">
                <div class="form-header">
                    <h2>Selamat Datang!</h2>
                    <p>Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3">
                        <i class="bi bi-exclamation-circle-fill fs-4"></i>
                        <div class="small fw-medium">{{ $errors->first() }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-group-custom">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <div class="input-group-custom">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="loginPassword" placeholder="Masukkan password" required>
                            <button type="button" class="btn border-0 p-0" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                 
                    <button type="submit" class="btn btn-login">
                        <span>Masuk ke Sistem</span>
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

              
                
                <p class="text-center mt-5 mb-0 small text-muted fw-medium">
                    &copy; {{ date('Y') }} Tanam Pepaya System. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const btn = document.getElementById('togglePassword');
        const input = document.getElementById('loginPassword');
        const icon = btn.querySelector('i');
        
        if (!btn || !input) return;
        
        btn.addEventListener('click', function () {
            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';
            icon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
        });
    })();
</script>
</body>
</html>