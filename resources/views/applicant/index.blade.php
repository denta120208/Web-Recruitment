@extends('layouts.app')

@section('title', 'Lowongan Pekerjaan - Metland Recruitment')

@section('styles')
<style>
    .job-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .job-card .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        padding: 1.5rem;
    }
    
    .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .job-title {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.1rem;
        line-height: 1.3;
        flex: 1;
    }
    
    .job-level {
        background: #009290;
        color: white;
        padding: 0.35rem 0.7rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
        flex-shrink: 0;
        line-height: 1;
    }
    
    .job-card .card-body > div:first-of-type {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        flex-shrink: 0;
    }
    
    .job-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
        overflow: hidden;
    }
    
    .job-desc {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
        max-height: 4.5em;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        word-break: break-word;
        margin-bottom: 0.5rem;
    }
    
    .job-spec {
        color: #495057;
        font-size: 0.85rem;
        line-height: 1.4;
        max-height: 3em;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        word-break: break-word;
        margin-bottom: 0.5rem;
    }
    
    .job-card .card-body > div:last-of-type {
        margin-top: auto;
        padding-top: 1rem;
        flex-shrink: 0;
        border-top: 1px solid #e9ecef;
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
        background: #009290;
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

    /* Simple custom modal (plain JS, minimal transition to avoid flicker) */
    .cm-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.25); display: none; z-index: 1050; }
    .cm-overlay.show { display: block; }
    .cm-dialog { width: min(920px, 95vw); margin: 5vh auto; background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,.2); overflow: hidden; }
    .cm-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f3f5; display:flex; justify-content:space-between; align-items:center; }
    .cm-body { padding: 1.25rem; max-height: 70vh; overflow: auto; }
    .cm-footer { padding: .75rem 1.25rem; border-top: 1px solid #f1f3f5; display:flex; justify-content:space-between; align-items:center; }
    .cm-close { border: 0; background: transparent; font-size: 1.25rem; line-height: 1; }
    /* Prevent layout shift/flicker when modal opens */
    body.modal-open { padding-right: 0 !important; }
    .modal, .modal .modal-dialog { transition: none !important; }
    .modal-backdrop { display: none !important; }
    
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
                    We’re Hiring!
                </h1>
                <p class="lead mb-4">
                    Temukan kesempatan karir terbaik di Metland. 
                    Pilih posisi yang sesuai dengan minat dan kemampuan Anda.
                </p>
                
                @if(auth()->check() && isset($isHired) && $isHired)
                    <div class="alert alert-success d-inline-flex align-items-center px-4 py-3 mb-4" style="border-radius: 20px; font-size: 1.1rem;">
                        <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                        <strong>Selamat! Anda sudah menjadi Karyawan Metland</strong>
                    </div>
                @endif
                
                @if(!auth()->check())
                <a href="{{ route('login') }}" class="btn btn-warning btn-lg px-4">
                    <i class="bi bi-person-plus-fill me-2"></i>Login untuk Melamar
                        </a>
                    @else
                    @if(!isset($hasProfile) || !$hasProfile)
                    <a href="{{ route('applicant.create') }}" class="btn btn-warning btn-lg px-4">
                        <i class="bi bi-person-plus-fill me-2"></i>Lengkapi Data Diri
                    </a>
                    @else
                    
                    @endif
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
                        Job Vacancy 
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
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="job-title">{{ $job->job_vacancy_name }}</h5>
                                <span class="job-level">{{ $job->job_vacancy_level_name }}</span>
                            </div>
                            
                            @php
                                $locationDisplay = $job->location; // This will trigger accessor
                            @endphp
                            @if($locationDisplay)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt-fill me-1 text-primary"></i>
                                    <strong>Lokasi:</strong> {{ $locationDisplay }}
                                </small>
                            </div>
                            @endif
                            
                            <div class="job-content mb-2" style="flex: 1; overflow: hidden;">
                                <div class="mb-2">
                                    <h6 class="text-primary mb-1" style="font-size: 0.9rem;">
                                        <i class="bi bi-file-text me-1"></i>Deskripsi Pekerjaan:
                                    </h6>
                                    <div class="job-desc">{!! $job->job_vacancy_job_desc !!}</div>
                                    <button type="button" class="btn btn-link p-0 mt-1" style="font-size: 0.85rem;" onclick="openJobDetail({
                                        id: '{{ $job->job_vacancy_id }}',
                                        name: @js($job->job_vacancy_name),
                                        level: @js($job->job_vacancy_level_name),
                                        desc: @js($job->job_vacancy_job_desc),
                                        spec: @js($job->job_vacancy_job_spec),
                                        start: '{{ $job->job_vacancy_start_date->format('d M Y') }}',
                                        end: '{{ $job->job_vacancy_end_date->format('d M Y') }}',
                                        canApply: {{ auth()->check() ? ($userHasApplied ? 'false' : 'true') : 'false' }},
                                        appliedThis: {{ ($userHasApplied && $appliedJobId == $job->job_vacancy_id) ? 'true' : 'false' }},
                                        isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                                        vacancyId: '{{ $job->job_vacancy_id }}'
                                    })">
                                        Lihat detail
                                    </button>
                                </div>
                                
                                <div class="mb-2">
                                    <h6 class="text-success mb-1" style="font-size: 0.9rem;">
                                        <i class="bi bi-check-circle me-1"></i>Persyaratan:
                                    </h6>
                                    <div class="job-spec">{!! $job->job_vacancy_job_spec !!}</div>
                                </div>
                                
                                <div class="row mb-2">
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
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <small class="text-info">
                                    <i class="bi bi-people me-1"></i>
                                    @php
                                        $hiredCount = $job->hired_count ?? 0;
                                        $manPower = $job->job_vacancy_man_power ?? 0;
                                        $available = max(0, $manPower - $hiredCount);
                                    @endphp
                                    {{ $available }} posisi tersedia
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
                    @if(isset($hasProfile) && $hasProfile)
                    <a href="{{ route('applicant.edit', $profileId) }}" class="btn btn-primary mt-3">
                        <i class="bi bi-pencil-square me-2"></i>Edit Data Diri
                    </a>
                    @else
                    <a href="{{ route('applicant.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-person-plus-fill me-2"></i>Lengkapi Data Diri
                    </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-person-plus-fill me-2"></i>Login Terlebih Dahulu
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<script>
    (function(){
        const overlay = document.createElement('div');
        overlay.className = 'cm-overlay';
        overlay.innerHTML = `
            <div class="cm-dialog">
                <div class="cm-header">
                    <h5 class="m-0" id="cm-title"></h5>
                    <button class="cm-close" aria-label="Close" id="cm-close">×</button>
            </div>
                <div class="cm-body">
                    <div class="mb-2"><span class="job-level" id="cm-level"></span></div>
                    <h6 class="text-primary mb-2">Deskripsi Pekerjaan</h6>
                    <div class="mb-3" id="cm-desc" style="white-space: pre-line;"></div>
                    <h6 class="text-success mb-2">Persyaratan</h6>
                    <div class="mb-3" id="cm-spec" style="white-space: pre-line;"></div>
        </div>
                <div class="cm-footer">
                    <small class="text-muted" id="cm-dates"></small>
                    <div id="cm-actions"></div>
                    </div>
            </div>`;
        document.body.appendChild(overlay);

        function close() { overlay.classList.remove('show'); document.body.style.overflow = ''; }
        document.getElementById('cm-close').addEventListener('click', close);
        overlay.addEventListener('click', function(e){ if (e.target === overlay) close(); });

        window.openJobDetail = function(data){
            document.getElementById('cm-title').textContent = data.name;
            document.getElementById('cm-level').textContent = data.level || '';
            document.getElementById('cm-desc').innerHTML = data.desc || '';
            document.getElementById('cm-spec').innerHTML = data.spec || '';
            document.getElementById('cm-dates').innerHTML = `
                <i class="bi bi-calendar-event me-1"></i> Mulai: ${data.start}
                &nbsp;&nbsp;
                <i class="bi bi-calendar-check me-1"></i> Berakhir: ${data.end}`;

            const actions = document.getElementById('cm-actions');
            actions.innerHTML = '';
            if (data.appliedThis) {
                const span = document.createElement('span');
                span.className = 'applied-badge';
                span.innerHTML = '<i class="bi bi-check-circle me-1"></i>Sudah Melamar';
                actions.appendChild(span);
            } else if (data.isLoggedIn && data.canApply) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('job-vacancy.apply') }}';
                form.innerHTML = `@csrf<input type="hidden" name="job_vacancy_id" value="${data.vacancyId}">`;
                const btn = document.createElement('button');
                btn.type = 'submit';
                btn.className = 'btn apply-btn';
                btn.innerHTML = '<i class="bi bi-send me-1"></i>Lamar Sekarang';
                form.appendChild(btn);
                actions.appendChild(form);
            } else if (!data.isLoggedIn) {
                const a = document.createElement('a');
                a.href = '{{ route('login') }}';
                a.className = 'btn apply-btn';
                a.innerHTML = '<i class="bi bi-person-plus me-1"></i>Login untuk Melamar';
                actions.appendChild(a);
            } else {
                const btn = document.createElement('button');
                btn.className = 'btn apply-btn';
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-x-circle me-1"></i>Sudah Melamar Pekerjaan Lain';
                actions.appendChild(btn);
            }

            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    })();
</script>
</script>
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
