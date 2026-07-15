<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_name',
        'academic_year',
        'teacher_id',
        'total_students',
        'is_active',
    ];

    protected $casts = [
        'academic_year' => 'year',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByName($query, $name)
    {
        return $query->where('class_name', $name);
    }

    // Methods
    public function getStudentCount()
    {
        return $this->students()->count();
    }

    public function getAveragePerformance()
    {
        return $this->students()
            ->join('marks', 'students.id', '=', 'marks.student_id')
            ->avg('marks.total_score');
    }

    public function getClassRanking()
    {
        return $this->students()
            ->join('marks', 'students.id', '=', 'marks.student_id')
            ->selectRaw('students.id, students.full_name, AVG(marks.total_score) as avg_score')
            ->groupBy('students.id', 'students.full_name')
            ->orderByDesc('avg_score')
            ->get();
    }
}
