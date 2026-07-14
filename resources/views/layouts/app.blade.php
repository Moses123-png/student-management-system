<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
        }
        body {
            background-color: #ecf0f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }
        .sidebar {
            background-color: var(--primary-color);
            min-height: 100vh;
            padding: 20px 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            border-left: 3px solid transparent;
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: var(--secondary-color);
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            border-radius: 8px;
        }
        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .stat-card {
            border-left: 4px solid var(--secondary-color);
        }
        .main-content {
            background-color: #ecf0f1;
            padding: 30px;
        }
        .page-header {
            margin-bottom: 30px;
        }
        .page-header h1 {
            color: var(--primary-color);
            font-weight: bold;
        }
    </style>
    @yield('styles')
</head>
<body>
    @include('layouts.navbar')
    
    <div class="d-flex">
        @auth
            @if(auth()->user()->isAdmin())
                @include('layouts.sidebar-admin')
            @elseif(auth()->user()->isTeacher())
                @include('layouts.sidebar-teacher')
            @endif
        @endauth
        
        <div class="main-content flex-grow-1">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Errors:</strong>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
