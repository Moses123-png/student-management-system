<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\ReportCard;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use PDF;

class MarkController extends Controller
{
    public function index(Request $request)
    {
        $query = Mark::query();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        $marks = $query->with(['student', 'teacher'])
            ->paginate(20);

        return view('admin.marks.index', [
            'marks' => $marks,
            'subjects' => Mark::getSubjects(),
            'years' => range(date('Y') - 5, date('Y')),
            'terms' => [1, 2, 3],
        ]);
    }

    public function create()
    {
        return view('admin.marks.create', [
            'students' => Student::where('status', 'Active')->get(),
            'subjects' => Mark::getSubjects(),
            'years' => range(date('Y') - 5, date('Y')),
            'terms' => [1, 2, 3],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|in:' . implode(',', Mark::getSubjects()),
            'academic_year' => 'required|integer',
            'term' => 'required|in:1,2,3',
            'test_1_score' => 'nullable|numeric|min:0|max:100',
            'test_2_score' => 'nullable|numeric|min:0|max:100',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'exam_score' => 'nullable|numeric|min:0|max:100',
        ]);

        // Check for duplicate
        $existing = Mark::where('student_id', $validated['student_id'])
            ->where('subject', $validated['subject'])
            ->where('academic_year', $validated['academic_year'])
            ->where('term', $validated['term'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Mark already exists for this student, subject, and term');
        }

        $mark = new Mark($validated);
        $mark->total_score = $mark->calculateTotal();
        $mark->grade = $mark->calculateGrade();
        $mark->teacher_id = auth()->user()->teacher->id ?? null;
        $mark->save();

        AuditLog::log(auth()->user(), 'CREATE', 'Mark', $mark->id, [], $validated);

        return redirect()->route('admin.marks.show', $mark)
            ->with('success', 'Mark recorded successfully');
    }

    public function show(Mark $mark)
    {
        return view('admin.marks.show', [
            'mark' => $mark->load(['student', 'teacher']),
        ]);
    }

    public function edit(Mark $mark)
    {
        return view('admin.marks.edit', [
            'mark' => $mark,
            'subjects' => Mark::getSubjects(),
        ]);
    }

    public function update(Request $request, Mark $mark)
    {
        $validated = $request->validate([
            'test_1_score' => 'nullable|numeric|min:0|max:100',
            'test_2_score' => 'nullable|numeric|min:0|max:100',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'exam_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $oldValues = $mark->toArray();

        $mark->fill($validated);
        $mark->total_score = $mark->calculateTotal();
        $mark->grade = $mark->calculateGrade();
        $mark->save();

        AuditLog::log(auth()->user(), 'UPDATE', 'Mark', $mark->id, $oldValues, $validated);

        return redirect()->route('admin.marks.show', $mark)
            ->with('success', 'Mark updated successfully');
    }

    public function destroy(Mark $mark)
    {
        $mark->delete();
        AuditLog::log(auth()->user(), 'DELETE', 'Mark', $mark->id);

        return redirect()->route('admin.marks.index')
            ->with('success', 'Mark deleted successfully');
    }

    public function bulkEntry(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject' => 'required|in:' . implode(',', Mark::getSubjects()),
            'academic_year' => 'required|integer',
            'term' => 'required|in:1,2,3',
            'marks' => 'required|array',
            'marks.*.student_id' => 'required|exists:students,id',
            'marks.*.score' => 'required|numeric|min:0|max:100',
        ]);

        $created = 0;
        foreach ($validated['marks'] as $markData) {
            Mark::updateOrCreate(
                [
                    'student_id' => $markData['student_id'],
                    'subject' => $validated['subject'],
                    'academic_year' => $validated['academic_year'],
                    'term' => $validated['term'],
                ],
                [
                    'exam_score' => $markData['score'],
                    'total_score' => $markData['score'],
                    'grade' => $this->calculateGrade($markData['score']),
                    'teacher_id' => auth()->user()->teacher->id ?? null,
                ]
            );
            $created++;
        }

        return back()->with('success', "{$created} marks recorded successfully");
    }

    public function report(Request $request, $year, $term)
    {
        $marks = Mark::where('academic_year', $year)
            ->where('term', $term)
            ->with('student', 'teacher')
            ->orderBy('subject')
            ->get();

        return view('admin.marks.report', [
            'marks' => $marks,
            'year' => $year,
            'term' => $term,
        ]);
    }

    public function reportCards(Request $request)
    {
        $query = ReportCard::query();

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        $reportCards = $query->with('student', 'class')
            ->paginate(15);

        return view('admin.marks.report-cards', [
            'reportCards' => $reportCards,
            'years' => range(date('Y') - 5, date('Y')),
            'terms' => [1, 2, 3],
        ]);
    }

    public function generateReportCard($studentId, $year, $term)
    {
        $student = Student::findOrFail($studentId);
        $marks = $student->marks()
            ->where('academic_year', $year)
            ->where('term', $term)
            ->get();

        if ($marks->isEmpty()) {
            return back()->with('error', 'No marks found for this student, year, and term');
        }

        $reportCard = ReportCard::firstOrCreate(
            [
                'student_id' => $studentId,
                'academic_year' => $year,
                'term' => $term,
            ],
            [
                'class_id' => $student->class_id,
                'generated_at' => now(),
            ]
        );

        return view('admin.marks.generate-report-card', [
            'student' => $student,
            'reportCard' => $reportCard,
            'marks' => $marks,
            'year' => $year,
            'term' => $term,
        ]);
    }

    public function downloadReportCard(ReportCard $reportCard)
    {
        $marks = $reportCard->getStudentMarks();

        $data = [
            'student' => $reportCard->student,
            'marks' => $marks,
            'reportCard' => $reportCard,
            'totalPoints' => $reportCard->getTotalPoints(),
            'overallGrade' => $reportCard->getOverallGrade(),
            'ranking' => $reportCard->getClassRanking(),
        ];

        $pdf = PDF::loadView('admin.marks.report-card-pdf', $data);
        return $pdf->download("report_card_{$reportCard->student->student_id}_{$reportCard->academic_year}_term_{$reportCard->term}.pdf");
    }

    private function calculateGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        if ($score >= 50) return 'E';
        return 'F';
    }
}
