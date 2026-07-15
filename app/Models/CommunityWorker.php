<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityWorker extends Model
{
    use HasFactory;

    protected $table = 'community_workers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'zone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }

    // Methods
    public function getStudentCount()
    {
        return $this->students()->count();
    }

    public static function getZones()
    {
        return [
            'Nansana East',
            'Nansana West',
            'Nansana North',
            'Nansana South',
            'Nansana Central',
        ];
    }
}
