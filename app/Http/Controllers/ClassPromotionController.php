<?php

namespace App\Http\Controllers;

use App\Models\StudentClass;
use App\Models\Student;
use App\Models\ClassPromotion;
use App\Models\Graduate;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ClassPromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentClass::query();

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $classes = $query->with('teacher', 'students')
            ->paginate(15);

        return view('admin.classes.index', [
            'classes' => $classes,
            'years' => range(date('Y') - 5, date('Y')),
        ]);
    }

    public function create()
    {
        return view('admin.classes.create', [
            'classNames' => ['P.1', 'P.2', 'P.3', 'P.4', 'P.5', 'P.6', 'P.7'],
            'years' => range(date('Y'), date('Y') + 1),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_name' => 'required|in:P.1,P.2,P.3,P.4,P.5,P.6,P.7',
            'academic_year' => 'required|integer|min:2020',
            'teacher_id' => 'nullable|exists:teachers,id',
            'is_active' => 'boolean',
        ]);

        $class = StudentClass::create($validated);
        AuditLog::log(auth()->user(), 'CREATE', 'StudentClass', $class->id, [], $validated);

        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Class created successfully');
    }

    public function show(StudentClass $class)
    {
        $students = $class->students()->paginate(20);
        
        return view('admin.classes.show', [
            'class' => $class->load('teacher'),
            'students' => $students,
            'studentCount' => $class->getStudentCount(),
            'averagePerformance' => $class->getAveragePerformance(),
        ]);
    }

    public function edit(StudentClass $class)
    {
        return view('admin.classes.edit', [
            'class' => $class,
            'classNames' => ['P.1', 'P.2', 'P.3', 'P.4', 'P.5', 'P.6', 'P.7'],
        ]);
    }

    public function update(Request $request, StudentClass $class)
    {
        $validated = $request->validate([
            'class_name' => 'required|in:P.1,P.2,P.3,P.4,P.5,P.6,P.7',
            'teacher_id' => 'nullable|exists:teachers,id',
            'is_active' => 'boolean',
        ]);

        $oldValues = $class->toArray();
        $class->update($validated);
        AuditLog::log(auth()->user(), 'UPDATE', 'StudentClass', $class->id, $oldValues, $validated);

        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Class updated successfully');
    }

    public function promoteStudents(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|integer',
            'promotion_list' => 'required|array',
            'promotion_list.*.student_id' => 'required|exists:students,id',
            'promotion_list.*.to_class_id' => 'required|exists:classes,id',
            'promotion_list.*.status' => 'required|in:Promoted,Held Back,Graduated',
        ]);

        $promoted = 0;
        $graduated = 0;

        foreach ($validated['promotion_list'] as $promotion) {
            $student = Student::find($promotion['student_id']);
            $fromClassId = $student->class_id;

            ClassPromotion::create([
                'student_id' => $promotion['student_id'],
                'from_class_id' => $fromClassId,
                'to_class_id' => $promotion['to_class_id'],
                'academic_year' => $validated['academic_year'],
                'promotion_date' => now(),
                'promoted_by' => auth()->id(),
                'status' => $promotion['status'],
            ]);

            if ($promotion['status'] === 'Graduated') {
                $student->update(['status' => 'Graduated']);
                Graduate::firstOrCreate(
                    ['student_id' => $promotion['student_id']],
                    [
                        'graduation_year' => $validated['academic_year'],
                        'graduation_date' => now(),
                        'final_class' => 'P.7',
                    ]
                );
                $graduated++;
            } else {
                $student->update(['class_id' => $promotion['to_class_id']]);
                $promoted++;
            }

            AuditLog::log(auth()->user(), 'PROMOTE', 'Student', $promotion['student_id'], [], $promotion);
        }

        return back()->with('success', "{$promoted} students promoted, {$graduated} graduated successfully");
    }

    public function showClass()
    {
        $teacher = auth()->user()->teacher;
        $class = $teacher->assignedClass;
        $students = $class->students()->paginate(20);

        return view('teacher.class.show', [
            'class' => $class,
            'students' => $students,
            'studentCount' => $class->getStudentCount(),
        ]);
    }

    public function graduates(Request $request)
    {
        $query = Graduate::query();

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('achievement_level')) {
            $query->where('achievement_level', $request->achievement_level);
        }

        $graduates = $query->with('student')
            ->paginate(20);

        return view('admin.graduates.index', [
            'graduates' => $graduates,
            'years' => range(date('Y') - 5, date('Y')),
            'achievements' => Graduate::getAchievementLevels(),
        ]);
    }

    public function graduatesByYear($year)
    {
        $graduates = Graduate::where('graduation_year', $year)
            ->with('student')
            ->get();

        return view('admin.graduates.by-year', [
            'graduates' => $graduates,
            'year' => $year,
        ]);
    }
}
