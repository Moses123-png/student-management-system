<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'academic_year' => 'year',
            'generated_at' => 'datetime',
        ];
    }

    /**
     * Relationship: Report Card belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: Report Card belongs to Class
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Get all marks for this report card
     */
    public function getMarks()
    {
        return $this->student->getTermMarks($this->term, $this->academic_year);
    }
}
