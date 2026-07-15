<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year',
        'term',
        'class_id',
        'teacher_comment',
        'teacher_signature_path',
        'principal_comment',
        'principal_signature_path',
        'generated_at',
        'pdf_path',
    ];

    protected $casts = [
        'academic_year' => 'year',
        'generated_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    // Scopes
    public function scopeByYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    // Methods
    public function getStudentMarks()
    {
        return $this->student->marks()
            ->where('academic_year', $this->academic_year)
            ->where('term', $this->term)
            ->get();
    }

    public function getTotalPoints()
    {
        return $this->getStudentMarks()->sum('total_score');
    }

    public function getOverallGrade()
    {
        $average = $this->getStudentMarks()->avg('total_score');
        
        if ($average >= 90) return 'A';
        if ($average >= 80) return 'B';
        if ($average >= 70) return 'C';
        if ($average >= 60) return 'D';
        if ($average >= 50) return 'E';
        return 'F';
    }

    public function getClassRanking()
    {
        $studentMarks = $this->student->marks()
            ->where('academic_year', $this->academic_year)
            ->where('term', $this->term)
            ->avg('total_score');

        $totalStudents = $this->class->students->count();
        
        $ranking = $this->class->students()
            ->join('marks', 'students.id', '=', 'marks.student_id')
            ->where('marks.academic_year', $this->academic_year)
            ->where('marks.term', $this->term)
            ->selectRaw('COUNT(DISTINCT students.id) as rank')
            ->where('marks.total_score', '>', $studentMarks)
            ->first();

        return ($ranking->rank ?? 0) + 1;
    }
}
