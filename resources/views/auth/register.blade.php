<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar - Metland Recruitment</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/logo_metland.png?v=1') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/logo_metland.png?v=1') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .register-header {
            background: #009290;
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-body {
            padding: 2rem;
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

        /* ensure the floating container is positioned for absolute toggle button */
        .form-floating.position-relative { position: relative; }

        /* square toggle button aligned centrally on the right of input */
        .toggle-password-btn {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            padding: 6px;
            border-radius: 8px;
            height: 42px;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid #e9ecef;
        }

        .toggle-password-btn i { font-size: 1rem; }

        .btn-primary {
            background: #009290; /* match requested brand color */
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background: #007c73;
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
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
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

        .password-strength {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #ffc107; width: 50%; }
        .strength-good { background: #17a2b8; width: 75%; }
        .strength-strong { background: #28a745; width: 100%; }
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Syarat &amp; Ketentuan Pengelolaan Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
</html>
