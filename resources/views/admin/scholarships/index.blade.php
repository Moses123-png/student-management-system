@extends('layouts.app')

@section('title', 'Scholarships')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-award"></i> Scholarships</h1>
    <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Scholarship
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="sponsor" class="form-control" placeholder="Search sponsor" value="{{ request('sponsor') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-4">
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
                    <th>Student</th>
                    <th>Type</th>
                    <th>Sponsor</th>
                    <th>Amount</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scholarships as $scholarship)
                    <tr>
                        <td><strong>{{ $scholarship->student->full_name }}</strong></td>
                        <td>{{ $scholarship->scholarship_type ?? 'N/A' }}</td>
                        <td>{{ $scholarship->sponsor_name ?? 'N/A' }}</td>
                        <td>{{ $scholarship->currency ?? 'UGX' }} {{ $scholarship->amount ? number_format($scholarship->amount) : 'N/A' }}</td>
                        <td>{{ $scholarship->start_year ?? 'N/A' }} - {{ $scholarship->end_year ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $scholarship->status == 'Active' ? 'success' : 'secondary' }}">
                                {{ $scholarship->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.scholarships.edit', $scholarship) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.scholarships.destroy', $scholarship) }}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
                        <td colspan="7" class="text-center text-muted py-4">No scholarships found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light">
        {{ $scholarships->links() }}
    </div>
</div>
@endsection
