<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPromotion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'academic_year' => 'year',
            'promotion_date' => 'datetime',
        ];
    }

    /**
     * Relationship: Promotion belongs to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship: From Class
     */
    public function fromClass()
    {
        return $this->belongsTo(ClassModel::class, 'from_class_id');
    }

    /**
     * Relationship: To Class
     */
    public function toClass()
    {
        return $this->belongsTo(ClassModel::class, 'to_class_id');
    }

    /**
     * Relationship: Promoted by User
     */
    public function promotedBy()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }
}
