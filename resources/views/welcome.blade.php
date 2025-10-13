<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Metland Recruitment - Login</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/logo.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/applicants/logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('storage/applicants/logo.png') }}?v=2" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/logo.png') }}?v=2">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #009290;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #e6dfdfff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
            width: 100%;
            height: 100%;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            width: 100%;
            padding: 0;
            margin: 0;
        }

        /* Decorative background elements */
        body::before {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0,146,144,0.08) 0%, transparent 70%);
            border-radius: 50%;
            top: -250px;
            right: -250px;
            z-index: 0;
            animation: float 20s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(52,152,219,0.06) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -200px;
            left: -200px;
            z-index: 0;
            animation: float 15s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -30px) scale(1.05); }
            50% { transform: translate(-20px, 20px) scale(0.95); }
            75% { transform: translate(20px, 30px) scale(1.02); }
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
            margin: 0 auto;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .col-md-6, .col-lg-4 {
            padding-left: 0;
            padding-right: 0;
        }

        .login-container {
            background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 30px;
            box-shadow: 
                0 30px 60px rgba(0,0,0,0.08),
                0 10px 20px rgba(0,0,0,0.04),
                inset 0 1px 0 rgba(255,255,255,0.9);
            overflow: hidden;
            max-width: 440px;
            width: 100%;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0,146,144,0.1);
            animation: slideUp 0.6s ease-out;
            margin: 20px auto;
        }
        
        /* Glassmorphism overlay */
        .login-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(0,146,144,0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(52,152,219,0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Decorative accent line */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--primary-color));
            background-size: 200% 100%;
            animation: gradientMove 3s ease infinite;
            z-index: 2;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Animated border glow */
        .login-container {
            position: relative;
        }

        .login-container:hover::before {
            box-shadow: 0 0 20px rgba(0,146,144,0.5);
        }

        .login-header {
            background: transparent;
            color: var(--primary-color);
            padding: 2.5rem 2rem 1.5rem 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        /* Floating particles effect */
        .login-header::before,
        .login-header::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,146,144,0.15), transparent);
            animation: float 6s ease-in-out infinite;
        }

        .login-header::before {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .login-header::after {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 15%;
            animation-delay: 3s;
        }

        .login-body {
            padding: 1.5rem 2.5rem 2.5rem 2.5rem;
            background: transparent;
            position: relative;
            overflow: hidden;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.85rem 1.2rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.15);
            background: #ffffff;
        }

        .btn-primary {
            background: linear-gradient(135deg, #009290 0%, #007c7a 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,146,144,0.2);
            width: 100%;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00a8a6 0%, #008f8d 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,146,144,0.3);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.25rem rgba(0,146,144,0.25);
            outline: none;
        }

        .btn-outline-primary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            background: transparent;
            border-radius: 12px;
            padding: 0.85rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .btn-outline-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: var(--secondary-color);
            transition: width 0.4s ease, height 0.4s ease, top 0.4s ease, left 0.4s ease;
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        .btn-outline-primary:hover::before {
            width: 300%;
            height: 300%;
        }

        .btn-outline-primary:hover {
            color: #ffffff;
            border-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52,152,219,0.25);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            animation: fadeIn 0.8s ease-out 0.2s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo img {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
            max-width: 100%;
            height: auto;
        }

        .logo:hover img {
            transform: scale(1.05);
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
            border-radius: 12px;
            border: none;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .text-muted {
            font-size: 0.9rem;
        }

        .d-grid {
            animation: fadeIn 0.8s ease-out 0.4s both;
        }

        .btn {
            position: relative;
            z-index: 1;
        }

        .text-center h4 {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .bi-check-circle-fill {
            animation: scaleIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Icon styling */
        .btn i {
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: translateX(3px);
        }

        .btn-outline-primary:hover i {
            transform: rotate(360deg);
        }

        /* Card hover effect */
        .login-container:hover {
            box-shadow: 
                0 40px 80px rgba(0,0,0,0.12),
                0 15px 35px rgba(0,146,144,0.08),
                inset 0 1px 0 rgba(255,255,255,0.9);
            transform: translateY(-5px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-container {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Shimmer effect on hover */
        .login-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(0,146,144,0.05),
                transparent
            );
            transition: left 0.8s ease;
            z-index: 0;
        }

        .login-container:hover .login-body::before {
            left: 200%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body::before {
                width: 300px;
                height: 300px;
                top: -150px;
                right: -150px;
            }

            body::after {
                width: 250px;
                height: 250px;
                bottom: -125px;
                left: -125px;
            }

            .login-container {
                border-radius: 20px;
                margin: 15px;
                max-width: 100%;
            }

            .login-header {
                padding: 2rem 1.5rem 1rem 1.5rem;
            }

            .login-body {
                padding: 1rem 1.5rem 2rem 1.5rem;
            }

            .logo {
                font-size: 1.25rem;
                gap: 0.4rem;
            }

            .logo img {
                height: 45px;
            }

            .text-center h4 {
                font-size: 1.25rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.75rem 1.5rem;
                font-size: 0.95rem;
            }

            .text-muted {
                font-size: 0.85rem;
            }

            .bi-check-circle-fill {
                font-size: 2.5rem !important;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .login-container {
                border-radius: 15px;
                margin: 10px;
            }

            .login-header {
                padding: 1.5rem 1rem 0.75rem 1rem;
            }

            .login-body {
                padding: 1rem 1.25rem 1.5rem 1.25rem;
            }

            .logo {
                font-size: 1.1rem;
            }

            .logo img {
                height: 40px;
            }

            .text-center h4 {
                font-size: 1.1rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.7rem 1.25rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .bi-check-circle-fill {
                font-size: 2rem !important;
            }

            .login-header::before {
                width: 60px;
                height: 60px;
            }

            .login-header::after {
                width: 45px;
                height: 45px;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                border-radius: 12px;
                margin: 8px;
            }

            .login-header {
                padding: 1.25rem 0.75rem 0.5rem 0.75rem;
            }

            .login-body {
                padding: 0.75rem 1rem 1.25rem 1rem;
            }

            .logo {
                font-size: 1rem;
            }

            .logo img {
                height: 35px;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
            }
        }

        /* Prevent horizontal scroll */
        @media (max-width: 991px) {
            .login-container:hover {
                transform: translateY(-2px);
            }
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