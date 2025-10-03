@extends('layouts.app')

@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Syarat & Ketentuan Pengelolaan Data</div>
                <div class="card-body">
                    <p>Dengan mencentang dan menyetujui syarat ini, Anda menyetujui bahwa data pribadi Anda akan dikelola oleh Metropolitan Land Tbk untuk keperluan proses rekrutmen dan administrasi terkait.</p>
                    <p>Data yang dikumpulkan termasuk (tetapi tidak terbatas pada): nama, tanggal lahir, alamat, email, nomor telepon, CV, foto, riwayat pendidikan dan pekerjaan. Data akan disimpan sesuai dengan kebijakan privasi dan hanya digunakan untuk keperluan yang tercantum di atas.</p>

                    <form method="POST" action="{{ route('terms.accept') }}">
                        @csrf
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="acceptCheck" name="accept" required>
                            <label class="form-check-label" for="acceptCheck">Saya menyetujui bahwa data saya dikelola oleh Metropolitan Land Tbk sesuai syarat & ketentuan di atas.</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-secondary">Keluar</a>
                            <button type="submit" class="btn btn-primary">Setujui & Lanjutkan</button>
                        </div>
                    </form>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
