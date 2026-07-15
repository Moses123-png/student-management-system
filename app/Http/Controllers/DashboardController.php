<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Mark;
use App\Models\Scholarship;
use App\Models\ClassPromotion;
use App\Models\Graduate;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isTeacher()) {
            return $this->teacherDashboard();
        } elseif ($user->isGuardian()) {
            return $this->guardianDashboard();
        }

        return redirect('/');
    }

    public function adminDashboard()
    {
        $totalStudents = Student::where('status', 'Active')->count();
        $totalGraduates = Graduate::count();
        $activeScholarships = Scholarship::where('status', 'Active')->count();
        $recentAuditLogs = AuditLog::recent()->limit(10)->get();

        $studentsByStatus = [
            'Active' => Student::where('status', 'Active')->count(),
            'Graduated' => Student::where('status', 'Graduated')->count(),
            'Dropped Out' => Student::where('status', 'Dropped Out')->count(),
        ];

        $marksDistribution = Mark::selectRaw('grade, count(*) as count')
            ->groupBy('grade')
            ->get();

        $scholarshipsByType = Scholarship::selectRaw('scholarship_type, count(*) as count')
            ->where('status', 'Active')
            ->groupBy('scholarship_type')
            ->get();

        return view('admin.dashboard', [
            'totalStudents' => $totalStudents,
            'totalGraduates' => $totalGraduates,
            'activeScholarships' => $activeScholarships,
            'studentsByStatus' => $studentsByStatus,
            'marksDistribution' => $marksDistribution,
            'scholarshipsByType' => $scholarshipsByType,
            'recentAuditLogs' => $recentAuditLogs,
        ]);
    }

    public function teacherDashboard()
    {
        $teacher = auth()->user()->teacher;
        $class = $teacher->assignedClass;
        $students = $class->students()->count();
        $averagePerformance = $class->getAveragePerformance();

        $recentMarks = Mark::where('teacher_id', $teacher->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('teacher.dashboard', [
            'teacher' => $teacher,
            'class' => $class,
            'studentCount' => $students,
            'averagePerformance' => $averagePerformance,
            'recentMarks' => $recentMarks,
        ]);
    }

    public function guardianDashboard()
    {
        // Guardian portal functionality
        return view('guardian.dashboard');
    }
}
