@extends('layouts.app')

@section('title', 'Class - ' . $class->class_name)

@section('content')
<div class="page-header">
    <h1><i class="fas fa-chalkboard"></i> {{ $class->class_name }} - {{ $class->academic_year }}</h1>
    <p class="text-muted">Teacher: {{ $class->teacher->name ?? 'Not assigned' }}</p>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Students ({{ $students->count() }})</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->student_id }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>{{ $student->date_of_birth->format('d/m/Y') }}</td>
                        <td><span class="badge bg-success">{{ $student->status }}</span></td>
                        <td>
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No students in this class</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
