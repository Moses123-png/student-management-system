@extends('layouts.app')

@section('title', 'Student Details - ' . $student->full_name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-user"></i> {{ $student->full_name }}</h1>
    <div>
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                @if($student->photo_path)
                    <img src="{{ Storage::url($student->photo_path) }}" alt="Student Photo" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                @else
                    <div class="bg-light p-5 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-user-alt" style="font-size: 3rem; color: #bbb;"></i>
                    </div>
                @endif
                <h5>{{ $student->student_id }}</h5>
                <p class="text-muted mb-0">Student ID</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Quick Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> <span class="badge bg-success">{{ $student->status }}</span></p>
                <p><strong>Age:</strong> {{ $student->age }} years</p>
                <p><strong>Gender:</strong> {{ $student->gender }}</p>
                <p><strong>Class:</strong> {{ $student->class->class_name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#personal">Personal Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#guardian">Guardian</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#marks">Marks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#scholarship">Scholarship</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#attendance">Attendance</a>
            </li>
        </ul>

        <div class="tab-content">
            <div id="personal" class="tab-pane fade show active">
                <div class="card">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Full Name:</dt>
                            <dd class="col-sm-9">{{ $student->full_name }}</dd>

                            <dt class="col-sm-3">Date of Birth:</dt>
                            <dd class="col-sm-9">{{ $student->date_of_birth->format('d F Y') }}</dd>

                            <dt class="col-sm-3">Gender:</dt>
                            <dd class="col-sm-9">{{ $student->gender }}</dd>

                            <dt class="col-sm-3">Entry Year:</dt>
                            <dd class="col-sm-9">{{ $student->entry_year }}</dd>

                            <dt class="col-sm-3">Current Class:</dt>
                            <dd class="col-sm-9">{{ $student->class->class_name ?? 'Not assigned' }}</dd>

                            <dt class="col-sm-3">Zone:</dt>
                            <dd class="col-sm-9">{{ $student->zone ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div id="guardian" class="tab-pane fade">
                <div class="card">
                    <div class="card-body">
                        @if($student->guardian)
                            <dl class="row">
                                <dt class="col-sm-3">Name:</dt>
                                <dd class="col-sm-9">{{ $student->guardian->name }}</dd>

                                <dt class="col-sm-3">Relationship:</dt>
                                <dd class="col-sm-9">{{ $student->guardian->relationship }}</dd>

                                <dt class="col-sm-3">Phone:</dt>
                                <dd class="col-sm-9">{{ $student->guardian->phone }}</dd>

                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9">{{ $student->guardian->email ?? 'N/A' }}</dd>

                                <dt class="col-sm-3">Occupation:</dt>
                                <dd class="col-sm-9">{{ $student->guardian->occupation ?? 'N/A' }}</dd>

                                <dt class="col-sm-3">Address:</dt>
                                <dd class="col-sm-9">{{ $student->guardian->address ?? 'N/A' }}</dd>
                            </dl>
                        @else
                            <p class="text-muted">No guardian information available</p>
                        @endif
                    </div>
                </div>
            </div>

            <div id="marks" class="tab-pane fade">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Test 1</th>
                                    <th>Test 2</th>
                                    <th>Assignment</th>
                                    <th>Exam</th>
                                    <th>Total</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($termMarks as $mark)
                                    <tr>
                                        <td>{{ $mark->subject }}</td>
                                        <td>{{ $mark->test_1_score ?? '-' }}</td>
                                        <td>{{ $mark->test_2_score ?? '-' }}</td>
                                        <td>{{ $mark->assignment_score ?? '-' }}</td>
                                        <td>{{ $mark->exam_score ?? '-' }}</td>
                                        <td><strong>{{ $mark->total_score ?? '-' }}</strong></td>
                                        <td><span class="badge bg-primary">{{ $mark->grade ?? '-' }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No marks available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="scholarship" class="tab-pane fade">
                <div class="card">
                    <div class="card-body">
                        @if($student->scholarship)
                            @if($student->scholarship->has_scholarship)
                                <dl class="row">
                                    <dt class="col-sm-3">Has Scholarship:</dt>
                                    <dd class="col-sm-9"><span class="badge bg-success">Yes</span></dd>

                                    <dt class="col-sm-3">Type:</dt>
                                    <dd class="col-sm-9">{{ $student->scholarship->scholarship_type }}</dd>

                                    <dt class="col-sm-3">Sponsor:</dt>
                                    <dd class="col-sm-9">{{ $student->scholarship->sponsor_name }}</dd>

                                    <dt class="col-sm-3">Amount:</dt>
                                    <dd class="col-sm-9">{{ $student->scholarship->currency }} {{ number_format($student->scholarship->amount) }}</dd>

                                    <dt class="col-sm-3">Period:</dt>
                                    <dd class="col-sm-9">{{ $student->scholarship->start_year }} - {{ $student->scholarship->end_year }}</dd>

                                    <dt class="col-sm-3">Status:</dt>
                                    <dd class="col-sm-9"><span class="badge bg-info">{{ $student->scholarship->status }}</span></dd>
                                </dl>
                            @else
                                <p class="text-muted">No active scholarship</p>
                            @endif
                        @else
                            <p class="text-muted">No scholarship information</p>
                        @endif
                    </div>
                </div>
            </div>

            <div id="attendance" class="tab-pane fade">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->attendance->take(20) as $record)
                                    <tr>
                                        <td>{{ $record->attendance_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $record->status == 'Present' ? 'success' : ($record->status == 'Absent' ? 'danger' : 'warning') }}">
                                                {{ $record->status }}
                                            </span>
                                        </td>
                                        <td>{{ $record->notes ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No attendance records</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
