<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP - Metland Recruitment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#ffffff; display:flex; align-items:center; justify-content:center; min-height:100vh;">
    <div class="card p-4" style="width:420px; background:#e9e7e7; border-radius:16px;">
        <div class="card-body bg-transparent">
            <h4 class="mb-2">Verifikasi Kode OTP</h4>
            <p class="text-muted">Masukkan 6 digit kode yang dikirim ke email Anda.</p>

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
                    <input type="text" name="otp" class="form-control" placeholder="Kode OTP" required maxlength="6">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Verifikasi & Buat Akun</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('register') }}">Kembali ke pendaftaran</a>
            </div>
        </div>
    </div>
</body>
</html>