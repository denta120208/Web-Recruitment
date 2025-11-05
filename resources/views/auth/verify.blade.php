<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP - Metland Recruitment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .otp-card {
            width: 420px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
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
        
        .card-header-custom {
            background: #009290;
            padding: 2rem;
            text-align: center;
            color: white;
        }
        
        .card-header-custom h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .card-header-custom p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .card-body-custom {
            padding: 2rem;
        }
        
        .otp-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .btn-primary {
            background: #009290;
            border: none;
            border-radius: 12px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            background: #009290;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .alert ul {
            list-style: none;
            padding-left: 0;
        }
        
        .text-muted {
            color: #6c757d !important;
            font-size: 0.9rem;
        }
        
        a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 1.5rem 0;
        }
    </style>
</head>
<body oncontextmenu="return false">
    <div class="card otp-card p-0">
        <div class="card-header-custom">
            <div class="otp-icon">🔐</div>
            <h4>Verifikasi Kode OTP</h4>
            <p>Masukkan 6 digit kode yang dikirim ke email Anda</p>
        </div>
        
        <div class="card-body-custom">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.verify.post') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" name="otp" class="form-control" placeholder="000000" required maxlength="6">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Verifikasi & Buat Akun</button>
                </div>
            </form>

            <div class="divider"></div>

            <div class="text-center">
                <a href="{{ route('register') }}">← Kembali ke pendaftaran</a>
            </div>
        </div>
    </div>
    
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