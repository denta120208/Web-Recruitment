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
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Syarat &amp; Ketentuan Pengelolaan Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Dengan mencentang dan menyetujui syarat ini, Anda menyetujui bahwa data pribadi Anda akan dikelola oleh Metropolitan Land Tbk untuk keperluan proses rekrutmen dan administrasi terkait.</p>
                    <p>Data yang dikumpulkan termasuk (tetapi tidak terbatas pada): nama, tanggal lahir, alamat, email, nomor telepon, CV, foto, riwayat pendidikan dan pekerjaan. Data akan disimpan sesuai dengan kebijakan privasi dan hanya digunakan untuk keperluan yang tercantum di atas.</p>
                    <p>Jika Anda setuju, silakan centang kotak persetujuan pada formulir pendaftaran untuk melanjutkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // If the browser shows native validation message when submit is attempted while modal is open,
        // ensure focus returns to the checkbox when modal closes so user can easily tick it.
        var termsModal = document.getElementById('termsModal');
        if (termsModal) {
            termsModal.addEventListener('hidden.bs.modal', function () {
                var cb = document.getElementById('acceptTerms');
                if (cb) cb.focus();
            });
        }
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
                // compute offsetTop of input relative to group
                var top = input.offsetTop + (input.clientHeight / 2);
                btn.style.top = top + 'px';
                btn.style.transform = 'translateY(-50%)';
            });
        }

        window.addEventListener('load', function(){ alignToggleButtons(); });
        window.addEventListener('resize', function(){ alignToggleButtons(); });
    </script>
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            
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
        (function(){
            const togglePwd = document.getElementById('toggleRegPassword');
            const pwd = document.getElementById('password');
            const iconPwd = document.getElementById('toggleRegPasswordIcon');
            const toggleConfirm = document.getElementById('toggleRegConfirm');
            const confirm = document.getElementById('password_confirmation');
            const iconConfirm = document.getElementById('toggleRegConfirmIcon');

            if (togglePwd && pwd) {
                togglePwd.addEventListener('click', function(){
                    if (pwd.type === 'password') {
                        pwd.type = 'text';
                        iconPwd.classList.remove('bi-eye'); iconPwd.classList.add('bi-eye-slash');
                    } else {
                        pwd.type = 'password';
                        iconPwd.classList.remove('bi-eye-slash'); iconPwd.classList.add('bi-eye');
                    }
                });
            }

            if (toggleConfirm && confirm) {
                toggleConfirm.addEventListener('click', function(){
                    if (confirm.type === 'password') {
                        confirm.type = 'text';
                        iconConfirm.classList.remove('bi-eye'); iconConfirm.classList.add('bi-eye-slash');
                    } else {
                        confirm.type = 'password';
                        iconConfirm.classList.remove('bi-eye-slash'); iconConfirm.classList.add('bi-eye');
                    }
                });
            }
        })();
    </script>
</body>
</html CSS -->
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
            align-items: flex-start;
            justify-content: center;
            padding: 3rem 0;
            position: relative;
            overflow-x: hidden;
            width: 100%;
            margin: 0;
        }

        @media (max-height: 900px) {
            body {
                align-items: flex-start;
                padding: 2rem 0;
            }
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

        .col-md-6, .col-lg-5 {
            padding-left: 0;
            padding-right: 0;
        }

        .register-container {
            background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 30px;
            box-shadow: 
                0 30px 60px rgba(0,0,0,0.08),
                0 10px 20px rgba(0,0,0,0.04),
                inset 0 1px 0 rgba(255,255,255,0.9);
            overflow: hidden;
            max-width: 480px;
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

        .form-floating.position-relative { 
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
            z-index: 2;
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

        .form-floating {
            position: relative;
            z-index: 1;
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 2rem 0;
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
                margin: 0 15px;
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

        @media (max-width: 480px) {
            body {
                padding: 1.5rem 0;
            }

            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .register-container {
                border-radius: 15px;
                margin: 0 10px;
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

        @media (max-width: 360px) {
            body {
                padding: 1rem 0;
            }

            .register-container {
                border-radius: 12px;
                margin: 0 8px;
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

            .form-control {
                padding: 0.65rem 0.85rem;
                font-size: 0.85rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
            }

            .toggle-password-btn {
                height: 34px;
                width: 34px;
            }

            .form-floating > label {
                font-size: 0.85rem;
            }

            small {
                font-size: 0.7rem;
            }
        }

        /* Prevent horizontal scroll */
        @media (max-width: 991px) {
            .register-container:hover {
                transform: translateY(-2px);
            }
        }
    </style>
</head>
<body>
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

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                                </button>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="acceptTerms" name="accepted_terms" required>
                                <label class="form-check-label small ms-2" for="acceptTerms">
                                    Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat &amp; Ketentuan Pengelolaan Data</a> yang berlaku.
                                </label>
                                <div class="invalid-feedback">Anda harus menyetujui syarat & ketentuan untuk melanjutkan.</div>
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

    <!-- Bootstrap 5