<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — UIO Rumah Makan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: var(--uio-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 18px;
            border: 1px solid var(--uio-border);
            box-shadow: 0 8px 32px rgba(124,158,135,0.15);
            background-color: var(--uio-white);
            overflow: hidden;
        }

        .login-header {
            background-color: var(--uio-primary);
            padding: 2rem;
            text-align: center;
        }

        .login-header h1 {
            color: var(--uio-white);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0.5rem 0 0.2rem;
        }

        .login-header p {
            color: rgba(255,255,255,0.80);
            font-size: 0.85rem;
            margin: 0;
        }

        .login-icon {
            width: 64px;
            height: 64px;
            background-color: rgba(255,255,255,0.20);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.8rem;
            font-size: 2rem;
            color: var(--uio-white);
        }

        .login-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-card">

        {{-- Header --}}
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-shop"></i>
            </div>
            <h1>UIO Rumah Makan</h1>
            <p>Sistem Informasi Manajemen</p>
        </div>

        {{-- Body --}}
        <div class="login-body">

            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-person"></i> Username
                    </label>
                    <input type="text"
                           name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           placeholder="Masukkan username"
                           autofocus required>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Masukkan password"
                           required>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.88rem;">
                            Ingat saya
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>

            </form>

        </div>
    </div>
</body>
</html>
