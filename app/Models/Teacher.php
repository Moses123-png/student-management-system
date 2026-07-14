<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'qualification',
        'assigned_class_id',
        'subjects',
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
            'subjects' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationship: Teacher belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Teacher has assigned Class
     */
    public function assignedClass()
    {
        return $this->belongsTo(ClassModel::class, 'assigned_class_id');
    }

    /**
     * Relationship: Teacher has many Marks (recorded)
     */
    public function marksRecorded()
    {
        return $this->hasManyThrough(
            Mark::class,
            User::class,
            'id',
            'teacher_id',
            'user_id',
            'id'
        );
    }
}
