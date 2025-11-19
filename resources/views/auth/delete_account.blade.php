@extends('layouts.app')

@section('title', 'Hapus Akun - Metland Recruitment')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    Hapus Akun Anda
                </div>
                <div class="card-body">
                    <p class="text-danger">Peringatan: tindakan ini akan menghapus akun Anda beserta data profil lamaran kerja (CV, foto, dsb). Tindakan ini tidak dapat dibatalkan.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.delete.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">Masukkan password Anda untuk konfirmasi</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="confirmDelete" required>
                            <label class="form-check-label" for="confirmDelete">Saya mengerti bahwa data saya akan dihapus permanen.</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-danger">Hapus Akun</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
