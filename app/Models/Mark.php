<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'academic_year' => 'year',
            'total_score' => 'float',
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mark) {
            $mark->total_score = $mark->calculateTotalScore();
            $mark->grade = $mark->calculateGrade();
        });

        static::updating(function ($mark) {
            $mark->total_score = $mark->calculateTotalScore();
            $mark->grade = $mark->calculateGrade();
        });
    }

    /**
     * Calculate total score
     */
    public function calculateTotalScore()
    {
        $scores = [
            $this->test_1_score ?? 0,
            $this->test_2_score ?? 0,
            $this->assignment_score ?? 0,
            $this->exam_score ?? 0,
        ];

        $count = count(array_filter($scores));
        if ($count === 0) return null;

        return array_sum($scores) / 4;
    }

    /**
     * Calculate grade based on total score
     */
    public function calculateGrade()
    {
        $total = $this->total_score;

        if ($total === null) return null;

        if ($total >= 90) return 'A';
        if ($total >= 80) return 'B';
        if ($total >= 70) return 'C';
        if ($total >= 60) return 'D';
        if ($total >= 50) return 'E';
        return 'F';
    }

    /**
     * Relationship: Mark belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: Mark belongs to Teacher
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
