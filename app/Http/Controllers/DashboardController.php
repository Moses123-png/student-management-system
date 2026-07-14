<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $stats = [
            'total_students' => \App\Models\Student::where('status', 'Active')->count(),
            'total_teachers' => User::where('role', 'teacher')->where('is_active', true)->count(),
            'total_classes' => \App\Models\ClassModel::where('is_active', true)->count(),
            'total_graduates' => \App\Models\Graduate::whereYear('graduation_year', now()->year)->count(),
        ];

        $recentStudents = \App\Models\Student::latest()->take(10)->get();
        $scholarships = \App\Models\Scholarship::where('status', 'Active')->count();
        $graduates = \App\Models\Graduate::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'scholarships', 'graduates'));
    }

    /**
     * Show teacher dashboard
     */
    public function teacherDashboard()
    {
        $teacher = auth()->user()->teacher;
        $assignedClass = $teacher->assignedClass;
        $students = [];
        $stats = [];

        if ($assignedClass) {
            $students = \App\Models\Student::where('class_id', $assignedClass->id)
                                           ->where('status', 'Active')
                                           ->get();
            $stats = [
                'total_students' => $students->count(),
                'class_name' => $assignedClass->class_name,
                'academic_year' => $assignedClass->academic_year,
            ];
        }

        return view('teacher.dashboard', compact('stats', 'students', 'assignedClass'));
    }

    /**
     * Show guardian dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        // Guardian can view their children's records
        return view('dashboard');
    }
}
