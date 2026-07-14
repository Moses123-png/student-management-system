@extends('layouts.app')

@section('title', 'Login - Student Management System')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-graduation-cap" style="font-size: 3rem; color: #3498db;"></i>
                        <h2 class="mt-3">Student Management System</h2>
                        <p class="text-muted">Login to your account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <hr class="my-4">

                    <div class="alert alert-info small">
                        <strong>Demo Credentials:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Admin:</strong> admin@sms.local / password123</li>
                            <li><strong>Teacher:</strong> teacher1@sms.local / password123</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
