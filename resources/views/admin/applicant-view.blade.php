@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Detail Applicant</h2>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">{{ $applicant->firstname }} {{ $applicant->lastname }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $applicant->gmail }}</p>
            <p class="card-text"><strong>Phone:</strong> {{ $applicant->phone }}</p>
            <p class="card-text"><strong>Gender:</strong> {{ $applicant->gender }}</p>
            <p class="card-text"><strong>Date of Birth:</strong> {{ $applicant->dateofbirth ? $applicant->dateofbirth->format('d M Y') : '-' }}</p>
            <p class="card-text"><strong>Address:</strong> {{ $applicant->address }}</p>
            <p class="card-text"><strong>City:</strong> {{ $applicant->city }}</p>
            <p class="card-text"><strong>LinkedIn:</strong> {{ $applicant->linkedin }}</p>
            <p class="card-text"><strong>Instagram:</strong> {{ $applicant->instagram }}</p>
            <p class="card-text"><strong>CV:</strong> @if($applicant->cvpath)<a href="{{ route('admin.file.applicant', ['requireId' => $applicant->getKey(), 'type' => 'cv']) }}" target="_blank">Download CV</a>@else - @endif</p>
            <p class="card-text"><strong>Photo:</strong> @if($applicant->photopath)<a href="{{ route('admin.file.applicant', ['requireId' => $applicant->getKey(), 'type' => 'photo']) }}" target="_blank">View Photo</a>@else - @endif</p>
        </div>
    </div>
</div>
@endsection
