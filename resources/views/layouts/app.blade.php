<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Metland Recruitment')</title>
    <!-- Favicon / touch icon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/applicants/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('storage/applicants/logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/logo.png') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
        }

        html {
            overflow-x: hidden;
            width: 100%;
        }

        * {
            box-sizing: border-box;
        }

        .container, .container-fluid {
            max-width: 100%;
            overflow-x: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .navbar-brand img {
            height: 36px;
            width: auto;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-nav {
            gap: 1rem;
        }

        .navbar-nav .nav-item {
            margin: 0;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(0, 146, 144, 0.1);
            color: #009290 !important;
        }

        .navbar-nav .btn-link {
            text-decoration: none;
            border: none;
            background: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .navbar-nav .btn-link:hover {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545 !important;
        }

        .hero-section {
            background: var(--gradient-bg);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .card-header {
            background: var(--gradient-bg);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: var(--gradient-bg);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-outline-primary {
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 2px;
        }

        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.05);
        }

        .file-upload-area.dragover {
            border-color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.1);
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
        }

        .step {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .step.active .step-number {
            background: var(--secondary-color);
            color: white;
        }

        .step.completed .step-number {
            background: var(--success-color);
            color: white;
        }

        .step-line {
            width: 50px;
            height: 2px;
            background: #e9ecef;
            margin: 0 1rem;
        }

        .step.completed + .step .step-line {
            background: var(--success-color);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
        }

        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
        }

        .floating-action-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        footer {
            width: 100%;
            overflow-x: hidden;
        }

        footer .container {
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }
            
            .step-indicator {
                flex-direction: column;
                align-items: center;
            }
            
            .step-line {
                display: none;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-brand img {
                height: 28px;
            }

            .navbar-brand span {
                font-size: 0.9rem !important;
            }

            .navbar-nav {
                gap: 0.5rem;
                padding: 1rem 0;
            }

            .navbar-nav .nav-link {
                padding: 0.5rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .hero-section h1 {
                font-size: 1.75rem;
            }

            .hero-section .lead {
                font-size: 1rem;
            }

            .hero-section .bi-briefcase-fill {
                font-size: 8rem !important;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                margin-left: 0.5rem !important;
            }

            .navbar-toggler {
                margin-right: 0.5rem;
            }

            .hero-section h1 {
                font-size: 1.5rem;
            }

            .hero-section .bi-briefcase-fill {
                font-size: 5rem !important;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1rem !important;
            }

            footer h5 {
                font-size: 1.1rem;
            }

            footer p {
                font-size: 0.85rem;
            }

            footer .row {
                text-align: center;
            }

            footer .text-md-end {
                text-align: center !important;
                margin-top: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg" style="background: white;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex flex-column align-items-center" href="{{ route('applicant.index') }}">
              
                <img src="{{ asset('storage/applicants/Logo_Metland.png') }}" alt="Metland Logo" style="height: 36px; width: auto; margin-bottom: 4px;">
                <span style="color: #009290; font-size: 1.2rem; font-weight: 600;">Recruitment</span>
            </a>
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-3 align-items-lg-center" style="color: #222;">
                    @auth
                        @php
                            $profile = \App\Models\Applicant::where('user_id', auth()->id())->first();
                            $hasProfile = (bool) $profile;
                        @endphp
                        
                        @if($hasProfile)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('applicant.index') }}">
                                    <i class="bi bi-house-fill me-1"></i>Beranda
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('applicant.edit', $profile?->RequireID) }}">
                                    <i class="bi bi-person-plus-fill me-1"></i>Data Diri
                                </a>
                            </li>
                        @else   
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('applicant.create') }}">
                                    <i class="bi bi-person-plus-fill me-1"></i>Isi Data Diri
                                </a>
                            </li>
                        @endif
                        
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-dark">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus-fill me-1"></i>Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin: 1rem; border-radius: 10px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Metland Recruitment</h5>
                    <p class="mb-0">Platform rekrutmen terpercaya untuk karir terbaik Anda.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} Metland Recruitment. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @yield('scripts')
</body>
</html>
