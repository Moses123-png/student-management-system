<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'relationship',
        'occupation',
        'address',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Scopes
    public function scopeByRelationship($query, $relationship)
    {
        return $query->where('relationship', $relationship);
    }

    public function scopeByOccupation($query, $occupation)
    {
        return $query->where('occupation', $occupation);
    }

    // Methods
    public function getStudentCount()
    {
        return $this->students()->count();
    }

    public static function getRelationships()
    {
        return [
            'Father',
            'Mother',
            'Uncle',
            'Aunt',
            'Brother',
            'Sister',
            'Grandfather',
            'Grandmother',
            'Cousin',
            'Other',
        ];
    }
}
