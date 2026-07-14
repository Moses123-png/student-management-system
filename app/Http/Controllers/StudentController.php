<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Guardian;
use App\Models\CommunityWorker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by zone
        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }

        // Search by name or student ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%$search%")
                  ->orWhere('surname', 'like', "%$search%")
                  ->orWhere('other_names', 'like', "%$search%");
            });
        }

        $students = $query->with(['class', 'guardian', 'communityWorker'])
                          ->paginate(15);

        $classes = ClassModel::where('is_active', true)->get();
        $zones = Student::distinct()->pluck('zone')->filter();

        return view('admin.students.index', compact('students', 'classes', 'zones'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $classes = ClassModel::where('is_active', true)->get();
        $guardians = Guardian::all();
        $communityWorkers = CommunityWorker::where('is_active', true)->get();

        return view('admin.students.create', compact('classes', 'guardians', 'communityWorkers'));
    }

    /**
     * Store a newly created student in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:100',
            'other_names' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'entry_year' => 'required|integer|min:2020|max:2030',
            'class_id' => 'required|exists:classes,id',
            'guardian_id' => 'nullable|exists:guardians,id',
            'community_worker_id' => 'nullable|exists:community_workers,id',
            'zone' => 'nullable|string|max:100',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo_path'] = $path;
        }

        // Generate Student ID
        $lastStudent = Student::orderBy('id', 'desc')->first();
        $nextId = ($lastStudent ? intval(substr($lastStudent->student_id, 3)) : 0) + 1;
        $validated['student_id'] = 'STD' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Set default status
        $validated['status'] = 'Active';

        $student = Student::create($validated);

        return redirect()->route('students.show', $student)
                        ->with('success', 'Student registered successfully!');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['class', 'guardian', 'communityWorker', 'scholarship', 'marks', 'attendance']);
        $termMarks = $student->getTermMarks(1, now()->year);

        return view('admin.students.show', compact('student', 'termMarks'));
    }

    /**
     * Show the form for editing the student
     */
    public function edit(Student $student)
    {
        $classes = ClassModel::where('is_active', true)->get();
        $guardians = Guardian::all();
        $communityWorkers = CommunityWorker::where('is_active', true)->get();

        return view('admin.students.edit', compact('student', 'classes', 'guardians', 'communityWorkers'));
    }

    /**
     * Update the specified student in database
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:100',
            'other_names' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'entry_year' => 'required|integer|min:2020|max:2030',
            'class_id' => 'required|exists:classes,id',
            'status' => 'required|in:Active,Graduated,Dropped Out',
            'guardian_id' => 'nullable|exists:guardians,id',
            'community_worker_id' => 'nullable|exists:community_workers,id',
            'zone' => 'nullable|string|max:100',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo_path && Storage::disk('public')->exists($student->photo_path)) {
                Storage::disk('public')->delete($student->photo_path);
            }

            $path = $request->file('photo')->store('students', 'public');
            $validated['photo_path'] = $path;
        }

        $student->update($validated);

        return redirect()->route('students.show', $student)
                        ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student from database
     */
    public function destroy(Student $student)
    {
        // Soft delete
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'Student deleted successfully!');
    }

    /**
     * Get students by class
     */
    public function getByClass($classId)
    {
        $students = Student::where('class_id', $classId)
                           ->where('status', 'Active')
                           ->get(['id', 'student_id', 'surname', 'other_names']);

        return response()->json($students);
    }

    /**
     * Get student age
     */
    public function getAge(Student $student)
    {
        return response()->json([
            'age' => $student->age,
            'dob' => $student->date_of_birth->format('Y-m-d')
        ]);
    }
}
