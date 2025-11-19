@extends('layouts.app')

@section('title', 'Berhasil - Metland Recruitment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center p-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="bi bi-check-circle-fill text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <h2 class="text-success fw-bold mb-3">Lamaran Berhasil Dikirim!</h2>
                    <p class="text-muted mb-4">
                        Terima kasih telah melengkapi formulir aplikasi. Data Anda telah berhasil disimpan 
                        dan akan segera diproses oleh tim HR kami.
                    </p>
                    
                    
                    
                    <!-- Contact Info -->
                    <div class="bg-light rounded p-4 mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-telephone-fill text-primary me-2"></i>
                            Butuh Bantuan?
                        </h6>
                        <p class="mb-2">
                            <i class="bi bi-envelope-fill me-2"></i>
                            Email: hr@metland.com
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-phone-fill me-2"></i>
                            Telepon: (021) 1234-5678
                        </p>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('applicant.index') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-house-fill me-2"></i>Kembali ke Beranda
                        </a>
                        <a href="{{ route('applicant.edit', \App\Models\Applicant::where('user_id', auth()->id())->first()->getKey()) }}" class="btn btn-outline-primary btn-lg px-4">
                            <i class="bi bi-plus-circle-fill me-2"></i>Edit Profil
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Animation -->
<div class="position-fixed top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: -1;">
    <div class="confetti"></div>
</div>

<style>
.confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background: #f39c12;
    animation: confetti-fall 3s linear infinite;
}

.confetti:nth-child(1) { left: 10%; animation-delay: 0s; background: #e74c3c; }
.confetti:nth-child(2) { left: 20%; animation-delay: 0.5s; background: #3498db; }
.confetti:nth-child(3) { left: 30%; animation-delay: 1s; background: #2ecc71; }
.confetti:nth-child(4) { left: 40%; animation-delay: 1.5s; background: #f39c12; }
.confetti:nth-child(5) { left: 50%; animation-delay: 2s; background: #9b59b6; }
.confetti:nth-child(6) { left: 60%; animation-delay: 0.3s; background: #1abc9c; }
.confetti:nth-child(7) { left: 70%; animation-delay: 0.8s; background: #e67e22; }
.confetti:nth-child(8) { left: 80%; animation-delay: 1.3s; background: #34495e; }
.confetti:nth-child(9) { left: 90%; animation-delay: 1.8s; background: #e91e63; }

@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}
</style>
@endsection
