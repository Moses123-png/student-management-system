@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chalkboard"></i> Teacher Dashboard</h1>
    <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
</div>

@if($assignedClass)
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Class</h5>
                    <h2 class="card-text text-primary">{{ $stats['class_name'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Total Students</h5>
                    <h2 class="card-text text-success">{{ $stats['total_students'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <h5 class="card-title text-muted">Academic Year</h5>
                    <h2 class="card-text text-info">{{ $stats['academic_year'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Class Students</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->full_name }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->date_of_birth->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No students in your class</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> No class has been assigned to you yet. Please contact the administrator.
    </div>
@endif
@endsection
