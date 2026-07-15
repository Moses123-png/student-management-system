<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarship::query();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sponsor_name')) {
            $query->where('sponsor_name', 'like', "%{$request->sponsor_name}%");
        }

        if ($request->filled('type')) {
            $query->where('scholarship_type', $request->type);
        }

        $scholarships = $query->with('student')
            ->paginate(20);

        return view('admin.scholarships.index', [
            'scholarships' => $scholarships,
            'types' => Scholarship::getTypes(),
            'statuses' => Scholarship::getStatuses(),
        ]);
    }

    public function create()
    {
        return view('admin.scholarships.create', [
            'students' => Student::where('status', 'Active')->get(),
            'types' => Scholarship::getTypes(),
            'statuses' => Scholarship::getStatuses(),
            'currencies' => Scholarship::getCurrencies(),
            'years' => range(date('Y'), date('Y') + 10),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'has_scholarship' => 'boolean',
            'scholarship_type' => 'required|in:' . implode(',', Scholarship::getTypes()),
            'sponsor_name' => 'required|string|max:150',
            'sponsor_contact' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:UGX,USD,EUR,GBP',
            'start_year' => 'required|integer',
            'end_year' => 'required|integer|gte:start_year',
            'status' => 'required|in:' . implode(',', Scholarship::getStatuses()),
            'certificate' => 'nullable|file|max:2048',
            'notes' => 'nullable|string',
        ]);

        $validated['has_scholarship'] = true;

        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('scholarships', 'public');
            $validated['certificate_path'] = $path;
        }

        $scholarship = Scholarship::create($validated);
        AuditLog::log(auth()->user(), 'CREATE', 'Scholarship', $scholarship->id, [], $validated);

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship created successfully');
    }

    public function show(Scholarship $scholarship)
    {
        return view('admin.scholarships.show', [
            'scholarship' => $scholarship->load('student'),
        ]);
    }

    public function edit(Scholarship $scholarship)
    {
        return view('admin.scholarships.edit', [
            'scholarship' => $scholarship,
            'types' => Scholarship::getTypes(),
            'statuses' => Scholarship::getStatuses(),
            'currencies' => Scholarship::getCurrencies(),
            'years' => range(date('Y'), date('Y') + 10),
        ]);
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'scholarship_type' => 'required|in:' . implode(',', Scholarship::getTypes()),
            'sponsor_name' => 'required|string|max:150',
            'sponsor_contact' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:UGX,USD,EUR,GBP',
            'start_year' => 'required|integer',
            'end_year' => 'required|integer|gte:start_year',
            'status' => 'required|in:' . implode(',', Scholarship::getStatuses()),
            'notes' => 'nullable|string',
        ]);

        $oldValues = $scholarship->toArray();
        $scholarship->update($validated);
        AuditLog::log(auth()->user(), 'UPDATE', 'Scholarship', $scholarship->id, $oldValues, $validated);

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship updated successfully');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();
        AuditLog::log(auth()->user(), 'DELETE', 'Scholarship', $scholarship->id);

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully');
    }

    public function report(Request $request)
    {
        $query = Scholarship::where('status', 'Active');

        if ($request->filled('type')) {
            $query->where('scholarship_type', $request->type);
        }

        if ($request->filled('sponsor_name')) {
            $query->where('sponsor_name', 'like', "%{$request->sponsor_name}%");
        }

        $scholarships = $query->with('student')->get();

        return view('admin.scholarships.report', [
            'scholarships' => $scholarships,
            'types' => Scholarship::getTypes(),
        ]);
    }
}
