<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

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

    protected $casts = [
        'date_of_birth' => 'date',
        'entry_year' => 'year',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function communityWorker()
    {
        return $this->belongsTo(CommunityWorker::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classPromotions()
    {
        return $this->hasMany(ClassPromotion::class);
    }

    public function graduate()
    {
        return $this->hasOne(Graduate::class);
    }

    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->surname} {$this->other_names}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeGraduated($query)
    {
        return $query->where('status', 'Graduated');
    }

    public function scopeDroppedOut($query)
    {
        return $query->where('status', 'Dropped Out');
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    // Methods
    public function getAverageMarks()
    {
        return $this->marks()
            ->selectRaw('AVG(total_score) as average')
            ->pluck('average')
            ->first();
    }

    public function getCurrentClass()
    {
        return $this->class;
    }

    public function hasScholarship()
    {
        return $this->scholarships()
            ->where('status', 'Active')
            ->exists();
    }

    public function getAttendancePercentage($term = null, $year = null)
    {
        $query = $this->attendance();
        
        if ($term) {
            $query->where('term', $term);
        }
        
        if ($year) {
            $query->where('academic_year', $year);
        }

        $total = $query->count();
        if ($total === 0) return 0;

        $present = $query->where('status', 'Present')->count();
        return round(($present / $total) * 100, 2);
    }
}
