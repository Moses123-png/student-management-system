@extends('layouts.app')

@section('title', 'Graduates')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-graduation-cap"></i> Graduates</h1>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Graduation Year</th>
                    <th>Achievement Level</th>
                    <th>Scholarship</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($graduates as $graduate)
                    <tr>
                        <td>{{ $graduate->student->student_id }}</td>
                        <td>{{ $graduate->student->full_name }}</td>
                        <td>{{ $graduate->graduation_year }}</td>
                        <td>
                            <span class="badge bg-{{ $graduate->achievement_level == 'Excellent' ? 'success' : 'info' }}">
                                {{ $graduate->achievement_level ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <i class="fas fa-{{ $graduate->scholarship_received ? 'check text-success' : 'times text-danger' }}"></i>
                        </td>
                        <td>
                            <a href="{{ route('admin.students.show', $graduate->student) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No graduates found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light">
        {{ $graduates->links() }}
    </div>
</div>
@endsection
