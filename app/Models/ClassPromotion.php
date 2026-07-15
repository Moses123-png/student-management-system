<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_class_id',
        'to_class_id',
        'academic_year',
        'promotion_date',
        'promoted_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'academic_year' => 'year',
        'promotion_date' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClass()
    {
        return $this->belongsTo(StudentClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(StudentClass::class, 'to_class_id');
    }

    public function promotedBy()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }

    // Scopes
    public function scopeByYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePromoted($query)
    {
        return $query->where('status', 'Promoted');
    }

    public function scopeGraduated($query)
    {
        return $query->where('status', 'Graduated');
    }

    // Methods
    public static function getStatuses()
    {
        return [
            'Promoted',
            'Held Back',
            'Graduated',
        ];
    }
}
