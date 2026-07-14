@extends('layouts.app')

@section('title', 'Welcome - Student Management System')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div style="padding: 60px 20px;">
                <i class="fas fa-graduation-cap" style="font-size: 5rem; color: #3498db; margin-bottom: 20px;"></i>
                <h1 class="mb-3">Student Management System</h1>
                <p class="lead text-muted mb-4">A comprehensive system for managing student records, grades, and scholarships</p>
                
                @if(auth()->check())
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('teacher.dashboard') }}" class="btn btn-lg btn-primary">
                        <i class="fas fa-arrow-right"></i> Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-lg btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
