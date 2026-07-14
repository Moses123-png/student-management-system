<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\ClassPromotion;
use App\Models\Graduate;
use Illuminate\Http\Request;

class ClassPromotionController extends Controller
{
    /**
     * Display all classes
     */
    public function index()
    {
        $classes = ClassModel::where('is_active', true)
                            ->with('teacher')
                            ->get();

        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show class details
     */
    public function show(ClassModel $class)
    {
        $students = $class->getActiveStudents();
        return view('admin.classes.show', compact('class', 'students'));
    }

    /**
     * Show promotion form
     */
    public function create()
    {
        $classes = ClassModel::where('is_active', true)->get();
        return view('admin.classes.promote', compact('classes'));
    }

    /**
     * Promote students to next class
     */
    public function promoteStudents(Request $request)
    {
        $validated = $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'to_class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|integer',
            'student_ids' => 'required|array',
        ]);

        $fromClass = ClassModel::find($validated['from_class_id']);
        $toClass = ClassModel::find($validated['to_class_id']);
        $promotedCount = 0;

        foreach ($validated['student_ids'] as $studentId => $promote) {
            if (!$promote) continue;

            $student = Student::find($studentId);
            if (!$student) continue;

            // Record promotion
            ClassPromotion::create([
                'student_id' => $studentId,
                'from_class_id' => $validated['from_class_id'],
                'to_class_id' => $validated['to_class_id'],
                'academic_year' => $validated['academic_year'],
                'promotion_date' => now(),
                'promoted_by' => auth()->id(),
                'status' => $toClass->class_name === 'P.7' ? 'Graduated' : 'Promoted',
            ]);

            // Update student class
            $student->update(['class_id' => $validated['to_class_id']]);

            // If promoted to P.7, mark as graduated
            if ($toClass->class_name === 'P.7') {
                Graduate::create([
                    'student_id' => $studentId,
                    'graduation_year' => $validated['academic_year'],
                    'graduation_date' => now(),
                    'final_class' => 'P.7',
                ]);
                $student->update(['status' => 'Graduated']);
            }

            $promotedCount++;
        }

        return redirect()->route('admin.classes.index')
                        ->with('success', "$promotedCount students promoted successfully!");
    }

    /**
     * Show teacher's assigned class
     */
    public function showClass()
    {
        $teacher = auth()->user()->teacher;
        $class = $teacher->assignedClass;

        if (!$class) {
            return back()->with('error', 'No class assigned to you.');
        }

        $students = $class->getActiveStudents();
        return view('teacher.class.show', compact('class', 'students'));
    }

    /**
     * Show class report
     */
    public function classReport(ClassModel $class)
    {
        $students = $class->students()->get();
        return view('admin.classes.report', compact('class', 'students'));
    }

    /**
     * Show graduates list
     */
    public function graduates()
    {
        $graduates = Graduate::with('student')
                            ->orderBy('graduation_year', 'desc')
                            ->paginate(20);

        return view('admin.graduates.index', compact('graduates'));
    }

    /**
     * Show graduates by year
     */
    public function graduatesByYear($year)
    {
        $graduates = Graduate::where('graduation_year', $year)
                            ->with('student')
                            ->paginate(20);

        return view('admin.graduates.by-year', compact('graduates', 'year'));
    }
}
