@extends('layouts.app')

@section('title', 'Beranda - Metland Recruitment')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Temukan Karir Impian Anda
                    <span class="text-warning">Bersama Kami</span>
                </h1>
                <p class="lead mb-4">
                    Bergabunglah dengan tim profesional di Metland dan wujudkan potensi terbaik Anda. 
                    Daftarkan diri Anda sekarang untuk peluang karir yang tak terbatas.
                </p>
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ route('applicant.create') }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-person-plus-fill me-2"></i>Isi Data Diri
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                        </a>
                    @endauth
                    <a href="#features" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-info-circle-fill me-2"></i>Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-briefcase-fill" style="font-size: 15rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Mengapa Memilih Kami?</h2>
                <p class="text-muted">Platform rekrutmen terdepan dengan fitur-fitur unggulan</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-check-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Aman & Terpercaya</h5>
                        <p class="card-text text-muted">
                            Data pribadi Anda dilindungi dengan enkripsi tingkat tinggi dan keamanan berlapis.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-lightning-fill text-warning" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Proses Cepat</h5>
                        <p class="card-text text-muted">
                            Sistem otomatis yang memproses aplikasi Anda dengan cepat dan efisien.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Tim Profesional</h5>
                        <p class="card-text text-muted">
                            Dikelola oleh tim HR berpengalaman yang memahami kebutuhan industri.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="section-title">Cara Kerja Platform</h2>
                <p class="text-muted">Langkah-langkah mudah untuk mendaftar</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-plus-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5>1. Daftar</h5>
                    <p class="text-muted">Isi formulir pendaftaran dengan data diri lengkap</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-file-earmark-text-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5>2. Upload Dokumen</h5>
                    <p class="text-muted">Upload CV, foto, dan dokumen pendukung lainnya</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-search" style="font-size: 2rem;"></i>
                    </div>
                    <h5>3. Review</h5>
                    <p class="text-muted">Tim kami akan meninjau aplikasi Anda</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-telephone-fill" style="font-size: 2rem;"></i>
                    </div>
                    <h5>4. Interview</h5>
                    <p class="text-muted">Jika lolos, Anda akan diundang untuk interview</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="card border-0" style="background: var(--gradient-bg); color: white;">
                    <div class="card-body p-5">
                        <h2 class="mb-4">Siap Memulai Karir Baru?</h2>
                        <p class="lead mb-4">
                            Jangan lewatkan kesempatan emas untuk bergabung dengan tim terbaik. 
                            Daftar sekarang dan wujudkan impian karir Anda!
                        </p>
                        @auth
                            <a href="{{ route('applicant.create') }}" class="btn btn-warning btn-lg px-5">
                                <i class="bi bi-rocket-takeoff-fill me-2"></i>Isi Data Diri
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-5">
                                <i class="bi bi-rocket-takeoff-fill me-2"></i>Daftar Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-primary">500+</h3>
                <p class="text-muted">Kandidat Terdaftar</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-building text-success" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-success">50+</h3>
                <p class="text-muted">Perusahaan Partner</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-briefcase-fill text-warning" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-warning">200+</h3>
                <p class="text-muted">Lowongan Tersedia</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <i class="bi bi-trophy-fill text-info" style="font-size: 3rem;"></i>
                </div>
                <h3 class="fw-bold text-info">95%</h3>
                <p class="text-muted">Tingkat Kepuasan</p>
            </div>
        </div>
    </div>
</section>
@endsection
