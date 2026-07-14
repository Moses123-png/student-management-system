<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ClassPromotionController;
use App\Http\Controllers\ScholarshipController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        // Students Management
        Route::resource('students', StudentController::class);
        Route::get('students/by-class/{classId}', [StudentController::class, 'getByClass'])->name('students.by-class');

        // Marks Management
        Route::resource('marks', MarkController::class);
        Route::post('marks/bulk-entry', [MarkController::class, 'bulkEntry'])->name('marks.bulk-entry');
        Route::get('marks/report/{year}/{term}', [MarkController::class, 'report'])->name('marks.report');

        // Class Management
        Route::resource('classes', ClassPromotionController::class);
        Route::post('classes/promote', [ClassPromotionController::class, 'promoteStudents'])->name('classes.promote');
        Route::get('classes/{class}/report', [ClassPromotionController::class, 'classReport'])->name('classes.report');

        // Scholarships
        Route::resource('scholarships', ScholarshipController::class);
        Route::get('scholarships/report', [ScholarshipController::class, 'report'])->name('scholarships.report');

        // Report Cards
        Route::get('report-cards', [MarkController::class, 'reportCards'])->name('report-cards.index');
        Route::get('report-cards/{student}/{year}/{term}', [MarkController::class, 'generateReportCard'])->name('report-cards.generate');
        Route::get('report-cards/{id}/pdf', [MarkController::class, 'downloadReportCard'])->name('report-cards.download');

        // Graduation
        Route::get('graduates', [ClassPromotionController::class, 'graduates'])->name('graduates.index');
        Route::get('graduates/{year}', [ClassPromotionController::class, 'graduatesByYear'])->name('graduates.year');
    });

    // Teacher Routes
    Route::middleware('teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'teacherDashboard'])->name('dashboard');

        // View class students
        Route::get('/class', [ClassPromotionController::class, 'showClass'])->name('class.show');
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

        // Mark entry
        Route::get('/marks/entry', [MarkController::class, 'entryForm'])->name('marks.entry');
        Route::post('/marks/entry', [MarkController::class, 'storeBatch'])->name('marks.store-batch');
        Route::get('/marks/{student}', [MarkController::class, 'showStudentMarks'])->name('marks.show');

        // Attendance
        Route::post('/attendance/record', [MarkController::class, 'recordAttendance'])->name('attendance.record');

        // Report cards
        Route::get('/report-cards/{student}/{year}/{term}', [MarkController::class, 'generateReportCard'])->name('report-cards.generate');
    });

    // Common routes (for authenticated users)
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});
