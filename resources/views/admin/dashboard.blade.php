@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chart-line"></i> Admin Dashboard</h1>
    <p class="text-muted">Welcome back, {{ auth()->user()->name }}!</p>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title text-muted">Total Students</h5>
                <h2 class="card-text text-primary">{{ $stats['total_students'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title text-muted">Teachers</h5>
                <h2 class="card-text text-success">{{ $stats['total_teachers'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title text-muted">Classes</h5>
                <h2 class="card-text text-info">{{ $stats['total_classes'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <h5 class="card-title text-muted">Scholarships</h5>
                <h2 class="card-text text-warning">{{ $scholarships }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Students</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                            <tr>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->full_name }}</td>
                                <td>{{ $student->class->class_name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No students yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Graduates</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Achievement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($graduates as $graduate)
                            <tr>
                                <td>{{ $graduate->student->full_name }}</td>
                                <td>{{ $graduate->graduation_year }}</td>
                                <td><span class="badge bg-success">{{ $graduate->achievement_level }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No graduates yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
