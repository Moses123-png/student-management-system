<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'graduation_year' => 'year',
            'graduation_date' => 'date',
            'scholarship_received' => 'boolean',
        ];
    }

    /**
     * Relationship: Graduate belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
