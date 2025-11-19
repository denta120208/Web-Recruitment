@extends('layouts.app')

@section('title', 'Hapus Akun - Metland Recruitment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Akun Permanen</h4>
                </div>
                <div class="card-body">
                    @if($isEmployee)
                        <div class="alert alert-warning" role="alert">
                            <h5><i class="fas fa-briefcase me-2"></i>AKUN TIDAK DAPAT DIHAPUS</h5>
                            <p class="mb-2">Akun Anda tidak dapat dihapus karena:</p>
                            <ul class="mb-2">
                                <li><strong>Anda sudah menjadi karyawan di sistem HRIS</strong></li>
                                <li>Data Anda telah terintegrasi dengan sistem kepegawaian</li>
                                <li>Penghapusan akun karyawan harus melalui proses resmi</li>
                            </ul>
                            <p class="mb-0"><strong>Silakan hubungi admin atau HR untuk bantuan lebih lanjut.</strong></p>
                        </div>
                    @elseif($hiredNotGenerated)
                        <div class="alert alert-info" role="alert">
                            <h5><i class="fas fa-clock me-2"></i>AKUN SEDANG DIPROSES</h5>
                            <p class="mb-2">Akun Anda tidak dapat dihapus karena:</p>
                            <ul class="mb-2">
                                <li><strong>Anda telah diterima bekerja (status: Hired)</strong></li>
                                <li>Data Anda sedang diproses untuk menjadi karyawan</li>
                                <li>Proses integrasi ke sistem HRIS sedang berlangsung</li>
                            </ul>
                            <p class="mb-0"><strong>Silakan hubungi admin untuk bantuan lebih lanjut.</strong></p>
                        </div>
                    @else
                        <div class="alert alert-danger" role="alert">
                            <h5><i class="fas fa-warning me-2"></i>PERINGATAN PENTING!</h5>
                            <p class="mb-2">Tindakan ini akan <strong>MENGHAPUS PERMANEN</strong> semua data Anda:</p>
                            <ul class="mb-2">
                                <li>Akun pengguna dan profil</li>
                                <li>Data lamaran kerja (CV, foto, dokumen)</li>
                                <li>Riwayat pendidikan dan pengalaman kerja</li>
                                <li>Semua aplikasi pekerjaan yang pernah diajukan</li>
                                <li>File-file yang telah diunggah</li>
                            </ul>
                            <p class="mb-0"><strong>Tindakan ini TIDAK DAPAT DIBATALKAN!</strong></p>
                        </div>
                    @endif

                    @if($applicant)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Data yang akan dihapus:</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nama:</strong> {{ $applicant->firstname }} {{ $applicant->middlename }} {{ $applicant->lastname }}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>Telepon:</strong> {{ $applicant->phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Kota:</strong> {{ $applicant->city }}</p>
                                    <p><strong>Terdaftar:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                    @if($applicant->cvpath)
                                    <p><strong>CV:</strong> <i class="fas fa-check text-success"></i> Tersedia</p>
                                    @endif
                                    @if($applicant->photopath)
                                    <p><strong>Foto:</strong> <i class="fas fa-check text-success"></i> Tersedia</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
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

                    @if($isEmployee || $hiredNotGenerated)
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('applicant.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('applicant.delete.account.post') }}" id="deleteAccountForm">
                            @csrf

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <strong>Masukkan password Anda untuk konfirmasi:</strong>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       required
                                       placeholder="Password akun Anda">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input @error('confirm_delete') is-invalid @enderror" 
                                           id="confirmDelete" 
                                           name="confirm_delete" 
                                           value="1"
                                           required>
                                    <label class="form-check-label" for="confirmDelete">
                                        <strong>Saya memahami bahwa tindakan ini tidak dapat dibatalkan dan semua data saya akan dihapus permanen.</strong>
                                    </label>
                                    @error('confirm_delete')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="confirmFinal" 
                                           required>
                                    <label class="form-check-label" for="confirmFinal">
                                        <strong>Saya yakin ingin menghapus akun saya sekarang.</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('applicant.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-danger" id="deleteBtn" disabled>
                                    <i class="fas fa-trash me-2"></i>Hapus Akun Permanen
                                </button>
                            </div>
                        </form>
                    @endif

                    <div class="mt-4 text-muted">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Jika Anda mengalami masalah atau ingin bantuan, silakan hubungi tim support kami sebelum menghapus akun.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmDelete = document.getElementById('confirmDelete');
    const confirmFinal = document.getElementById('confirmFinal');
    const deleteBtn = document.getElementById('deleteBtn');
    const passwordInput = document.getElementById('password');

    function checkFormValidity() {
        const isValid = confirmDelete.checked && 
                       confirmFinal.checked && 
                       passwordInput.value.length > 0;
        deleteBtn.disabled = !isValid;
    }

    confirmDelete.addEventListener('change', checkFormValidity);
    confirmFinal.addEventListener('change', checkFormValidity);
    passwordInput.addEventListener('input', checkFormValidity);

    document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
        if (!confirm('PERINGATAN TERAKHIR!\n\nApakah Anda benar-benar yakin ingin menghapus akun ini?\n\nTindakan ini TIDAK DAPAT DIBATALKAN!')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
