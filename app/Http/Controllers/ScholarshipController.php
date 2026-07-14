<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    /**
     * Display all scholarships
     */
    public function index(Request $request)
    {
        $query = Scholarship::with('student');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sponsor')) {
            $query->where('sponsor_name', 'like', '%' . $request->sponsor . '%');
        }

        $scholarships = $query->paginate(20);
        $sponsors = Scholarship::where('has_scholarship', true)
                              ->distinct()
                              ->pluck('sponsor_name');

        return view('admin.scholarships.index', compact('scholarships', 'sponsors'));
    }

    /**
     * Show scholarship form
     */
    public function create()
    {
        $students = Student::where('status', 'Active')->get();
        return view('admin.scholarships.create', compact('students'));
    }

    /**
     * Store scholarship
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'has_scholarship' => 'required|boolean',
            'scholarship_type' => 'nullable|string',
            'sponsor_name' => 'nullable|string',
            'sponsor_contact' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'start_year' => 'nullable|integer',
            'end_year' => 'nullable|integer',
            'status' => 'required|in:Active,Completed,Pending,Cancelled',
            'notes' => 'nullable|string',
        ]);

        Scholarship::create($validated);

        return redirect()->route('admin.scholarships.index')
                        ->with('success', 'Scholarship created successfully!');
    }

    /**
     * Edit scholarship
     */
    public function edit(Scholarship $scholarship)
    {
        $students = Student::where('status', 'Active')->get();
        return view('admin.scholarships.edit', compact('scholarship', 'students'));
    }

    /**
     * Update scholarship
     */
    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'has_scholarship' => 'required|boolean',
            'scholarship_type' => 'nullable|string',
            'sponsor_name' => 'nullable|string',
            'sponsor_contact' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'currency' => 'nullable|string|max:3',
            'start_year' => 'nullable|integer',
            'end_year' => 'nullable|integer',
            'status' => 'required|in:Active,Completed,Pending,Cancelled',
            'notes' => 'nullable|string',
        ]);

        $scholarship->update($validated);

        return redirect()->route('admin.scholarships.index')
                        ->with('success', 'Scholarship updated successfully!');
    }

    /**
     * Delete scholarship
     */
    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')
                        ->with('success', 'Scholarship deleted successfully!');
    }

    /**
     * Generate scholarship report
     */
    public function report()
    {
        $scholarships = Scholarship::where('has_scholarship', true)
                                  ->with('student')
                                  ->get()
                                  ->groupBy('sponsor_name');

        return view('admin.scholarships.report', compact('scholarships'));
    }
}
