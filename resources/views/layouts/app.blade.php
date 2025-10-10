<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Metland Recruitment')</title>
    <!-- Favicon / touch icon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/applicants/logo_metland.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/applicants/logo_metland.png') }}">
    
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
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
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
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--gradient-bg);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('applicant.index') }}">
                <img src="{{ asset('storage/applicants/logo_metland.png') }}" alt="Metland" style="height:32px;width:auto;margin-right:8px;border-radius:4px;box-shadow:0 4px 10px rgba(0,0,0,0.08)">
                <span>Metland Recruitment</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('applicant.index') }}">
                           
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('applicant.create') }}">
                            
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-light p-0">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
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
