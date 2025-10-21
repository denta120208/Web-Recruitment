@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Detail Applicant</h2>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">{{ $applicant->FirstName }} {{ $applicant->LastName }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $applicant->Gmail }}</p>
            <p class="card-text"><strong>Phone:</strong> {{ $applicant->Phone }}</p>
            <p class="card-text"><strong>Gender:</strong> {{ $applicant->Gender }}</p>
            <p class="card-text"><strong>Date of Birth:</strong> {{ $applicant->DateOfBirth ? $applicant->DateOfBirth->format('d M Y') : '-' }}</p>
            <p class="card-text"><strong>Address:</strong> {{ $applicant->Address }}</p>
            <p class="card-text"><strong>City:</strong> {{ $applicant->City }}</p>
            <p class="card-text"><strong>LinkedIn:</strong> {{ $applicant->LinkedIn }}</p>
            <p class="card-text"><strong>Instagram:</strong> {{ $applicant->Instagram }}</p>
            <p class="card-text"><strong>CV:</strong> @if($applicant->CVPath)<a href="{{ route('admin.file.applicant', ['requireId' => $applicant->RequireID, 'type' => 'cv']) }}" target="_blank">Download CV</a>@else - @endif</p>
            <p class="card-text"><strong>Photo:</strong> @if($applicant->PhotoPath)<a href="{{ route('admin.file.applicant', ['requireId' => $applicant->RequireID, 'type' => 'photo']) }}" target="_blank">View Photo</a>@else - @endif</p>
        </div>
    </div>
</div>
@endsection
