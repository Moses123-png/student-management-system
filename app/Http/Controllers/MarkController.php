<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Attendance;
use App\Models\ReportCard;
use Illuminate\Http\Request;
use PDF;

class MarkController extends Controller
{
    /**
     * Display marks for a student
     */
    public function show(Request $request)
    {
        $query = Mark::query();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('year')) {
            $query->where('academic_year', $request->year);
        }

        if ($request->filled('term')) {
            $query->where('term', $request->term);
        }

        $marks = $query->with('student', 'teacher')->paginate(20);

        return view('admin.marks.index', compact('marks'));
    }

    /**
     * Show mark entry form
     */
    public function entryForm()
    {
        $classes = ClassModel::where('is_active', true)->get();
        $students = [];

        return view('teacher.marks.entry', compact('classes', 'students'));
    }

    /**
     * Store marks in bulk for a class and term
     */
    public function storeBatch(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'term' => 'required|in:1,2,3',
            'academic_year' => 'required|integer',
            'marks' => 'required|array',
        ]);

        $marks = $request->marks;
        $successCount = 0;

        foreach ($marks as $studentId => $subjects) {
            foreach ($subjects as $subject => $scores) {
                $mark = Mark::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject' => $subject,
                        'academic_year' => $validated['academic_year'],
                        'term' => $validated['term'],
                    ],
                    [
                        'test_1_score' => $scores['test_1'] ?? null,
                        'test_2_score' => $scores['test_2'] ?? null,
                        'assignment_score' => $scores['assignment'] ?? null,
                        'exam_score' => $scores['exam'] ?? null,
                        'teacher_id' => auth()->id(),
                    ]
                );
                $successCount++;
            }
        }

        return redirect()->back()->with('success', "$successCount marks saved successfully!");
    }

    /**
     * Show marks report
     */
    public function report(Request $request, $year = null, $term = null)
    {
        $year = $year ?? now()->year;
        $term = $term ?? 1;

        $marks = Mark::where('academic_year', $year)
                      ->where('term', $term)
                      ->with('student', 'teacher')
                      ->get();

        return view('admin.marks.report', compact('marks', 'year', 'term'));
    }

    /**
     * Show student marks
     */
    public function showStudentMarks(Student $student)
    {
        $marks = $student->marks()->with('teacher')->get();
        $termMarks = $student->getTermMarks(1, now()->year);

        return view('teacher.marks.show', compact('student', 'marks', 'termMarks'));
    }

    /**
     * Generate report card
     */
    public function generateReportCard(Request $request, Student $student, $year = null, $term = null)
    {
        $year = $year ?? now()->year;
        $term = $term ?? 1;

        $marks = Mark::where('student_id', $student->id)
                      ->where('academic_year', $year)
                      ->where('term', $term)
                      ->get();

        $reportCard = ReportCard::where('student_id', $student->id)
                                ->where('academic_year', $year)
                                ->where('term', $term)
                                ->first();

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('reports.report-card', compact('student', 'marks', 'year', 'term', 'reportCard'));
            return $pdf->download('report-card-' . $student->student_id . '.pdf');
        }

        return view('reports.report-card', compact('student', 'marks', 'year', 'term', 'reportCard'));
    }

    /**
     * List all report cards
     */
    public function reportCards()
    {
        $reportCards = ReportCard::with('student', 'class')->paginate(20);
        return view('admin.report-cards.index', compact('reportCards'));
    }

    /**
     * Download report card PDF
     */
    public function downloadReportCard($id)
    {
        $reportCard = ReportCard::findOrFail($id);
        $student = $reportCard->student;
        $marks = $reportCard->getMarks();

        $pdf = PDF::loadView('reports.report-card', compact('student', 'marks', 'reportCard'));
        return $pdf->download('report-card-' . $student->student_id . '.pdf');
    }

    /**
     * Record attendance
     */
    public function recordAttendance(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'class_id' => $validated['class_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                ['status' => $status, 'recorded_by' => auth()->id()]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully!');
    }
}
