<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'relationship',
        'occupation',
        'address',
    ];

    /**
     * Relationship: Guardian has many Students
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
