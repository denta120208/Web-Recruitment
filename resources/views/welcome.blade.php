<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Metland Recruitment - Login</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/Logo_Metland.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/Logo_Metland.png') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #009290; /* Metland text color requested (black) */
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #e6dfdfff;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff; /* ensure page background is white */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #eeededff; /* slightly darker off-white so box stands out from page white */
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: transparent; /* let container color show through */
            color: var(--primary-color);
            padding: 1.75rem 2rem 0.75rem 2rem;
            text-align: center;
        }

        .login-body {
            padding: 1.5rem 2rem 2rem 2rem;
            background: transparent; /* let container color show through */
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: #009290; /* requested color */
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background: #007c73; /* slightly darker on hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(0,146,144,0.25);
            outline: none;
        }

        .btn-outline-primary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .25rem;
        }

        .subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--secondary-color);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .text-muted {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container">
                    <div class="login-header text-center">
                        <div class="logo">
                            <img src="{{ asset('storage/applicants/Logo_Metland.png') }}" alt="Metland Logo" style="height:50px; object-fit:contain;">
                            <span style="color:var(--primary-color); font-size:1.25rem;">Metland Recruitment</span>
                        </div>
                        
                    </div>
                    
                    <div class="login-body">
                        @auth
                            <div class="text-center mb-4">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h4 class="text-success">Selamat Datang!</h4>
                                <p class="text-muted">Anda sudah login sebagai {{ Auth::user()->name }}</p>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('applicant.index') }}" class="btn btn-primary">
                                    <i class="bi bi-house-fill me-2"></i>Ke Beranda
                                </a>
                                <a href="{{ route('applicant.create') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-person-plus-fill me-2"></i>Isi Data Diri
                                </a>
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <h4 class="text-primary">Silakan Login</h4>
                                <p class="text-muted">Masuk untuk mengakses form data diri</p>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-person-plus-fill me-2"></i>Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
