<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon" />
    <title>MONITA Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #951923;
            --secondary-color: #d32f2f;
            --light-color: #f8f9fa;
            --dark-color: #1e1e2d;
            --border-radius: 8px;
        }

        body {
            background-color: var(--light-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .login-container {
            width: 85%;
            height: 80vh;
            display: flex;
            border-radius: var(--border-radius);
            overflow: hidden;
            background-color: #f8f9fb;
        }

        /* kiri */
        .login-image {
            flex: 3;
            background: url('{{ asset('images/login.png') }}') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        .login-image .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            color: #ffffff;
            padding: 20px;
        }

        .login-image .overlay h1 {

            font-weight: 700;
            font-size: 5rem;
            margin-bottom: 0.5rem;
        }

        .login-image .overlay p {
            margin: 0.2rem 0;
        }

        /* Form login */
        /* kanan */
        .login-form-container {
            flex: 1;
            padding: 0px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form-container .logo img {
            width: 220px;
            margin-bottom: 20px;
        }

        .login-form-container h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: var(--border-radius);
            border-color: rgb(131, 131, 131);
            padding: 12px;
        }

        .btn-login {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: var(--border-radius);
            padding: 12px;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: var(--secondary-color);
        }

        /* Alert */
        .alert-danger {
            border-radius: var(--border-radius);
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-image {
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Bagian kiri: gambar dan overlay -->
        <div class="login-image col-8">
            <div class="overlay">
                <h1>MONITA</h1>
                <p>Monitoring Anggaran</p>
                <p>Telkom University Purwokerto</p>
            </div>
        </div>

        <!-- Bagian kanan: form login -->
        <div class="login-form-container col-4">
            <div class="logo text-center">
                <img src="{{ asset('images/logo.png') }}" alt="Telkom University Logo">
            </div>
            <h2 class="text-center">Selamat Datang</h2>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login w-100">Login</button>
            </form>
        </div>
    </div>
</body>

</html>