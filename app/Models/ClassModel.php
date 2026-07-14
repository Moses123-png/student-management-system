<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class_name',
        'academic_year',
        'teacher_id',
        'total_students',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: Class belongs to Teacher
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relationship: Class has many Students
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Relationship: Class has many Attendance records
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relationship: Class has many Report Cards
     */
    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    /**
     * Get all active students in class
     */
    public function getActiveStudents()
    {
        return $this->students()->where('status', 'Active')->get();
    }

    /**
     * Get class level (P.1, P.2, etc)
     */
    public function getClassLevelAttribute()
    {
        return $this->class_name;
    }
}
