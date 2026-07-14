@extends('layouts.app')

@section('title', 'Students - Admin')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-users"></i> Students</h1>
    </div>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Student
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.students.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search by ID or name" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Graduated" {{ request('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="Dropped Out" {{ request('status') == 'Dropped Out' ? 'selected' : '' }}>Dropped Out</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Class</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td><strong>{{ $student->student_id }}</strong></td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>{{ $student->class->class_name ?? 'N/A' }}</td>
                        <td>{{ $student->date_of_birth->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $student->status == 'Active' ? 'success' : ($student->status == 'Graduated' ? 'info' : 'danger') }}">
                                {{ $student->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light">
        {{ $students->links() }}
    </div>
</div>
@endsection
