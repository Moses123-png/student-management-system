<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\StudentClass;
use App\Models\CommunityWorker;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('surname', 'like', "%{$search}%")
                    ->orWhere('other_names', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $query->with(['class', 'guardian', 'communityWorker'])
            ->paginate(15);

        return view('admin.students.index', [
            'students' => $students,
            'classes' => StudentClass::all(),
            'statuses' => ['Active', 'Graduated', 'Dropped Out'],
        ]);
    }

    public function create()
    {
        return view('admin.students.create', [
            'classes' => StudentClass::where('is_active', true)->get(),
            'guardians' => Guardian::all(),
            'communityWorkers' => CommunityWorker::where('is_active', true)->get(),
            'zones' => CommunityWorker::getZones(),
            'genders' => ['Male', 'Female', 'Other'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:100',
            'other_names' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'photo' => 'nullable|image|max:2048',
            'entry_year' => 'required|integer|min:2020|max:' . date('Y'),
            'class_id' => 'required|exists:classes,id',
            'guardian_id' => 'nullable|exists:guardians,id',
            'community_worker_id' => 'nullable|exists:community_workers,id',
            'zone' => 'nullable|string|max:100',
        ]);

        // Generate student ID
        $lastStudent = Student::latest('id')->first();
        $nextId = ($lastStudent ? intval(substr($lastStudent->student_id, 3)) : 0) + 1;
        $validated['student_id'] = 'STD' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo_path'] = $path;
        }

        $validated['status'] = 'Active';
        $student = Student::create($validated);

        // Log action
        AuditLog::log(auth()->user(), 'CREATE', 'Student', $student->id, [], $validated);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student created successfully');
    }

    public function show(Student $student)
    {
        $student->load(['class', 'guardian', 'communityWorker', 'marks', 'scholarships', 'attendance']);
        
        return view('admin.students.show', [
            'student' => $student,
            'averageMarks' => $student->getAverageMarks(),
            'hasScholarship' => $student->hasScholarship(),
        ]);
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', [
            'student' => $student,
            'classes' => StudentClass::where('is_active', true)->get(),
            'guardians' => Guardian::all(),
            'communityWorkers' => CommunityWorker::where('is_active', true)->get(),
            'zones' => CommunityWorker::getZones(),
            'genders' => ['Male', 'Female', 'Other'],
            'statuses' => ['Active', 'Graduated', 'Dropped Out'],
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:100',
            'other_names' => 'required|string|max:100',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'photo' => 'nullable|image|max:2048',
            'class_id' => 'required|exists:classes,id',
            'guardian_id' => 'nullable|exists:guardians,id',
            'community_worker_id' => 'nullable|exists:community_workers,id',
            'zone' => 'nullable|string|max:100',
            'status' => 'required|in:Active,Graduated,Dropped Out',
        ]);

        $oldValues = $student->toArray();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo_path'] = $path;
        }

        $student->update($validated);

        // Log action
        AuditLog::log(auth()->user(), 'UPDATE', 'Student', $student->id, $oldValues, $validated);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        if ($student->photo_path) {
            Storage::disk('public')->delete($student->photo_path);
        }

        $student->delete();
        AuditLog::log(auth()->user(), 'DELETE', 'Student', $student->id);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully');
    }

    public function getByClass($classId)
    {
        $students = Student::where('class_id', $classId)
            ->where('status', 'Active')
            ->get(['id', 'student_id', 'surname', 'other_names']);

        return response()->json($students);
    }
}
