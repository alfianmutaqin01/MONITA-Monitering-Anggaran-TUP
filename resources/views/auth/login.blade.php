<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <title>Login | MONITA</title>

    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon" />

    <!-- Google Font & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('berry/assets/css/style-preset.css') }}" />

    <style>
        :root {
            --primary: #951923;
            --light-bg: #f4f6f9;
        }

        body {
            background: var(--light-bg);
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
        }

        /* ===== Background Animated Logos ===== */
        .bg-animated {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: #e2e2e2;
        }

        .floating-logo {
            position: absolute;
            opacity: 0.06;
            filter: drop-shadow(0 0 8px rgba(149, 25, 35, 0.2));
            animation: floatRandom infinite ease-in-out, glowPulse 6s infinite ease-in-out;
        }

        @keyframes floatRandom {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            25% {
                transform: translate(40px, -30px) rotate(10deg) scale(1.05);
            }

            50% {
                transform: translate(-50px, 40px) rotate(-10deg) scale(1.08);
            }

            75% {
                transform: translate(30px, 20px) rotate(6deg) scale(1.03);
            }

            100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.05;
                filter: drop-shadow(0 0 5px rgba(149, 25, 35, 0.2));
            }

            50% {
                opacity: 0.1;
                filter: drop-shadow(0 0 15px rgba(149, 25, 35, 0.35));
            }
        }

        /* ===== Auth Wrapper ===== */
        .auth-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .auth-bg {
            background: url('{{ asset('images/login.png') }}') center/cover no-repeat;
            position: relative;
            min-height: 500px;
        }

        .auth-bg .overlay-text {
            position: absolute;
            bottom: 0;
            left: 0;
            padding: 25px;
            background: rgba(0, 0, 0, 0.45);
            border-top-right-radius: 10px;
            color: #fff;
        }

        .auth-bg .overlay-text h3 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .auth-bg .overlay-text small {
            font-size: 0.95rem;
        }

        .brand-logo {
            width: 180px;
            margin-bottom: 10px;
        }

        .auth-header h2 {
            font-weight: 700;
            color: var(--primary);
        }

        .btn-login {
            background: var(--primary);
            color: #ffffff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 12px;
        }

        .btn-login:hover {
            background: #fff;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .form-floating>label {
            color: #666;
        }

        .alert-danger {
            border-radius: 8px;
            font-size: 0.9rem;
        }

        @media (max-width: 992px) {
            .auth-bg {
                display: none;
            }

            .card {
                width: 90%;
            }
        }

        /* === Fix: Hilangkan animasi naik saat klik tombol login === */
        .card,
        .card:hover,
        .card:focus-within {
            transform: none !important;
            transition: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        }

        .btn-login:active,
        .btn-login:focus {
            transform: none !important;
            box-shadow: none !important;
        }
    </style>
</head>

<body>
    <!-- ===== Background Animated Logos ===== -->
    <div class="bg-animated">
        @for ($i = 0; $i < 14; $i++)
            <img src="{{ asset('images/icon.png') }}" alt="Floating Logo" class="floating-logo" style="
                                top: {{ rand(5, 90) }}%;
                                left: {{ rand(5, 90) }}%;
                                animation-delay: {{ rand(0, 15) / 10 }}s;
                                animation-duration: {{ rand(15, 35) }}s;
                                width: {{ rand(60, 160) }}px;
                            ">
        @endfor
    </div>

    <!-- ===== Login Card ===== -->
    <div class="auth-wrapper">
        <div class="card overflow-hidden" style="max-width: 900px; width: 100%;">
            <div class="row g-0">
                <!-- Left Background Image -->
                <div class="col-lg-6 d-none d-lg-block auth-bg">
                    {{-- <div class="overlay-text">
                        <h3>MONITA</h3>
                        <small>Monitoring Anggaran Telkom University Purwokerto</small>
                    </div> --}}
                </div>

                <!-- Right Form Section -->
                <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center bg-white">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Telkom University" class="brand-logo">
                        <h2 class="auth-header mb-1">Selamat Datang</h2>
                        <p class="text-muted mb-4">Masukkan akun Anda untuk melanjutkan</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required>
                            <label for="username"><i class="ti ti-user me-1"></i> Username</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                            <label for="password"><i class="ti ti-lock me-1"></i> Password</label>
                        </div>

                        <button type="submit" class="btn btn-login w-100">
                            <i class="ti ti-login me-1"></i> Masuk ke MONITA
                        </button>
                    </form>

                    <div class="text-center mt-4 text-muted">
                        <small>© {{ date('Y') }} MONITA — Telkom University Purwokerto</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('berry/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('berry/assets/js/plugins/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
</body>

</html>