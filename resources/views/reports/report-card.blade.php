@extends('layouts.app')

@section('title', 'Report Card - ' . $student->full_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-file-pdf"></i> Report Card</h1>
    <a href="{{ route('teacher.report-cards.generate', [$student, now()->year, 1, 'format' => 'pdf']) }}" class="btn btn-danger">
        <i class="fas fa-download"></i> Download PDF
    </a>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body" style="font-family: Arial, sans-serif; line-height: 1.6;">
        <!-- Header -->
        <div class="text-center mb-4">
            <h4>STUDENT REPORT CARD</h4>
            <small class="text-muted">{{ now()->format('d F Y') }}</small>
        </div>

        <!-- Student Info -->
        <div class="row mb-4">
            <div class="col-md-3 text-center">
                @if($student->photo_path)
                    <img src="{{ Storage::url($student->photo_path) }}" alt="Photo" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                @else
                    <div class="bg-light p-4" style="border-radius: 50%; display: inline-block;">
                        <i class="fas fa-user" style="font-size: 2rem; color: #bbb;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <dl class="row">
                    <dt class="col-sm-4">Student ID:</dt>
                    <dd class="col-sm-8">{{ $student->student_id }}</dd>
                    <dt class="col-sm-4">Name:</dt>
                    <dd class="col-sm-8">{{ $student->full_name }}</dd>
                    <dt class="col-sm-4">Class:</dt>
                    <dd class="col-sm-8">{{ $student->class->class_name ?? 'N/A' }}</dd>
                    <dt class="col-sm-4">Year:</dt>
                    <dd class="col-sm-8">{{ now()->year }}</dd>
                </dl>
            </div>
        </div>

        <hr>

        <!-- Marks Table -->
        <h5 class="mb-3">Academic Performance</h5>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th class="text-center">Test 1</th>
                        <th class="text-center">Test 2</th>
                        <th class="text-center">Assignment</th>
                        <th class="text-center">Exam</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalScore = 0; $subjectCount = 0; @endphp
                    @forelse($marks as $mark)
                        <tr>
                            <td>{{ $mark->subject }}</td>
                            <td class="text-center">{{ $mark->test_1_score ?? '-' }}</td>
                            <td class="text-center">{{ $mark->test_2_score ?? '-' }}</td>
                            <td class="text-center">{{ $mark->assignment_score ?? '-' }}</td>
                            <td class="text-center">{{ $mark->exam_score ?? '-' }}</td>
                            <td class="text-center"><strong>{{ $mark->total_score ? number_format($mark->total_score, 1) : '-' }}</strong></td>
                            <td class="text-center"><span class="badge bg-primary">{{ $mark->grade ?? '-' }}</span></td>
                        </tr>
                        @php 
                            $totalScore += $mark->total_score ?? 0;
                            $subjectCount++;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No marks available</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($subjectCount > 0)
                    <tfoot class="table-light">
                        <tr>
                            <th>Overall</th>
                            <th colspan="5" class="text-end">Average: <strong>{{ number_format($totalScore / $subjectCount, 1) }}</strong></th>
                            <th></th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <hr>

        <!-- Comments -->
        @if($reportCard)
            <div class="mb-4">
                <h5>Teacher's Comment</h5>
                <p>{{ $reportCard->teacher_comment ?? 'No comment' }}</p>
            </div>
        @endif

        <hr class="my-5">

        <!-- Signatures -->
        <div class="row mt-5">
            <div class="col-md-6">
                <p>___________________________</p>
                <small>Teacher Signature</small>
            </div>
            <div class="col-md-6">
                <p>___________________________</p>
                <small>Principal Signature</small>
            </div>
        </div>
    </div>
</div>
@endsection
