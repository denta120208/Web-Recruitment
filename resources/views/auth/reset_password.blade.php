<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password - Metland Recruitment</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/logo.png') }}">
    
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
            --light-bg: #ffffff;
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
            position: relative;
            overflow-x: hidden;
            width: 100%;
            padding: 10px;
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
            padding: 0;
            margin: 0 auto;
        }

        .row {
            margin: 0;
            width: 100%;
        }

        .col-md-6, .col-lg-4 {
            padding: 0;
        }

        .login-container {
            background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 20px;
            box-shadow: 
                0 20px 40px rgba(0,0,0,0.08),
                0 8px 16px rgba(0,0,0,0.04),
                inset 0 1px 0 rgba(255,255,255,0.9);
            overflow: hidden;
            max-width: 440px;
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

        /* Animated border glow */
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

        .login-container:hover::before {
            box-shadow: 0 0 20px rgba(0,146,144,0.5);
        }

        .login-header {
            background: linear-gradient(135deg, #009290 0%, #007c7a 100%);
            color: #ffffff;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            text-align: center;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Floating particles in header */
        .login-header::before,
        .login-header::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent);
            animation: floatParticle 6s ease-in-out infinite;
        }

        .login-header::before {
            width: 80px;
            height: 80px;
            top: -20px;
            left: -20px;
            animation-delay: 0s;
        }

        .login-header::after {
            width: 60px;
            height: 60px;
            bottom: -20px;
            right: -20px;
            animation-delay: 3s;
        }

        @keyframes floatParticle {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.3; }
            50% { transform: translate(20px, 20px) scale(1.2); opacity: 0.6; }
        }

        .login-body {
            padding: 1.75rem 1.75rem 2rem 1.75rem;
            position: relative;
            z-index: 1;
            overflow: hidden;
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

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
            position: relative;
            z-index: 1;
            width: 100%;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.15);
            background: #ffffff;
            transform: translateY(-2px);
        }

        .form-floating.position-relative { 
            position: relative;
            animation: fadeInUp 0.6s ease-out backwards;
            margin-bottom: 1rem;
        }

        .form-floating:nth-child(1) { animation-delay: 0.1s; }
        .form-floating:nth-child(2) { animation-delay: 0.2s; }
        .form-check { animation: fadeInUp 0.6s ease-out 0.3s backwards; }

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
            right: 10px;
            transform: translateY(-50%);
            padding: 6px;
            border-radius: 10px;
            height: 38px;
            width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 2;
        }

        .toggle-password-btn:hover {
            background: rgba(52,152,219,0.1);
            border-color: var(--secondary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password-btn i { 
            font-size: 0.95rem;
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
            padding: 0.75rem 1.5rem;
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
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,146,144,0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
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
            padding: 0.75rem 1.5rem;
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
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52,152,219,0.25);
        }

        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            animation: fadeIn 0.8s ease-out 0.2s both;
            position: relative;
            z-index: 1;
            line-height: 1.2;
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
            margin-bottom: 1rem;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
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

        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .form-check-input {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:hover {
            border-color: var(--secondary-color);
            transform: scale(1.1);
        }

        .form-check-label {
            cursor: pointer;
            font-size: 0.95rem;
        }

        .d-grid {
            animation: fadeInUp 0.6s ease-out 0.4s backwards;
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

        /* Additional form styling */
        .form-floating {
            position: relative;
            z-index: 1;
        }

        /* Tablet Landscape */
        @media (max-width: 991px) {
            .login-container:hover {
                transform: translateY(-2px);
            }
        }

        /* Tablet Portrait & Mobile Landscape */
        @media (max-width: 768px) {
            body {
                padding: 15px 10px;
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

            .login-container {
                border-radius: 18px;
                max-width: 100%;
            }

            .login-header {
                padding: 1.5rem 1.25rem 1.25rem 1.25rem;
            }

            .login-body {
                padding: 1.5rem 1.5rem 1.75rem 1.5rem;
            }

            .logo {
                font-size: 1.6rem;
            }

            .logo div:first-child {
                font-size: 1.6rem !important;
            }

            .logo div:last-child {
                font-size: 1rem !important;
            }

            .subtitle {
                font-size: 0.875rem;
            }

            .form-control {
                padding: 0.7rem 0.95rem;
                font-size: 0.95rem;
            }

            .toggle-password-btn {
                height: 36px;
                width: 36px;
                right: 8px;
            }

            .toggle-password-btn i {
                font-size: 0.9rem;
            }
        }

        /* Mobile Portrait */
        @media (max-width: 576px) {
            body {
                padding: 10px 8px;
            }

            .login-container {
                border-radius: 16px;
            }

            .login-header {
                padding: 1.25rem 1rem 1rem 1rem;
            }

            .login-body {
                padding: 1.25rem 1.25rem 1.5rem 1.25rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .logo div:first-child {
                font-size: 1.5rem !important;
            }

            .logo div:last-child {
                font-size: 0.95rem !important;
                margin-top: 4px !important;
            }

            .subtitle {
                font-size: 0.85rem;
            }

            .form-control {
                padding: 0.65rem 0.9rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.65rem 1.25rem;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .toggle-password-btn {
                height: 34px;
                width: 34px;
            }

            .form-floating > label {
                font-size: 0.9rem;
            }

            .form-check-label {
                font-size: 0.9rem;
            }

            .text-muted {
                font-size: 0.85rem;
            }

            .alert {
                font-size: 0.85rem;
                padding: 0.65rem 0.85rem;
            }

            .login-header::before {
                width: 60px;
                height: 60px;
            }

            .login-header::after {
                width: 50px;
                height: 50px;
            }

            .form-floating {
                margin-bottom: 0.85rem;
            }

            .form-floating.position-relative {
                margin-bottom: 0.85rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 400px) {
            body {
                padding: 8px 6px;
            }

            .login-container {
                border-radius: 14px;
            }

            .login-header {
                padding: 1rem 0.875rem 0.875rem 0.875rem;
            }

            .login-body {
                padding: 1rem 1rem 1.25rem 1rem;
            }

            .logo {
                font-size: 1.4rem;
            }

            .logo div:first-child {
                font-size: 1.4rem !important;
            }

            .logo div:last-child {
                font-size: 0.9rem !important;
            }

            .subtitle {
                font-size: 0.8rem;
            }

            .form-control {
                padding: 0.6rem 0.85rem;
                font-size: 0.875rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.6rem 1rem;
                font-size: 0.875rem;
            }

            .toggle-password-btn {
                height: 32px;
                width: 32px;
            }

            .toggle-password-btn i {
                font-size: 0.85rem;
            }

            .form-floating > label {
                font-size: 0.875rem;
            }

            .form-check-label {
                font-size: 0.875rem;
            }

            .text-muted {
                font-size: 0.8rem;
            }
        }

        /* Extra Small Mobile */
        @media (max-width: 360px) {
            body {
                padding: 6px 4px;
            }

            .login-container {
                border-radius: 12px;
            }

            .login-header {
                padding: 0.875rem 0.75rem 0.75rem 0.75rem;
            }

            .login-body {
                padding: 0.875rem 0.875rem 1rem 0.875rem;
            }

            .logo {
                font-size: 1.3rem;
            }

            .logo div:first-child {
                font-size: 1.3rem !important;
            }

            .logo div:last-child {
                font-size: 0.85rem !important;
            }

            .form-control {
                padding: 0.55rem 0.75rem;
                font-size: 0.85rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.55rem 0.875rem;
                font-size: 0.85rem;
            }

            .form-floating {
                margin-bottom: 0.75rem;
            }

            .form-floating.position-relative {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container">
                    <div class="login-header">
                        <div class="logo" style="line-height:1; color: #ffffff;">
                            <div style="font-weight:800;">Metland</div>
                            <div style="font-weight:700; margin-top:6px;">Recruitment</div>
                        </div>
                        <div class="subtitle" style="color: rgba(255,255,255,0.95);">Reset Password</div>
                    </div>
                    
                    <div class="login-body">
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

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            
                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Password Baru" required autofocus>
                                <label for="password">Password Baru</label>
                                <button type="button" id="togglePassword" class="toggle-password-btn" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter spesial</small>
                            </div>

                            <div class="form-floating mb-3 position-relative">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <button type="button" id="togglePasswordConfirm" class="toggle-password-btn" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                                </button>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Reset Password
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function(){
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            }
            
            // Toggle for password confirmation
            const toggleBtnConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const iconConfirm = document.getElementById('togglePasswordConfirmIcon');
            if (toggleBtnConfirm && passwordConfirmInput) {
                toggleBtnConfirm.addEventListener('click', function(){
                    if (passwordConfirmInput.type === 'password') {
                        passwordConfirmInput.type = 'text';
                        iconConfirm.classList.remove('bi-eye');
                        iconConfirm.classList.add('bi-eye-slash');
                    } else {
                        passwordConfirmInput.type = 'password';
                        iconConfirm.classList.remove('bi-eye-slash');
                        iconConfirm.classList.add('bi-eye');
                    }
                });
            }
        })();
    </script>
    <script>
        // align toggle-password-btn vertically with its corresponding input
        function alignToggleButtons(container) {
            container = container || document;
            var groups = container.querySelectorAll('.form-floating.position-relative');
            groups.forEach(function(g){
                var input = g.querySelector('input');
                var btn = g.querySelector('.toggle-password-btn');
                if (!input || !btn) return;
                var top = input.offsetTop + (input.clientHeight / 2);
                btn.style.top = top + 'px';
                btn.style.transform = 'translateY(-50%)';
            });
        }

        window.addEventListener('load', function(){ alignToggleButtons(); });
        window.addEventListener('resize', function(){ alignToggleButtons(); });
    </script>
</body>
</html>