<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentClass;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::query();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('attendance_date')) {
            $query->where('attendance_date', $request->attendance_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendance = $query->with(['student', 'class', 'recordedByTeacher'])
            ->paginate(50);

        return view('admin.attendance.index', [
            'attendance' => $attendance,
            'classes' => StudentClass::all(),
            'statuses' => Attendance::getStatuses(),
        ]);
    }

    public function record(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'attendance_date' => 'required|date',
            'attendance_list' => 'required|array',
            'attendance_list.*.student_id' => 'required|exists:students,id',
            'attendance_list.*.status' => 'required|in:Present,Absent,Excused,Late',
            'attendance_list.*.notes' => 'nullable|string',
        ]);

        $recorded = 0;
        foreach ($validated['attendance_list'] as $record) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'class_id' => $validated['class_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'status' => $record['status'],
                    'notes' => $record['notes'] ?? null,
                    'recorded_by' => auth()->user()->teacher->id ?? null,
                ]
            );
            $recorded++;
        }

        AuditLog::log(auth()->user(), 'RECORD_ATTENDANCE', 'Attendance', 0, [], $validated);
        return back()->with('success', "{$recorded} attendance records saved successfully");
    }

    public function report(Request $request)
    {
        $query = Attendance::query();

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('start_date')) {
            $query->where('attendance_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('attendance_date', '<=', $request->end_date);
        }

        $attendance = $query->with('student', 'class')
            ->get();

        return view('admin.attendance.report', [
            'attendance' => $attendance,
            'classes' => StudentClass::all(),
        ]);
    }
}
