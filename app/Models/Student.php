<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'surname',
        'other_names',
        'gender',
        'date_of_birth',
        'photo_path',
        'entry_year',
        'class_id',
        'status',
        'guardian_id',
        'community_worker_id',
        'zone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'entry_year' => 'year',
        ];
    }

    /**
     * Get student's full name
     */
    public function getFullNameAttribute()
    {
        return $this->surname . ' ' . $this->other_names;
    }

    /**
     * Get student's age from DOB
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth->diffInYears(now());
    }

    /**
     * Relationship: Student belongs to Class
     */
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * Relationship: Student belongs to Guardian
     */
    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    /**
     * Relationship: Student belongs to Community Worker
     */
    public function communityWorker()
    {
        return $this->belongsTo(CommunityWorker::class, 'community_worker_id');
    }

    /**
     * Relationship: Student has many Marks
     */
    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    /**
     * Relationship: Student has one Scholarship
     */
    public function scholarship()
    {
        return $this->hasOne(Scholarship::class);
    }

    /**
     * Relationship: Student has many Attendance records
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relationship: Student has many Class Promotions
     */
    public function promotions()
    {
        return $this->hasMany(ClassPromotion::class);
    }

    /**
     * Relationship: Student has one Graduate record
     */
    public function graduate()
    {
        return $this->hasOne(Graduate::class);
    }

    /**
     * Relationship: Student has many Report Cards
     */
    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    /**
     * Get marks for specific subject and term
     */
    public function getMarksBySubjectAndTerm($subject, $term, $year = null)
    {
        $year = $year ?? now()->year;
        return $this->marks()
            ->where('subject', $subject)
            ->where('term', $term)
            ->where('academic_year', $year)
            ->first();
    }

    /**
     * Get all marks for a specific year and term
     */
    public function getTermMarks($term, $year = null)
    {
        $year = $year ?? now()->year;
        return $this->marks()
            ->where('term', $term)
            ->where('academic_year', $year)
            ->get();
    }

    /**
     * Check if student is graduated
     */
    public function isGraduated()
    {
        return $this->status === 'Graduated' || $this->graduate()->exists();
    }

    /**
     * Get student's current class level
     */
    public function getCurrentClassLevel()
    {
        return $this->class->class_name ?? null;
    }
}
