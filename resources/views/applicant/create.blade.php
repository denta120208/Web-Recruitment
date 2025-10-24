@extends('layouts.app')

@section('title', 'Form Data Diri - Metland Recruitment')

@section('styles')
<style>
    .form-section {
        margin-bottom: 3rem;
    }
    
    .section-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .section-header {
        background: #009290;
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
        margin: 0;
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #667eea;
    }
    
    .dynamic-section {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .dynamic-section:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
    
    .file-upload-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .file-upload-input {
        position: absolute;
        left: -9999px;
    }
    
    .file-upload-label {
        display: block;
        padding: 2rem;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    
    .file-upload-label:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .file-upload-label.dragover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }
    
    .progress {
        height: 8px;
        border-radius: 10px;
    }
    
    .step-progress {
        background: #e9ecef;
        border-radius: 10px;
        height: 6px;
        margin-bottom: 2rem;
    }
    
    .step-progress-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        height: 100%;
        transition: width 0.3s ease;
    }

    @media (max-width: 768px) {
        .section-card {
            margin-bottom: 1.5rem;
        }

        .section-header h3 {
            font-size: 1.25rem;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        .form-floating > label {
            font-size: 0.9rem;
        }

        .file-upload-label {
            padding: 1.5rem;
        }

        .file-upload-label h6 {
            font-size: 0.9rem;
        }

        .file-upload-label small {
            font-size: 0.75rem;
        }

        .dynamic-section {
            padding: 1rem;
        }

        .btn {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .section-header {
            padding: 1rem;
        }

        .section-header h3 {
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1rem !important;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .form-floating > label {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }

        .file-upload-label {
            padding: 1rem;
        }

        .file-upload-label i {
            font-size: 1.5rem !important;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .btn-lg {
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <!-- Progress Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="step-progress">
                <div class="step-progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Form Header -->
    
    <form id="applicantForm" action="{{ route('applicant.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Personal Information Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card section-card">
                    <div class="section-header">
                        <h3 class="mb-0">
                            <i class="bi bi-person-fill me-2"></i>
                            Informasi Pribadi
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('FirstName') is-invalid @enderror" 
                                           id="FirstName" name="FirstName" placeholder="Nama Depan" 
                                           value="{{ old('FirstName') }}" required>
                                    <label for="FirstName">Nama Depan *</label>
                                    @error('FirstName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('MiddleName') is-invalid @enderror" 
                                           id="MiddleName" name="MiddleName" placeholder="Nama Tengah" 
                                           value="{{ old('MiddleName') }}">
                                    <label for="MiddleName">Nama Tengah</label>
                                    @error('MiddleName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                        <input type="text" class="form-control @error('LastName') is-invalid @enderror" 
                                 id="LastName" name="LastName" placeholder="Nama Belakang" 
                                 value="{{ old('LastName') }}">
                             <label for="LastName">Nama Belakang</label>
                                    @error('LastName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('Gender') is-invalid @enderror" 
                                            id="Gender" name="Gender" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Male" {{ old('Gender') == 'Male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Female" {{ old('Gender') == 'Female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <label for="Gender">Jenis Kelamin *</label>
                                    @error('Gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control @error('DateOfBirth') is-invalid @enderror" 
                                           id="DateOfBirth" name="DateOfBirth" 
                                           value="{{ old('DateOfBirth') }}" required>
                                    <label for="DateOfBirth">Tanggal Lahir *</label>
                                    @error('DateOfBirth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control @error('Address') is-invalid @enderror" 
                                              id="Address" name="Address" placeholder="Alamat Lengkap" 
                                              style="height: 100px" required>{{ old('Address') }}</textarea>
                                    <label for="Address">Alamat Lengkap *</label>
                                    @error('Address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('City') is-invalid @enderror" 
                                           id="City" name="City" placeholder="Kota" 
                                           value="{{ old('City') }}" required>
                                    <label for="City">Kota *</label>
                                    @error('City')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control @error('Phone') is-invalid @enderror" 
                                           id="Phone" name="Phone" placeholder="Nomor Telepon" 
                                           value="{{ old('Phone') }}" required>
                                    <label for="Phone">Nomor Telepon *</label>
                                    @error('Phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card section-card">
                    <div class="section-header">
                        <h3 class="mb-0">
                            <i class="bi bi-envelope-fill me-2"></i>
                            Informasi Kontak
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('Gmail') is-invalid @enderror" 
                                           id="Gmail" name="Gmail" placeholder="Email" 
                                           value="{{ old('Gmail') }}" required>
                                    <label for="Gmail">Email *</label>
                                    @error('Gmail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="url" class="form-control @error('LinkedIn') is-invalid @enderror" 
                                           id="LinkedIn" name="LinkedIn" placeholder="LinkedIn URL" 
                                           value="{{ old('LinkedIn') }}">
                                    <label for="LinkedIn">LinkedIn URL</label>
                                    @error('LinkedIn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('Instagram') is-invalid @enderror" 
                                           id="Instagram" name="Instagram" placeholder="Instagram Username" 
                                           value="{{ old('Instagram') }}">
                                    <label for="Instagram">Instagram Username</label>
                                    @error('Instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card section-card">
                    <div class="section-header">
                        <h3 class="mb-0">
                            <i class="bi bi-cloud-upload-fill me-2"></i>
                            Upload Dokumen
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="file-upload-wrapper">
                                    <input type="file" class="file-upload-input @error('CVPath') is-invalid @enderror" 
                                           id="CVPath" name="CVPath" accept=".pdf,.doc,.docx">
                                    <label for="CVPath" class="file-upload-label">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 2rem;"></i>
                                        <h6 class="mt-2">Upload CV</h6>
                                        <small class="text-muted">PDF Only (Max 5MB)</small>
                                        <br><small class="text-info">❗Harap menamai file dengan nama Anda</small>
                                    </label>
                                    @error('CVPath')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="file-upload-wrapper">
                                    <input type="file" class="file-upload-input @error('PhotoPath') is-invalid @enderror" 
                                           id="PhotoPath" name="PhotoPath" accept="image/*">
                                    <label for="PhotoPath" class="file-upload-label">
                                        <i class="bi bi-camera-fill text-primary" style="font-size: 2rem;"></i>
                                        <h6 class="mt-2">Upload Foto</h6>
                                        <small class="text-muted">IMAGE Only (Max 5MB)</small>
                                        <br><small class="text-info">❗Harap menamai file dengan nama Anda</small>
                                    </label>
                                    @error('PhotoPath')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



 <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card section-card">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="bi bi-mortarboard-fill me-2"></i>
                            Pendidikan
                        </h3>
                        <button type="button" class="btn btn-light btn-sm" id="addEducation">
                            <i class="bi bi-plus-circle-fill me-1"></i>Tambah
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="educationContainer">
                            <!-- Education items will be added here dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Work Experience Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card section-card">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="bi bi-briefcase-fill me-2"></i>
                            Pengalaman Kerja
                        </h3>
                        <button type="button" class="btn btn-light btn-sm" id="addWorkExperience">
                            <i class="bi bi-plus-circle-fill me-1"></i>Tambah
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="workExperienceContainer">
                            <!-- Work experience items will be added here dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education Section -->
       

        <!-- Training Section -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card section-card">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="bi bi-award-fill me-2"></i>
                            Pelatihan & Sertifikasi
                        </h3>
                        <button type="button" class="btn btn-light btn-sm" id="addTraining">
                            <i class="bi bi-plus-circle-fill me-1"></i>Tambah
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div id="trainingContainer">
                            <!-- Training items will be added here dynamically -->
                        </div>
                    </div>
                </div>
            </div>
        </div>  

        <!-- Submit Button -->
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn" style="background: #009290; border-color: #009290;">
                        <i class="bi bi-check-circle-fill me-2"></i>Kirim Profil
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg px-5" onclick="history.back()">
                        <i class="bi bi-arrow-left-circle-fill me-2"></i>Kembali
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let workExpCount = 0;
    let educationCount = 0;
    let trainingCount = 0;
    
    // Update progress bar
    function updateProgress() {
        const totalSections = 6;
        let completedSections = 0;
        
        // Check personal info
        if ($('#FirstName').val() && $('#Gender').val() && $('#DateOfBirth').val()) {
            completedSections++;
        }
        
        // Check contact info
        if ($('#Gmail').val() && $('#Phone').val()) {
            completedSections++;
        }
        
        // Check files
        if ($('#CVPath')[0].files.length > 0) {
            completedSections++;
        }
        
        // Check work experience
        if ($('#workExperienceContainer .dynamic-section').length > 0) {
            completedSections++;
        }
        
        // Check education
        if ($('#educationContainer .dynamic-section').length > 0) {
            completedSections++;
        }
        
        // Check training
        if ($('#trainingContainer .dynamic-section').length > 0) {
            completedSections++;
        }
        
        const progress = (completedSections / totalSections) * 100;
        $('#progressBar').css('width', progress + '%');
    }
    
    // Add work experience
    $('#addWorkExperience').click(function() {
        workExpCount++;
        const workExpHtml = `
            <div class="dynamic-section position-relative" data-index="${workExpCount}">
                <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeWorkExperience(${workExpCount})">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="work_experiences[${workExpCount}][CompanyName]" placeholder="Nama Perusahaan">
                            <label>Nama Perusahaan</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="work_experiences[${workExpCount}][JobLevel]" placeholder="Posisi/Jabatan">
                            <label>Posisi/Jabatan</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="work_experiences[${workExpCount}][StartDate]" placeholder="Tanggal Mulai">
                            <label>Tanggal Mulai</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="work_experiences[${workExpCount}][EndDate]" placeholder="Tanggal Selesai">
                            <label>Tanggal Selesai</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="number" class="form-control" name="work_experiences[${workExpCount}][Salary]" placeholder="Gaji" step="0.01">
                            <label>Gaji</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="is_current_${workExpCount}" name="work_experiences[${workExpCount}][is_current]" value="1" onchange="toggleCurrent(this, ${workExpCount})">
                            <label class="form-check-label" for="is_current_${workExpCount}">Masih bekerja di sini</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#workExperienceContainer').append(workExpHtml);
        updateProgress();
    });
    
    // Add education
    $('#addEducation').click(function() {
        educationCount++;
        const educationHtml = `
            <div class="dynamic-section position-relative" data-index="${educationCount}">
                <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeEducation(${educationCount})">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="educations[${educationCount}][InstitutionName]" placeholder="Nama Institusi">
                            <label>Nama Institusi</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="educations[${educationCount}][Major]" placeholder="Jurusan">
                            <label>Jurusan</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="educations[${educationCount}][StartDate]" placeholder="Tanggal Mulai">
                            <label>Tanggal Mulai</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="educations[${educationCount}][EndDate]" placeholder="Tanggal Selesai">
                            <label>Tanggal Selesai</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#educationContainer').append(educationHtml);
        updateProgress();
    });
    
    // Add training
    $('#addTraining').click(function() {
        trainingCount++;
        const trainingHtml = `
            <div class="dynamic-section position-relative" data-index="${trainingCount}">
                <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeTraining(${trainingCount})">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="trainings[${trainingCount}][TrainingName]" placeholder="Nama Pelatihan">
                            <label>Nama Pelatihan</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="trainings[${trainingCount}][CertificateNo]" placeholder="Nomor Sertifikat">
                            <label>Nomor Sertifikat</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="trainings[${trainingCount}][StartTrainingDate]" placeholder="Tanggal Mulai">
                            <label>Tanggal Mulai</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="trainings[${trainingCount}][EndTrainingDate]" placeholder="Tanggal Selesai">
                            <label>Tanggal Selesai</label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#trainingContainer').append(trainingHtml);
        updateProgress();
    });
    
    // File upload handling with validation
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

    function showFileInfo(inputEl) {
        const file = inputEl.files[0];
        const label = $(inputEl).siblings('.file-upload-label');

        if (file) {
            label.html(`
                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                <h6 class="mt-2">${file.name}</h6>
                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
            `);
        } else {
            // revert to default label text if no file
            const defaultText = $(label).data('default') || $(label).html();
            $(label).html(defaultText);
        }
    }

    function validateFile(inputEl) {
        const file = inputEl.files[0];
        const id = inputEl.id;
        const $input = $(inputEl);
        let valid = true;
        let message = '';

        if (!file) {
            // no file selected: not invalid at this stage (server will enforce required if needed)
            $input.removeClass('is-invalid');
            $input.next('.invalid-feedback').remove();
            return true;
        }

        if (file.size > MAX_FILE_SIZE) {
            valid = false;
            message = 'File terlalu besar. Maksimum 5 MB.';
        }

        if (id === 'CVPath') {
            const allowedExt = ['pdf'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedExt.includes(ext) || file.type !== 'application/pdf') {
                valid = false;
                message = 'CV harus berformat PDF.';
            }
        }

        if (id === 'PhotoPath') {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(file.type) && !['jpg','jpeg','png'].includes(ext)) {
                valid = false;
                message = 'Foto harus berupa image (JPG/PNG).';
            }
        }

        // remove previous feedback
        $input.removeClass('is-invalid');
        $input.nextAll('.invalid-feedback').remove();

        if (!valid) {
            $input.addClass('is-invalid');
            $input.after(`<div class="invalid-feedback d-block">${message}</div>`);
        } else {
            showFileInfo(inputEl);
        }

        updateProgress();
        return valid;
    }

    // store default label html so we can restore when clearing
    $('.file-upload-label').each(function() { $(this).data('default', $(this).html()); });

    $('.file-upload-input').on('change', function() {
        validateFile(this);
    });
    
    // Drag and drop for file uploads
    $('.file-upload-label').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });
    
    $('.file-upload-label').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });
    
    $('.file-upload-label').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $(this).siblings('.file-upload-input')[0].files = files;
            $(this).trigger('change');
        }
    });
    
    // Form validation: only validate explicit required fields so optional fields
    // (LinkedIn, Instagram, work experiences, trainings) do NOT block submission.
    $('#applicantForm').on('submit', function(e) {
        let isValid = true;

        // Whitelist of required field IDs
    const requiredIds = ['#FirstName', '#Gender', '#DateOfBirth', '#Gmail', '#Phone'];

        requiredIds.forEach(function(selector) {
            const el = $(selector);
            if (!el.val()) {
                el.addClass('is-invalid');
                isValid = false;
            } else {
                el.removeClass('is-invalid');
            }
        });

        // Validate files explicitly before submit
        const cvValid = validateFile(document.getElementById('CVPath'));
        const photoValid = validateFile(document.getElementById('PhotoPath'));

        // Require at least one education entry
        if ($('#educationContainer .dynamic-section').length === 0) {
            isValid = false;
            alert('Mohon tambahkan minimal 1 data pendidikan sebelum mengirim profil.');
            e.preventDefault();
            return false;
        }

        if (!isValid || !cvValid || !photoValid) {
            e.preventDefault();
            let msg = 'Mohon lengkapi semua field yang wajib diisi!';
            if (!cvValid || !photoValid) {
                msg = 'Mohon perbaiki file yang diupload: pastikan tipe dan ukuran sesuai.';
            }
            alert(msg);
        }
    });

    // Real-time validation for the same whitelist
    $('input, select').on('blur', function() {
        const requiredIds = ['FirstName', 'LastName', 'Gender', 'DateOfBirth', 'Gmail', 'Phone'];
        if (requiredIds.includes(this.id) && !$(this).val()) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
        updateProgress();
    });
    
    // Update progress on input change
    $('input, select').on('input change', updateProgress);
});

// Remove functions
function removeWorkExperience(index) {
    $(`.dynamic-section[data-index="${index}"]`).remove();
    updateProgress();
}

// Toggle EndDate disabled/cleared when 'is current' checkbox is checked
function toggleCurrent(el, index) {
    var $section = $(`.dynamic-section[data-index="${index}"]`);
    var $end = $section.find(`input[name="work_experiences[${index}][EndDate]"]`);
    if ($(el).is(':checked')) {
        $end.val('').prop('disabled', true);
    } else {
        $end.prop('disabled', false);
    }
}

function removeEducation(index) {
    $(`.dynamic-section[data-index="${index}"]`).remove();
    updateProgress();
}

function removeTraining(index) {
    $(`.dynamic-section[data-index="${index}"]`).remove();
    updateProgress();
}

function updateProgress() {
    const totalSections = 6;
    let completedSections = 0;
    
    if ($('#FirstName').val() && $('#Gender').val() && $('#DateOfBirth').val()) {
        completedSections++;
    }
    if ($('#Gmail').val() && $('#Phone').val()) {
        completedSections++;
    }
    if ($('#CVPath')[0].files.length > 0) {
        completedSections++;
    }
    if ($('#workExperienceContainer .dynamic-section').length > 0) {
        completedSections++;
    }
    if ($('#educationContainer .dynamic-section').length > 0) {
        completedSections++;
    }
    if ($('#trainingContainer .dynamic-section').length > 0) {
        completedSections++;
    }
    
    const progress = (completedSections / totalSections) * 100;
    $('#progressBar').css('width', progress + '%');
}

// Toggle EndDate disabled/cleared when 'is current' checkbox is checked
function toggleCurrent(el, index) {
    var $section = $(`.dynamic-section[data-index="${index}"]`);
    var $end = $section.find(`input[name="work_experiences[${index}][EndDate]"]`);
    if ($(el).is(':checked')) {
        $end.val('').prop('disabled', true);
    } else {
        $end.prop('disabled', false);
    }
}
</script>
@endsection
