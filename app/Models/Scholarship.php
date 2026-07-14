<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'has_scholarship',
        'scholarship_type',
        'sponsor_name',
        'sponsor_contact',
        'amount',
        'currency',
        'start_year',
        'end_year',
        'status',
        'certificate_path',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_scholarship' => 'boolean',
            'amount' => 'float',
            'start_year' => 'year',
            'end_year' => 'year',
        ];
    }

    /**
     * Relationship: Scholarship belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if scholarship is active
     */
    public function isActive()
    {
        return $this->status === 'Active';
    }
}
