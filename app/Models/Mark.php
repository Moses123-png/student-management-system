<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject',
        'academic_year',
        'term',
        'test_1_score',
        'test_2_score',
        'assignment_score',
        'exam_score',
        'total_score',
        'grade',
        'teacher_id',
    ];

    protected $casts = [
        'academic_year' => 'year',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade', $grade);
    }

    // Methods
    public function calculateTotal()
    {
        $scores = array_filter([
            $this->test_1_score,
            $this->test_2_score,
            $this->assignment_score,
            $this->exam_score,
        ]);

        return count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
    }

    public function calculateGrade()
    {
        $total = $this->total_score;

        if ($total >= 90) return 'A';
        if ($total >= 80) return 'B';
        if ($total >= 70) return 'C';
        if ($total >= 60) return 'D';
        if ($total >= 50) return 'E';
        return 'F';
    }

    public function getGradeDescription()
    {
        $descriptions = [
            'A' => 'Excellent',
            'B' => 'Good',
            'C' => 'Very Good',
            'D' => 'Satisfactory',
            'E' => 'Pass',
            'F' => 'Fail',
        ];

        return $descriptions[$this->grade] ?? 'Unknown';
    }

    public static function getSubjects()
    {
        return [
            'Mathematics',
            'English',
            'Science',
            'Social Studies',
            'Religious Education',
            'Local Language',
        ];
    }
}
