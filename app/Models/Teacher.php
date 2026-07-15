<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

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

    protected $casts = [
        'subjects' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedClass()
    {
        return $this->belongsTo(StudentClass::class, 'assigned_class_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'recorded_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass($query, $classId)
    {
        return $query->where('assigned_class_id', $classId);
    }

    // Methods
    public function getAssignedStudents()
    {
        return $this->assignedClass->students ?? collect();
    }

    public function addSubject($subject)
    {
        if (!in_array($subject, $this->subjects ?? [])) {
            $this->subjects = array_merge($this->subjects ?? [], [$subject]);
            $this->save();
        }
    }

    public function removeSubject($subject)
    {
        $this->subjects = array_diff($this->subjects ?? [], [$subject]);
        $this->save();
    }
}
