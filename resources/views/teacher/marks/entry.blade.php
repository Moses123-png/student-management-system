@extends('layouts.app')

@section('title', 'Marks - Teacher')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-clipboard-list"></i> Enter Student Marks</h1>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="class_id" class="form-label">Select Class *</label>
                <select class="form-select" id="class_id" name="class_id" required onchange="this.form.submit()">
                    <option value="">Choose a class</option>
                    @if(auth()->user()->teacher && auth()->user()->teacher->assignedClass)
                        <option value="{{ auth()->user()->teacher->assignedClass->id }}" selected>
                            {{ auth()->user()->teacher->assignedClass->class_name }}
                        </option>
                    @endif
                </select>
            </div>
            <div class="col-md-4">
                <label for="term" class="form-label">Term *</label>
                <select class="form-select" id="term" name="term" required onchange="this.form.submit()">
                    <option value="">Choose a term</option>
                    <option value="1" {{ request('term') == 1 ? 'selected' : '' }}>Term 1</option>
                    <option value="2" {{ request('term') == 2 ? 'selected' : '' }}>Term 2</option>
                    <option value="3" {{ request('term') == 3 ? 'selected' : '' }}>Term 3</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="year" class="form-label">Academic Year *</label>
                <input type="number" class="form-control" id="year" name="year" min="2020" max="2030" value="{{ request('year', now()->year) }}" required>
            </div>
        </form>
    </div>
</div>

@if(request()->filled('class_id') && request()->filled('term'))
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Mark Entry Form</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.marks.store-batch') }}">
                @csrf

                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                <input type="hidden" name="term" value="{{ request('term') }}">
                <input type="hidden" name="academic_year" value="{{ request('year', now()->year) }}">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2">Student</th>
                                <th colspan="4" class="text-center">Mathematics</th>
                                <th colspan="4" class="text-center">English</th>
                                <th colspan="4" class="text-center">Science</th>
                            </tr>
                            <tr>
                                @for($i = 0; $i < 3; $i++)
                                    <th class="text-center">T1</th>
                                    <th class="text-center">T2</th>
                                    <th class="text-center">Asn</th>
                                    <th class="text-center">Exam</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @if(request()->filled('class_id'))
                                @php
                                    $students = \App\Models\Student::where('class_id', request('class_id'))
                                                                   ->where('status', 'Active')
                                                                   ->get();
                                @endphp
                                @forelse($students as $student)
                                    <tr>
                                        <td><strong>{{ $student->full_name }}</strong></td>
                                        @foreach(['Mathematics', 'English', 'Science'] as $subject)
                                            <td><input type="number" name="marks[{{ $student->id }}][{{ $subject }}][test_1]" min="0" max="100" class="form-control form-control-sm"></td>
                                            <td><input type="number" name="marks[{{ $student->id }}][{{ $subject }}][test_2]" min="0" max="100" class="form-control form-control-sm"></td>
                                            <td><input type="number" name="marks[{{ $student->id }}][{{ $subject }}][assignment]" min="0" max="100" class="form-control form-control-sm"></td>
                                            <td><input type="number" name="marks[{{ $student->id }}][{{ $subject }}][exam]" min="0" max="100" class="form-control form-control-sm"></td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">No students in this class</td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Marks</button>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Please select a class and term to enter marks.
    </div>
@endif
@endsection
