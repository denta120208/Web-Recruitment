<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar - Metland Recruitment</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/logo.png') }}">
    
    <!-- Bootstrap CSS -->
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
            --light-bg: #f8f9fa;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            padding: 1.5rem 0.5rem;
            position: relative;
            overflow-x: hidden;
            width: 100%;
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
            padding: 0 0.75rem;
            margin: 0 auto;
        }

        .register-container {
            background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 30px;
            box-shadow: 
                0 30px 60px rgba(0,0,0,0.08),
                0 10px 20px rgba(0,0,0,0.04),
                inset 0 1px 0 rgba(255,255,255,0.9);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(0,146,144,0.1);
            animation: slideUp 0.6s ease-out;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 auto;
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

        /* Glassmorphism overlay */
        .register-container::after {
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

        /* Animated border glow */
        .register-container::before {
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

        .register-container:hover::before {
            box-shadow: 0 0 20px rgba(0,146,144,0.5);
        }

        .register-container:hover {
            box-shadow: 
                0 40px 80px rgba(0,0,0,0.12),
                0 15px 35px rgba(0,146,144,0.08),
                inset 0 1px 0 rgba(255,255,255,0.9);
            transform: translateY(-5px);
        }

        .register-header {
            background: linear-gradient(135deg, #009290 0%, #007c7a 100%);
            color: white;
            padding: 2rem 2rem 1.25rem 2rem;
            text-align: center;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Floating particles in header */
        .register-header::before,
        .register-header::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent);
            animation: floatParticle 6s ease-in-out infinite;
        }

        .register-header::before {
            width: 100px;
            height: 100px;
            top: -20px;
            left: -20px;
            animation-delay: 0s;
        }

        .register-header::after {
            width: 80px;
            height: 80px;
            bottom: -20px;
            right: -20px;
            animation-delay: 3s;
        }

        @keyframes floatParticle {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.3; }
            50% { transform: translate(20px, 20px) scale(1.2); opacity: 0.6; }
        }

        .register-body {
            padding: 1.75rem 2.5rem 2.5rem 2.5rem;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Shimmer effect on hover */
        .register-body::before {
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

        .register-container:hover .register-body::before {
            left: 200%;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.85rem 1.2rem;
            transition: all 0.3s ease;
            background: #ffffff;
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.15);
            background: #ffffff;
            transform: translateY(-2px);
        }

        .form-floating { 
            position: relative;
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .form-floating:nth-child(1) { animation-delay: 0.1s; }
        .form-floating:nth-child(2) { animation-delay: 0.2s; }
        .form-floating:nth-child(3) { animation-delay: 0.3s; }
        .form-floating:nth-child(4) { animation-delay: 0.4s; }
        .form-check { animation: fadeInUp 0.6s ease-out 0.5s backwards; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toggle-password-btn {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            padding: 6px;
            border-radius: 10px;
            height: 42px;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 100;
            pointer-events: auto;
        }

        .toggle-password-btn:hover {
            background: rgba(52,152,219,0.1);
            border-color: var(--secondary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password-btn i { 
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .toggle-password-btn:hover i {
            transform: scale(1.1);
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
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            animation: fadeIn 0.8s ease-out 0.2s both;
            position: relative;
            z-index: 1;
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

        .subtitle {
            opacity: 0.95;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--secondary-color);
        }

        .alert {
            border-radius: 12px;
            border: none;
            animation: slideDown 0.4s ease-out;
            position: relative;
            z-index: 1;
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

        .password-strength {
            height: 5px;
            background: #e9ecef;
            border-radius: 3px;
            margin-top: 0.5rem;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 3px;
        }

        .strength-weak { 
            background: linear-gradient(90deg, #dc3545, #c82333);
            width: 25%;
            box-shadow: 0 0 8px rgba(220, 53, 69, 0.4);
        }
        .strength-fair { 
            background: linear-gradient(90deg, #ffc107, #e0a800);
            width: 50%;
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.4);
        }
        .strength-good { 
            background: linear-gradient(90deg, #17a2b8, #138496);
            width: 75%;
            box-shadow: 0 0 8px rgba(23, 162, 184, 0.4);
        }
        .strength-strong { 
            background: linear-gradient(90deg, #28a745, #218838);
            width: 100%;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.4);
        }

        .form-check-input {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .form-check-input:hover {
            border-color: var(--secondary-color);
            transform: scale(1.1);
        }

        .form-check-input:checked {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .form-check-label {
            cursor: pointer;
        }

        .d-grid {
            animation: fadeInUp 0.6s ease-out 0.5s backwards;
        }

        .btn i {
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: translateX(3px);
        }

        .btn-outline-primary:hover i {
            transform: rotate(360deg);
        }

        a {
            transition: all 0.3s ease;
        }

        a:hover {
            transform: translateX(3px);
        }

        .form-floating > label {
            z-index: 3;
            pointer-events: none;
            color: #6c757d;
        }

        .form-floating > .form-control {
            z-index: 2;
            background-color: #ffffff !important;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #009290 0%, #007c7a 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
        }

        .modal-footer {
            border: none;
            padding: 1.5rem;
        }

        .btn-secondary {
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        small {
            position: relative;
            z-index: 1;
        }

        /* ========== RESPONSIVE STYLES ========== */
        
        /* Tablet & Small Desktop */
        @media (max-width: 991px) {
            .register-container:hover {
                transform: translateY(-2px);
            }
        }

        /* Tablet */
        @media (max-width: 768px) {
            body {
                padding: 1.5rem 0.5rem;
                align-items: flex-start;
            }

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

            .register-container {
                border-radius: 20px;
                max-width: 100%;
            }

            .register-header {
                padding: 1.75rem 1.5rem 1rem 1.5rem;
            }

            .register-body {
                padding: 1.5rem 1.75rem 2rem 1.75rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 0.85rem;
            }

            .form-control {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.75rem 1.5rem;
                font-size: 0.95rem;
            }

            .toggle-password-btn {
                height: 38px;
                width: 38px;
            }

            small {
                font-size: 0.8rem;
            }
        }

        /* Mobile Large */
        @media (max-width: 576px) {
            body {
                padding: 1rem 0.5rem;
            }

            .container {
                padding: 0 0.5rem;
            }

            .register-container {
                border-radius: 18px;
            }

            .register-header {
                padding: 1.5rem 1.25rem 0.9rem 1.25rem;
            }

            .register-body {
                padding: 1.25rem 1.5rem 1.75rem 1.5rem;
            }

            .logo {
                font-size: 1.35rem;
            }

            .logo i {
                font-size: 1.2rem;
            }

            .subtitle {
                font-size: 0.8rem;
            }

            .form-control {
                padding: 0.7rem 0.9rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.7rem 1.25rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .toggle-password-btn {
                height: 36px;
                width: 36px;
                right: 10px;
            }

            .toggle-password-btn i {
                font-size: 0.9rem;
            }

            .form-floating > label {
                font-size: 0.9rem;
            }

            .text-muted {
                font-size: 0.85rem;
            }

            .alert {
                font-size: 0.85rem;
                padding: 0.75rem;
            }

            small {
                font-size: 0.75rem;
            }

            .form-check-label {
                font-size: 0.85rem;
            }

            .password-strength {
                height: 4px;
            }

            .register-header::before {
                width: 70px;
                height: 70px;
            }

            .register-header::after {
                width: 60px;
                height: 60px;
            }
        }

        /* Mobile Medium */
        @media (max-width: 400px) {
            body {
                padding: 0.75rem 0.25rem;
            }

            .container {
                padding: 0 0.25rem;
            }

            .register-container {
                border-radius: 15px;
            }

            .register-header {
                padding: 1.25rem 1rem 0.75rem 1rem;
            }

            .register-body {
                padding: 1rem 1.25rem 1.5rem 1.25rem;
            }

            .logo {
                font-size: 1.25rem;
            }

            .logo i {
                font-size: 1.1rem;
            }

            .subtitle {
                font-size: 0.75rem;
            }

            .form-control {
                padding: 0.65rem 0.85rem;
                font-size: 0.875rem;
                border-radius: 9px;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.65rem 1rem;
                font-size: 0.875rem;
                border-radius: 9px;
            }

            .toggle-password-btn {
                height: 34px;
                width: 34px;
                right: 8px;
            }

            .toggle-password-btn i {
                font-size: 0.85rem;
            }

            .form-floating > label {
                font-size: 0.85rem;
            }

            small {
                font-size: 0.7rem;
            }

            .form-check-label {
                font-size: 0.8rem;
            }

            .register-header::before {
                width: 60px;
                height: 60px;
            }

            .register-header::after {
                width: 50px;
                height: 50px;
            }
        }

        /* Mobile Small */
        @media (max-width: 360px) {
            body {
                padding: 0.5rem 0.25rem;
            }

            .register-container {
                border-radius: 12px;
            }

            .register-header {
                padding: 1rem 0.85rem 0.65rem 0.85rem;
            }

            .register-body {
                padding: 0.85rem 1rem 1.25rem 1rem;
            }

            .logo {
                font-size: 1.15rem;
            }

            .logo i {
                font-size: 1rem;
            }

            .subtitle {
                font-size: 0.7rem;
            }

            .form-control {
                padding: 0.6rem 0.75rem;
                font-size: 0.85rem;
                border-radius: 8px;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.6rem 0.9rem;
                font-size: 0.85rem;
                border-radius: 8px;
            }

            .toggle-password-btn {
                height: 32px;
                width: 32px;
                right: 6px;
            }

            .toggle-password-btn i {
                font-size: 0.8rem;
            }

            .form-floating > label {
                font-size: 0.8rem;
            }

            small {
                font-size: 0.65rem;
            }

            .form-check-label {
                font-size: 0.75rem;
            }
        }

        /* Height responsive for short screens */
        @media (max-height: 800px) {
            body {
                align-items: flex-start;
                padding-top: 1rem;
            }

            .register-header {
                padding-top: 1.5rem;
                padding-bottom: 1rem;
            }

            .register-body {
                padding-top: 1.25rem;
                padding-bottom: 1.75rem;
            }

            .form-floating {
                margin-bottom: 0.85rem;
            }
        }

        @media (max-height: 700px) {
            body {
                padding: 0.75rem 0.5rem;
            }

            .register-header {
                padding: 1.25rem 1.5rem 0.85rem 1.5rem;
            }

            .register-body {
                padding: 1rem 1.5rem 1.5rem 1.5rem;
            }

            .form-floating {
                margin-bottom: 0.75rem;
            }

            .logo {
                font-size: 1.4rem;
            }
        }

        @media (max-height: 650px) and (max-width: 576px) {
            body {
                padding: 0.5rem 0.25rem;
            }

            .register-header {
                padding: 1rem 1.25rem 0.75rem 1.25rem;
            }

            .register-body {
                padding: 0.85rem 1.25rem 1.25rem 1.25rem;
            }

            .form-floating {
                margin-bottom: 0.65rem;
            }
        }
    </style>
</head>
<body oncontextmenu="return false">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="register-container">
                    <div class="register-header">
                        <div class="logo">
                            <i class="bi bi-person-plus-fill me-2"></i>
                            Daftar Akun
                        </div>
                        <div class="subtitle">Buat akun untuk mengakses form data diri</div>
                    </div>
                    
                    <div class="register-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" placeholder="Nama Lengkap" 
                                       value="{{ old('name') }}" required autofocus>
                                <label for="name">Nama Lengkap</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="Email" 
                                       value="{{ old('email') }}" required>
                                <label for="email">Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       id="date_of_birth" name="date_of_birth" placeholder="Tanggal Lahir"
                                       value="{{ old('date_of_birth') }}" required>
                                <label for="date_of_birth">Tanggal Lahir *</label>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Password" required
                                       pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^()\[\]{}<>~`_+=\\|;:\\"'\/,.-]).{8,}"
                                       title="Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial">
                                <label for="password">Password</label>
                                <button type="button" id="toggleRegPassword" class="toggle-password-btn" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="toggleRegPasswordIcon"></i>
                                </button>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="strengthBar"></div>
                                </div>
                                <small class="text-muted">Password minimal 8 karakter, harus berisi huruf besar, huruf kecil, angka, dan simbol.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <button type="button" id="toggleRegConfirm" class="toggle-password-btn" aria-label="Toggle confirm password visibility">
                                    <i class="bi bi-eye" id="toggleRegConfirmIcon"></i>
                                </button>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="acceptTerms" name="accepted_terms" required>
                                <label class="form-check-label small ms-2" for="acceptTerms">
                                    Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat &amp; Ketentuan Pengelolaan Data</a> yang berlaku.
                                </label>
                                <div class="invalid-feedback">Anda harus menyetujui syarat & ketentuan untuk melanjutkan.</div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-0">Sudah punya akun?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary mt-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </a>
                        </div>

                        <div class="text-center mt-3">
                            <a href="/" class="text-muted text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Syarat &amp; Ketentuan Pengelolaan Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p> 1.	Pendahuluan
Selamat datang di situs Metland Career (selanjutnya disebut dengan “Situs”). Situs ini merupakan milik dan dikelola oleh PT Metropolitan Land Tbk (selanjutnya disebut “Perusahaan”). Dengan mengakses, mendaftar dan menggunakan, maka Anda dianggap telah membaca, memahami, dan secara sadar dan tanpa paksaan menyetujui Syarat dan Ketentuan Situs (selanjutnya disebut “Syarat dan Ketentuan”) ini. Perusahaan mengumpulkan dan menggunakan informasi pribadi Anda sesuai dengan Kebijakan Privasi Perusahaan.

2.	Membuat Akun
Situs ini terbuka untuk individu yang secara hukum dianggap dewasa (Ketentuan usia minimal 18 tahun dan kapasitas hukum untuk mengadakan perjanjian di Indonesia sudah jelas dan sesuai dengan hukum yang berlaku untuk menandatangani perjanjian. Anda menyatakan dan menjamin bahwa Anda adalah individu yang secara hukum berhak dan cakap untuk mengadakan dan mengikatkan diri dalam perjanjian berdasarkan hukum Negara Republik Indonesia. Apabila ketentuan tersebut tidak terpenuhi, Perusahaan berhak berdasarkan hukum untuk membatalkan setiap perjanjian yang dibuat dengan Anda. Anda selanjutnya menyatakan dan menjamin bahwa Anda memiliki hak, wewenang dan kapasitas untuk menggunakan Situs.
Alamat email individu yang valid diperlukan untuk bergabung dengan Situs. Dua atau lebih individu tidak boleh menggunakan alamat email yang sama. Dengan mendaftar, Anda setuju untuk menerima komunikasi elektronik yang berkaitan dengan pengoperasian, termasuk pesan informasi, informasi mengenai pengoperasian akun Anda. Anda kapan saja dapat berhenti menggunakan Situs ini, termasuk mengajukan permintaan penghapusan akun (apabila Anda telah melakukan pendaftaran sebagai pengguna Situs). Mohon diperhatikan bahwa penggunaan Situs tunduk pada Ketentuan yang Perusahaan keluarkan dan/atau perbaharui dari waktu ke waktu.
Untuk mendaftar dalam Situs, Anda harus membuat akun Anda dengan mengisi informasi yang diperlukan dalam formulir pendaftaran, termasuk nama pengguna. Anda menyatakan bahwa informasi yang diberikan dalam formulir pendaftaran adalah benar dan lengkap. Perusahaan tidak bertanggung jawab jika terdapat kesalahan atau informasi yang tidak benar/valid yang Anda berikan. Proses pendaftaran/registrasi diatur sebagai berikut:
a.	Batas minimal usia untuk mendaftarkan diri sebagai Anda adalah 18 tahun pada saat mendaftar.
b.	Anda diminta untuk mendaftarkan email, satu nomor handphone. Satu kartu identitas hanya berlaku untuk satu akun.
c.	Jika nomor handphone dan alamat email yang digunakan belum terdaftar pada Situs ini, maka Anda akan diarahkan untuk melakukan proses pendaftaran terlebih dahulu, dengan mengisi data nama, alamat email dan nomor handphone, kemudian Anda akan mendapatkan verifikasi kode OTP yang akan dikirimkan sesuai alamat email yang sudah terdaftar atas nomor handphone tersebut.
d.	Setelah proses verifikasi kode OTP berhasil, maka Anda diarahkan untuk membuat Password dan Konfirmasi Password.
e.	Anda sudah dapat melakukan proses login dengan menggunakan email atau nomor handphone yang telah diinformasikan melalui email. 

Login adalah sebuah proses dimana Anda telah terdaftar menjadi Anda dan ingin menggunakan Situs. Pada halaman Login, Anda dapat menggunakan email atau nomor handphone yang sama dengan yang digunakan pada Situs. Hal ini bertujuan untuk melanjutkan data dan progress yang sudah ada pada Situs.

3.	Akurasi Data dan Keamanan Akun 
Akun Anda bersifat sangat pribadi dan rahasia. Anda bertanggung jawab penuh untuk menyimpan dan menggunakannya. Perusahaan tidak bertanggung jawab kepada Anda atas segala kerugian yang dialami karena adanya kesalahan dan penyalahgunaan akses akun Anda yang tidak sah, curang, atau tidak pantas.
Anda bertanggung jawab sepenuhnya untuk menjaga kerahasiaan kata sandi, nomor keanggotaan, dan informasi akses akun lainnya serta untuk membatasi akses ke perangkat dan akun Anda, termasuk, tanpa batasan, akun email apa pun yang terkait dengan akun Anda, sehingga orang lain tidak dapat mengakses akun Anda, dan Perusahaan tidak akan bertanggung jawab atas kegagalan Anda untuk melakukannya.



4.	Penangguhan atau penghentian akun
Setiap pelanggaran oleh Anda terhadap Syarat dan Ketentuan ini, penyampaian informasi palsu dapat mengakibatkan penangguhan sementara keanggotaan atau penghentian akun Anda tanpa pemberitahuan. 

5.	Kewajiban Informasi dan Data Akun
Perusahaan mengumpulkan dan memproses informasi pribadi Anda termasuk namun tidak terbatas kepada nama, alamat, surat elektronik, nomor telepon Anda ketika Anda mendaftarkan diri pada Situs. Anda wajib untuk memberikan informasi yang akurat dan lengkap serta memperbaharui informasi dan setuju untuk memberikan kepada Perusahaan bukti identitas apapun yang secara wajar Perusahaan mintakan agar Perusahaan dapat tetap menyediakan layanan melalui Situs secara utuh dan maksimal kepada Anda.
Setelah mendaftarkan diri pada Situs, Anda akan mendapatkan suatu akun pribadi yang dapat diakses dengan kata sandi yang Anda pilih. Anda berjanji untuk tidak menyerahkan, mengalihkan maupun memberikan wewenang kepada orang lain untuk menggunakan identitas Anda atau menggunakan akun Anda. Anda wajib menjaga kerahasiaan kata sandi akun Anda dan setiap identifikasi yang Perusahaan berikan kepada atas akun atau identitas pribadi Anda. Dalam hal terjadi pengungkapan kata sandi atas akun Anda dengan cara apapun yang terjadi bukan atas kesalahan Perusahaan dan mengakibatkan penggunaan yang tidak sah dan/atau tanpa kewenangan atas akun Anda, transaksi maupun pesanan yang terjadi dalam Situs tetap dianggap sebagai transaksi yang sah kecuali Anda memberitahu Perusahaan sebelumnya.
Anda wajib melaporkan kepada Perusahaan bila Anda kehilangan kendali atas akun Anda. Anda bertanggung jawab atas setiap penggunaan akun Situs Anda meskipun jika Akun tersebut telah disalahgunakan oleh pihak lain

6.	Perubahan Syarat dan Ketentuan
Perusahaan berhak untuk mengubah Syarat dan Ketentuan ini dari waktu ke waktu dengan mempostingnya di Situs. Syarat dan Ketentuan yang diperbarui akan berlaku efektif sejak saat diposting, atau pada tanggal selanjutnya atau dengan metode lain yang ditentukan oleh Perusahaan. Kecuali dinyatakan lain, Syarat dan Ketentuan yang diperbarui akan berlaku pada saat Anda mengakses dan menggunakan Situs.
Situs ini mungkin berisi outbond links ke situs web lainnya yang dioperasikan oleh Perusahaan. Saat menggunakan situs-situs web tersebut, Anda perlu menyetujui syarat dan ketentuan terpisah yang mungkin berbeda dengan Syarat dan Ketentuan ini.

7.	Larangan
Dengan mengakses dan menggunakannya Situs ini, Anda dianggap telah menyetujui Syarat dan Ketentuan. 
Dalam menggunakan Situs, Anda tidak boleh terlibat dalam salah satu tindakan berikut ini:
a.	Tindakan pelanggaran atas properti atau privasi, dan lain-lain, dari Perusahaan dan/atau pihak ketiga, atau melakukan tindakan apapun yang dapat menyebabkan pelanggaran tersebut.
b.	Setiap tindakan yang menyebabkan kerugian atau kerusakan kepada Perusahaan dan/atau pihak ketiga.
c.	Setiap tindakan melanggar hukum seperti pengumpulan, pengungkapan, pemalsuan atau penghapusan informasi Perusahaan dan/atau pihak ketiga (antara lain, informasi yang terdaftar, informasi riwayat penggunaan dan informasi lainnya), atau tindakan apapun yang dapat menyebabkan hal tersebut.
d.	Setiap tindakan yang mengakses Situs (termasuk server dan jaringan yang terhubung dengan Situs) tanpa izin atau dengan cara lain secara melanggar hukum, atau tindakan lainnya yang menghambat penggunaan atau operasinya, atau tindakan yang dapat menyebabkan hal tersebut termasuk tindakan-tindakan sehubungan dengannya yang menyebabkan kerugian bagi Perusahaan dan/atau pihak ketiga.
e.	Setiap tindakan yang melanggar ketertiban umum, kesusilaan atau moral, atau tindakan yang dapat menyebabkan hal tersebut.
f.	Setiap tindakan melanggar hukum atau tindakan yang berhubungan dengan tindakan melanggar hukum, atau tindakan yang dapat menyebabkan hal tersebut.
g.	Setiap tindakan pernyataan atau deklarasi palsu atau penyerahan termasuk memberikan informasi yang tidak benar atau menyesatkan.
h.	Setiap tindakan menggunakan Situs untuk mendapatkan keuntungan yang tidak sesuai dengan tujuan dari Situs ini, atau setiap tindakan dalam mempersiapkan hal tersebut. 
i.	Setiap tindakan yang mencemarkan atau merusak nama baik Perusahaan dan/atau pihak ketiga manapun.
j.	Setiap tindakan yang menggunakan atau memberikan program yang merusak seperti virus komputer atau program perusak piranti lunak lainnya (termasuk namun tidak terbatas pada malware, trojan horse, dan lain-lain) atau setiap tindakan yang dapat menyebabkan hal tersebut.
k.	Setiap tindakan yang menggunakan Situs dengan berpura-pura menjadi pihak ketiga.
l.	Setiap tindakan yang melanggar Syarat dan Ketentuan, dan Kebijakan Privasi, atau setiap tindakan yang dapat menyebabkan hal tersebut.
m.	Setiap tindakan yang melanggar hukum dan peraturan baik di dalam maupun di luar negeri, setiap tindakan yang dapat menyebabkan hal tersebut, atau setiap tindakan yang melanggar tindakan administratif yang mengikat secara hukum.
n.	Setiap tindakan lain yang dianggap tidak pantas oleh Perusahaan dan/atau mengakibatkan adanya tanggung jawab pada Perusahaan, baik secara langsung maupun tidak langsung.
o.	Perusahaan berhak mengambil langkah hukum yang relevan terhadap Anda dalam setiap pelanggaran atas larangan-larangan di atas sesuai dengan ketentuan hukum dan/atau peraturan perundang-undangan yang berlaku.

8.	Batasan Tanggung Jawab
Perusahaan menyediakan Situs ini sebagaimana adanya dengan selalu mengusahakan kualitas dan sistem pengamanan yang sebaik-baiknya. Namun Perusahaan tidak menjamin bahwa Situs sepenuhnya bebas dari error, bug, gangguan, kerusakan atau cacat lainnya. Perusahaan dibebaskan dari tanggung jawab atas kerugian akibat force majeure atau penangguhan layanan.
Perusahaan tidak membuat jaminan apapun mengenai keakuratan, ketepatan waktu, kegunaan, atau karakteristik lainnya yang terkait dengan informasi yang diterbitkan dalam Situs. Perusahaan dapat, dengan kebijakan Perusahaan sendiri, menambah, mengubah, mengoreksi, atau menghapus informasi yang diterbitkan dalam Situs setiap saat tanpa memberikan pemberitahuan sebelumnya kepada Anda sepanjang diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku. Dalam hal penambahan, perubahan, koreksi atau penghapusan informasi Situs menyebabkan kerugian bagi Anda, maka ketentuan Pasal 10 akan berlaku.
Perusahaan dapat, dengan kebijakan Perusahaan sendiri, menangguhkan atau menghentikan Situs secara keseluruhan atau sebagian, untuk sebab-sebab yang termasuk, namun tidak terbatas pada, sebagai berikut:
a.	Dalam hal adanya pemeliharaan atau perawatan secara berkala atau keadaan darurat atau peningkatan peralatan dan sistem untuk publikasi Situs.
b.	Dalam hal layanan atau penampilan Situs sulit untuk dilaksanakan karena hal-hal yang berada di luar kendali Perusahaan seperti kebakaran, mati listrik, bencana alam, keadaan perang atau darurat sipil/militer, gangguan telekomunikasi (termasuk pemutusan jaringan internet) dan lain sebagainya yang berada di luar kendali Perusahaan.
c.	Dalam hal layanan telekomunikasi tidak disediakan oleh operator telekomunikasi.
d.	Saat Perusahaan telah menetapkan penangguhan sementara atau pemberhentian Situs dibutuhkan untuk operasional atau alasan-alasan teknis, atau Perusahaan telah menetapkan atas keadaan yang tidak terduga, maka layanan atau penampilan Situs terlalu sulit untuk dilakukan.
e.	Dalam hal adanya perintah dari aparat yang berwenang dan/ atau suatu putusan pengadilan berdasarkan hukum dan/atau peraturan perundang-undangan yang berlaku.
f.	Dalam hal Perusahaan memiliki alasan untuk percaya bahwa suatu serangan siber atau gangguan keamanan atas sistem yang kredibel telah, sedang, atau akan terjadi dalam waktu dekat.

Dalam hal setiap kerugian atau kerusakan terjadi pada Anda atau pihak ketiga karena penangguhan sementara atau penghentian Situs atau penampilan Situs, ketentuan Pasal 10 akan berlaku.
Anda dapat mengakhiri penggunaan Situs dengan menghapus Akun Anda yang telah didaftarkan dan diakses dalam perangkat yang digunakan.
Bahkan jika Anda mengakhiri penggunaan Situs, informasi Anda yang diperoleh oleh Perusahaan melalui Situs tidak akan dihapus. Penanganan informasi tersebut dari Anda akan disesuaikan dan diperlakukan sesuai dengan ketentuan dalam Kebijakan Privasi serta hukum dan/atau peraturan perundang-undangan yang berlaku tentang data pribadi.
Dalam hal Anda melanggar ketentuan dalam Syarat dan Ketentuan, Perusahaan memiliki hak untuk menghilangkan atau memberhentikan sementara hak Anda berdasarkan Syarat dan Ketentuan dan hak Anda untuk menggunakan Situs tanpa pemberitahuan sebelumnya kepada Anda. Perusahaan tidak akan bertanggung jawab atas kerugian atau kerusakan yang terjadi pada Anda sebagai akibat dari Perusahaan dalam melaksanakan hak-hak yang tertera dalam butir ini. Untuk tujuan pengakhiran hak Anda di atas, Anda setuju untuk mengabaikan Pasal 1266 Kitab Undang-Undang Hukum perdata.
Dalam hal sebuah tautan dari Situs mengacu ke situs web atau aplikasi pihak ketiga, Perusahaan tidak akan bertanggung jawab atas setiap situs web atau aplikasi pihak ketiga selain Situs ini. Dalam keadaan tersebut, Perusahaan tidak akan bertanggung jawab atas isi, iklan, produk, layanan, dan lain-lain, termasuk konten maupun isi dalam situs web atau aplikasi pihak ketiga tersebut dan yang tersedia untuk penggunaan situs web atau aplikasi pihak ketiga tersebut termasuk tindakan-tindakan Anda pada situs web atau aplikasi pihak ketiga tersebut.  Perusahaan, tidak akan bertanggung jawab untuk memberikan kompensasi atas kerusakan yang diakibatkan oleh atau terjadi karena berhubungan dengan setiap isi, iklan, produk, layanan, dan lain sebagainya, yang bukan merupakan produk dari Perusahaan dan/atau Manajemen. Dengan menyediakan tautan atau menyediakan akses ke situs web atau aplikasi pihak ketiga, Perusahaan tidak memberikan setiap pernyataan, jaminan atau pengesahan, secara tegas atau tersirat, berkenaan dengan legalitas, akurasi, kualitas atau keaslian konten, informasi atau jasa yang disediakan oleh situs web atau aplikasi pihak ketiga tersebut.

9.	Merek Dagang / Hak Kekayaan Intelektual
Anda tidak boleh menyalin, membuat ulang, mendistribusikan, mengirimkan, menyiarkan, menampilkan, menjual, melisensikan atau mengeksploitasi setiap Merek dagang yang terdapat dalam Situs untuk tujuan apapun.

10.	Ganti Rugi
Perusahaan tidak akan bertanggung jawab atas setiap kerusakan atau kerugian apapun yang terjadi pada Anda atau pihak ketiga yang berhubungan dengan perubahan, gangguan, penangguhan, pemberhentian atau pengakhiran dan lain sebagainya pada Situs sejauh mana diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku.
Perusahaan tidak akan bertanggung jawab atas kerusakan yang disebabkan oleh peristiwa atau kejadian tertentu yang berada di luar dugaan Perusahaan maupun yang bersifat force majeure atau dikarenakan keadaan terpaksa sepanjang diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku.
Dalam hal Anda menyebabkan kerusakan dan/atau kerugian kepada pihak ketiga sehubungan dengan penggunaan Situs, Anda harus bertanggung jawab untuk menyelesaikan kerusakan dan/atau kerugian tersebut dengan biaya Anda dan tidak akan meminta Perusahaan untuk bertanggung jawab atas tindakan yang dilakukan oleh Anda sendiri. Dalam hal Anda menyebabkan kerusakan dan/atau kerugian pada Perusahaan melalui tindakan yang melanggar Syarat dan Ketentuan serta hukum dan/atau peraturan perundang-undangan yang berlaku,  Perusahaan dapat meminta ganti rugi atau kompensasi yang sepadan atas kerusakan dan/atau kerugian yang disebabkan oleh Anda termasuk mengambil langkah-langkah hukum terhadap Anda sebagai akibat dari tindakan yang dilakukakannya.
Sejauh diperkenankan oleh hukum dan/atau peraturan perundang-undangan yang berlaku, Anda setuju untuk membela, mengganti rugi dan membebaskan  Perusahaan pemegang saham, sponsor, mitra usaha Perusahaan, direktur, komisaris, karyawan dan agen, dari dan terhadap setiap dan semua klaim, kerusakan, kewajiban, kerugian, tanggung jawab, biaya atau hutang dan pengeluaran (termasuk, namun tidak terbatas pada, biaya penasihat hukum) yang timbul dari:
a.	Penggunaan atau akses Anda terhadap layanan Perusahaan yang disebabkan oleh kelalaian Anda
b.	Pelanggaran terhadap Syarat dan Ketentuan ini; dan/atau
c.	Pelanggaran oleh Anda terhadap pihak ketiga

11.	Keterkaitan Data Pribadi 
Anda setuju bahwa Perusahaan dapat memperoleh, menyimpan, mengelola, menggunakan dan mentransfer data pribadi Anda untuk tujuan menyediakan Layanan dalam Situs ini.
Pengelolaan data pribadi secara konsisten merujuk pada Kebijakan Privasi yang terpisah untuk detail pemrosesan data, yang merupakan praktik yang sangat baik dan yang ditentukan secara terpisah oleh Perusahaan.

12.	Keterpisahan
Apabila salah satu atau lebih ketentuan dalam Syarat dan Ketentuan ini dinyatakan tidak sah, batal atau tidak dapat diberlakukan, ketentuan tersebut harus dianggap terpisah dan tidak akan mempengaruhi validitas dan/atau keberlakukan ketentuan-ketentuan lainnya dalam Syarat dan Ketentuan yang tetap memiliki kekuatan hukum penuh dan berlaku.
 
13.	Keseluruhan Perjanjian
Syarat dan Ketentuan ini merupakan perjanjian yang lengkap dan eksklusif antara Anda dan Perusahaan sehubungan dengan Situs. Syarat dan Ketentuan ini menggantikan semua komunikasi, perjanjian, iklan, dan proposal sebelumnya atau saat ini, baik secara elektronik, lisan, atau tertulis, sehubungan dengan Situs ini atau versi lain dari program loyalitas pelanggan dari Perusahaan atau Pihak Perusahaan. Baik Anda maupun Perusahaan mengakui bahwa tidak satu pun dari Anda yang dibujuk untuk menyetujui Syarat dan Ketentuan ini oleh pernyataan atau janji apa pun yang tidak secara khusus dinyatakan dalam Syarat dan Ketentuan ini.
 
14.	Hukum yang Berlaku
Syarat dan Ketentuan ini diatur oleh Hukum Indonesia.
 
15.	Penyelesaian Perselisihan
Setiap perselisihan yang timbul dari atau berkenaan dengan Syarat dan Ketentuan ini, termasuk namun tidak terbatas pada setiap pertanyaan tentang keberadaan, validitas atau pengakhirannya, yang tidak dapat diselesaikan secara damai, harus diselesaikan melalui Pengadilan.

16.	Bahasa
Jika terdapat perbedaan atau ketidakkonsistenan antara versi bahasa Inggris dan versi bahasa Indonesia dari Syarat dan Ketentuan ini, versi bahasa Indonesia akan berlaku, mengatur, dan mengendalikan.

17.	Pemberitahuan
Anda setuju untuk menerima semua komunikasi termasuk pemberitahuan, perjanjian, pengungkapan, informasi lain dari Perusahaan atau dengan mengunggahnya pada fitur dalam Situs. Untuk pertanyaan terkait, Anda dapat menghubungi dan/atau menyampaikan pemberitahuan melalui alamat email: hrd.metland@metropolitanland.com.  

</p>
                 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Terms modal focus handler
            var termsModal = document.getElementById('termsModal');
            if (termsModal) {
                termsModal.addEventListener('hidden.bs.modal', function () {
                    var cb = document.getElementById('acceptTerms');
                    if (cb) cb.focus();
                });
            }

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('strengthBar');
            
            if (passwordInput && strengthBar) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    
                    let strength = 0;
                    if (password.length >= 6) strength++;
                    if (password.match(/[a-z]/)) strength++;
                    if (password.match(/[A-Z]/)) strength++;
                    if (password.match(/[0-9]/)) strength++;
                    if (password.match(/[^a-zA-Z0-9]/)) strength++;
                    
                    strengthBar.className = 'password-strength-bar';
                    if (strength <= 1) {
                        strengthBar.classList.add('strength-weak');
                    } else if (strength <= 2) {
                        strengthBar.classList.add('strength-fair');
                    } else if (strength <= 3) {
                        strengthBar.classList.add('strength-good');
                    } else {
                        strengthBar.classList.add('strength-strong');
                    }
                });
            }

            // Toggle password visibility
            const togglePwd = document.getElementById('toggleRegPassword');
            const pwd = document.getElementById('password');
            const iconPwd = document.getElementById('toggleRegPasswordIcon');
            const toggleConfirm = document.getElementById('toggleRegConfirm');
            const confirm = document.getElementById('password_confirmation');
            const iconConfirm = document.getElementById('toggleRegConfirmIcon');

            if (togglePwd && pwd && iconPwd) {
                togglePwd.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Password toggle clicked'); // Debug log
                    
                    if (pwd.type === 'password') {
                        pwd.type = 'text';
                        iconPwd.classList.remove('bi-eye');
                        iconPwd.classList.add('bi-eye-slash');
                    } else {
                        pwd.type = 'password';
                        iconPwd.classList.remove('bi-eye-slash');
                        iconPwd.classList.add('bi-eye');
                    }
                });
            }

            if (toggleConfirm && confirm && iconConfirm) {
                toggleConfirm.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Confirm password toggle clicked'); // Debug log
                    
                    if (confirm.type === 'password') {
                        confirm.type = 'text';
                        iconConfirm.classList.remove('bi-eye');
                        iconConfirm.classList.add('bi-eye-slash');
                    } else {
                        confirm.type = 'password';
                        iconConfirm.classList.remove('bi-eye-slash');
                        iconConfirm.classList.add('bi-eye');
                    }
                });
            }

            // Real-time password confirmation validation
            function validatePasswordMatch() {
                if (!pwd || !confirm) return;
                
                const passwordValue = pwd.value;
                const confirmValue = confirm.value;
                
                // Remove existing error message
                let existingError = confirm.parentElement.querySelector('.password-match-error');
                if (existingError) {
                    existingError.remove();
                }
                
                // Remove invalid class
                confirm.classList.remove('is-invalid', 'is-valid');
                
                // Only validate if confirm field has value
                if (confirmValue.length > 0) {
                    if (passwordValue !== confirmValue) {
                        // Passwords don't match
                        confirm.classList.add('is-invalid');
                        confirm.classList.remove('is-valid');
                        
                        // Add error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback password-match-error';
                        errorDiv.textContent = 'Konfirmasi password tidak cocok dengan password.';
                        confirm.parentElement.appendChild(errorDiv);
                    } else {
                        // Passwords match
                        confirm.classList.add('is-valid');
                        confirm.classList.remove('is-invalid');
                    }
                }
            }

            // Add event listeners for real-time validation
            if (pwd && confirm) {
                pwd.addEventListener('input', validatePasswordMatch);
                confirm.addEventListener('input', validatePasswordMatch);
                
                // Also validate on blur
                confirm.addEventListener('blur', validatePasswordMatch);
            }

            // Align toggle buttons vertically
            function alignToggleButtons() {
                var groups = document.querySelectorAll('.form-floating.position-relative');
                groups.forEach(function(g){
                    var input = g.querySelector('input');
                    var btn = g.querySelector('.toggle-password-btn');
                    if (!input || !btn) return;
                    // compute offsetTop of input relative to group
                    var top = input.offsetTop + (input.clientHeight / 2);
                    btn.style.top = top + 'px';
                    btn.style.transform = 'translateY(-50%)';
                });
            }

            alignToggleButtons();
            window.addEventListener('resize', alignToggleButtons);
            // Registration DOB client-side age check (must be >= 18)
            const regForm = document.querySelector('form[action="{{ route('register') }}"]') || document.querySelector('form');
            if (regForm) {
                regForm.addEventListener('submit', function(e) {
                    const dobEl = document.getElementById('date_of_birth');
                    if (!dobEl) return;
                    const dobVal = dobEl.value;
                    if (!dobVal) {
                        dobEl.classList.add('is-invalid');
                        e.preventDefault();
                        alert('Mohon isi tanggal lahir.');
                        return false;
                    }
                    const dob = new Date(dobVal + 'T00:00:00');
                    if (isNaN(dob.getTime())) {
                        dobEl.classList.add('is-invalid');
                        e.preventDefault();
                        alert('Format tanggal lahir tidak valid.');
                        return false;
                    }
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const m = today.getMonth() - dob.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }
                    if (age < 18) {
                        dobEl.classList.add('is-invalid');
                        e.preventDefault();
                        alert('Umur minimal 18 tahun untuk mendaftar.');
                        return false;
                    }
                });

                const dobEl = document.getElementById('date_of_birth');
                if (dobEl) {
                    dobEl.addEventListener('change', function() {
                        this.classList.remove('is-invalid');
                    });
                }
            }
        });
    </script>
    
    <!-- Disable Inspect Element Protection -->
    <script type="text/javascript">
        document.onkeydown = (e) => {
            if (e.key === 123) {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key === 'J') {
                e.preventDefault();
            }
            if (e.ctrlKey && e.key === 'U') {
                e.preventDefault();
            }
            if (e.key === 'F12') {
                e.preventDefault();
            }
        };
    </script>
</body>
</html>