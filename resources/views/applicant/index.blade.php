@extends('layouts.app')

@section('title', 'Lowongan Pekerjaan - Metland Recruitment')

@section('styles')
<style>
    .job-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .job-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .job-level {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .job-desc {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .job-spec {
        color: #495057;
        font-size: 0.85rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .apply-btn {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .apply-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
        color: white;
    }
    
    .apply-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .applied-badge {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
    }
    
    .no-jobs {
        text-align: center;
        padding: 4rem 0;
    }
    
    .no-jobs i {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 0;
        }
        
        .hero-section .display-4 {
            font-size: 2rem;
        }
        
        .job-card {
            margin-bottom: 1.5rem;
        }
        
        .apply-btn {
            width: 100%;
            margin-top: 1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="bi bi-briefcase-fill me-3"></i>
                    Lowongan Pekerjaan Terbaru
                </h1>
                <p class="lead mb-4">
                    Temukan kesempatan karir terbaik di Metland. 
                    Pilih posisi yang sesuai dengan minat dan kemampuan Anda.
                </p>
                @if(!auth()->check())
                <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-4">
                    <i class="bi bi-person-plus-fill me-2"></i>Login untuk Melamar
                </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Job Vacancies Section -->
<section class="py-5">
    <div class="container">
        @if($jobVacancies->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="text-center mb-4">
                        <i class="bi bi-list-ul me-2"></i>
                        Daftar Lowongan Tersedia
                    </h2>
                    <p class="text-center text-muted">
                        Menampilkan {{ $jobVacancies->count() }} lowongan pekerjaan yang sedang dibuka
                    </p>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($jobVacancies as $job)
                <div class="col-lg-6 col-xl-4">
                    <div class="card job-card h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="job-title">{{ $job->job_vacancy_name }}</h5>
                                <span class="job-level">{{ $job->job_vacancy_level_name }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-primary mb-2">
                                    <i class="bi bi-file-text me-1"></i>Deskripsi Pekerjaan:
                                </h6>
                                <p class="job-desc">{{ $job->job_vacancy_job_desc }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="text-success mb-2">
                                    <i class="bi bi-check-circle me-1"></i>Persyaratan:
                                </h6>
                                <p class="job-spec">{{ $job->job_vacancy_job_spec }}</p>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        Mulai: {{ $job->job_vacancy_start_date->format('d M Y') }}
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Berakhir: {{ $job->job_vacancy_end_date->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-info">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $job->job_vacancy_man_power }} posisi tersedia
                                </small>
                                
                                @if($userHasApplied && $appliedJobId == $job->job_vacancy_id)
                                    <span class="applied-badge">
                                        <i class="bi bi-check-circle me-1"></i>Sudah Melamar
                                    </span>
                                @elseif($userHasApplied)
                                    <button class="btn apply-btn" disabled>
                                        <i class="bi bi-x-circle me-1"></i>Sudah Melamar Pekerjaan Lain
                                    </button>
                                @elseif(auth()->check())
                                    <form method="POST" action="{{ route('job-vacancy.apply') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="job_vacancy_id" value="{{ $job->job_vacancy_id }}">
                                        <button type="submit" class="btn apply-btn" 
                                                onclick="return confirm('Apakah Anda yakin ingin melamar untuk posisi {{ $job->job_vacancy_name }}?')">
                                            <i class="bi bi-send me-1"></i>Lamar Sekarang
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn apply-btn">
                                        <i class="bi bi-person-plus me-1"></i>Login untuk Melamar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="no-jobs">
                <i class="bi bi-briefcase"></i>
                <h3 class="text-muted">Belum Ada Lowongan Tersedia</h3>
                <p class="text-muted">Saat ini tidak ada lowongan pekerjaan yang sedang dibuka. 
                Silakan kembali lagi nanti untuk melihat lowongan terbaru.</p>
                @if(auth()->check())
                <a href="{{ route('applicant.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-person-plus-fill me-2"></i>Lengkapi Data Diri
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-person-plus-fill me-2"></i>Login Terlebih Dahulu
                </a>
                @endif
            </div>
        @endif
    </div>
</section>

<!-- Info Section -->
@if($jobVacancies->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body p-5">
                        <h3 class="mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Informasi Penting
                        </h3>
                        <div class="row text-start">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Satu user hanya bisa melamar satu pekerjaan
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Pastikan data diri sudah lengkap
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Lamaran akan diproses oleh tim HR
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Status akan diupdate secara berkala
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection