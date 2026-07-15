<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{
    use HasFactory;

    protected $table = 'graduates';

    protected $fillable = [
        'student_id',
        'graduation_year',
        'graduation_date',
        'final_class',
        'achievement_level',
        'scholarship_received',
        'notes',
        'diploma_path',
    ];

    protected $casts = [
        'graduation_year' => 'year',
        'graduation_date' => 'date',
        'scholarship_received' => 'boolean',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopeByYear($query, $year)
    {
        return $query->where('graduation_year', $year);
    }

    public function scopeByAchievement($query, $level)
    {
        return $query->where('achievement_level', $level);
    }

    public function scopeWithScholarship($query)
    {
        return $query->where('scholarship_received', true);
    }

    // Methods
    public static function getAchievementLevels()
    {
        return [
            'Excellent',
            'Good',
            'Average',
            'Below Average',
        ];
    }
}
