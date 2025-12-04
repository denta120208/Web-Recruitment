@extends('layouts.app')

@section('title', 'Detail Applicant - Metland Recruitment')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user"></i> Detail Applicant
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('applicant.print', $applicant->requireid) }}" 
                           class="btn btn-light btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <a href="{{ route('applicant.pdf', $applicant->requireid) }}" 
                           class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </a>
                        @auth
                            @if(auth()->user()->id == $applicant->user_id)
                                <a href="{{ route('applicant.edit', $applicant->requireid) }}" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Personal Data Section -->
                    <div class="section mb-4">
                        <h5 class="section-title text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-user-circle"></i> Data Pribadi
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Nama Lengkap:</strong></div>
                                    <div class="col-sm-8">{{ $applicant->firstname }} {{ $applicant->middlename }} {{ $applicant->lastname }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Jenis Kelamin:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge badge-{{ $applicant->gender == 'Male' ? 'primary' : 'pink' }}">
                                            {{ $applicant->gender == 'Male' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Tanggal Lahir:</strong></div>
                                    <div class="col-sm-8">{{ $applicant->dateofbirth ? $applicant->dateofbirth->format('d F Y') : '-' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Alamat:</strong></div>
                                    <div class="col-sm-8">{{ $applicant->address ?? '-' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Kota:</strong></div>
                                    <div class="col-sm-8">{{ $applicant->city ?? '-' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8">
                                        <a href="mailto:{{ $applicant->gmail }}">{{ $applicant->gmail ?? '-' }}</a>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Telepon:</strong></div>
                                    <div class="col-sm-8">{{ $applicant->phone ?? '-' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>LinkedIn:</strong></div>
                                    <div class="col-sm-8">
                                        @if($applicant->linkedin)
                                            <a href="{{ $applicant->linkedin }}" target="_blank">{{ $applicant->linkedin }}</a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Instagram:</strong></div>
                                    <div class="col-sm-8">{{ $applicant->instagram ?? '-' }}</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 text-center">
                                @if($applicant->photopath)
                                    <div class="photo-frame">
                                        <img src="{{ route('applicant.file', ['type' => 'photo']) }}" 
                                             alt="Photo" class="img-thumbnail" style="max-width: 200px; max-height: 250px;">
                                    </div>
                                @else
                                    <div class="photo-placeholder bg-light border rounded d-flex align-items-center justify-content-center" 
                                         style="width: 200px; height: 250px; margin: 0 auto;">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Education Section -->
                    <div class="section mb-4">
                        <h5 class="section-title text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-graduation-cap"></i> Riwayat Pendidikan
                        </h5>
                        
                        @if(count($educations) > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tingkat</th>
                                            <th>Nama Institusi</th>
                                            <th>Jurusan</th>
                                            <th>Tahun</th>
                                            <th>Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($educations as $education)
                                        <tr>
                                            <td>{{ $education->education->educationname ?? '-' }}</td>
                                            <td>{{ $education->institutionname ?? '-' }}</td>
                                            <td>{{ $education->major ?? '-' }}</td>
                                            <td>
                                                {{ $education->startdate ? \Carbon\Carbon::parse($education->startdate)->format('Y') : '' }}
                                                @if($education->enddate)
                                                    - {{ \Carbon\Carbon::parse($education->enddate)->format('Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $education->score ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada data pendidikan.</p>
                        @endif
                    </div>

                    <!-- Work Experience Section -->
                    <div class="section mb-4">
                        <h5 class="section-title text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-briefcase"></i> Pengalaman Kerja
                        </h5>
                        
                        @if(count($workExperiences) > 0)
                            @foreach($workExperiences as $work)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="card-title text-primary">{{ $work->joblevel ?? 'Posisi tidak disebutkan' }}</h6>
                                            <p class="card-text">
                                                <strong>{{ $work->companyname ?? 'Perusahaan tidak disebutkan' }}</strong>
                                            </p>
                                            <p class="text-muted small">
                                                <i class="fas fa-calendar"></i>
                                                {{ $work->startdate ? \Carbon\Carbon::parse($work->startdate)->format('M Y') : 'Tidak disebutkan' }}
                                                - 
                                                @if($work->iscurrent)
                                                    <span class="badge badge-success">Sekarang</span>
                                                @else
                                                    {{ $work->enddate ? \Carbon\Carbon::parse($work->enddate)->format('M Y') : 'Tidak disebutkan' }}
                                                @endif
                                            </p>
                                            @if($work->eexp_comments)
                                                <p class="card-text"><small>{{ $work->eexp_comments }}</small></p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-right">
                                            @if($work->salary)
                                                <span class="badge badge-info">
                                                    Rp {{ number_format($work->salary, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">Belum ada data pengalaman kerja.</p>
                        @endif
                    </div>

                    <!-- Training Section -->
                    @if(count($trainings) > 0)
                    <div class="section mb-4">
                        <h5 class="section-title text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-certificate"></i> Pelatihan & Sertifikasi
                        </h5>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama Pelatihan</th>
                                        <th>No. Sertifikat</th>
                                        <th>Periode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trainings as $training)
                                    <tr>
                                        <td>{{ $training->trainingname ?? '-' }}</td>
                                        <td>{{ $training->certificateno ?? '-' }}</td>
                                        <td>
                                            {{ $training->starttrainingdate ? \Carbon\Carbon::parse($training->starttrainingdate)->format('M Y') : '' }}
                                            @if($training->endtrainingdate)
                                                - {{ \Carbon\Carbon::parse($training->endtrainingdate)->format('M Y') }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Files Section -->
                    <div class="section mb-4">
                        <h5 class="section-title text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-file"></i> Dokumen
                        </h5>
                        
                        <div class="row">
                            @if($applicant->cvpath)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                        <h6>Curriculum Vitae</h6>
                                        <a href="{{ route('applicant.file', ['type' => 'cv']) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat CV
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($applicant->photopath)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="fas fa-image fa-3x text-info mb-2"></i>
                                        <h6>Foto</h6>
                                        <a href="{{ route('applicant.file', ['type' => 'photo']) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat Foto
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .photo-frame {
        border: 3px solid #dee2e6;
        border-radius: 8px;
        padding: 5px;
        display: inline-block;
    }
    
    .badge-pink {
        background-color: #e91e63;
        color: white;
    }
    
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }
</style>
@endsection
