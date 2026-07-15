<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

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

    protected $casts = [
        'has_scholarship' => 'boolean',
        'start_year' => 'year',
        'end_year' => 'year',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('scholarship_type', $type);
    }

    public function scopeBySponsor($query, $sponsor)
    {
        return $query->where('sponsor_name', $sponsor);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'Active';
    }

    public function isExpiring()
    {
        return $this->end_year && $this->end_year === now()->year;
    }

    public static function getTypes()
    {
        return [
            'Secondary School',
            'University',
            'Technical School',
            'Other',
        ];
    }

    public static function getStatuses()
    {
        return [
            'Active',
            'Completed',
            'Pending',
            'Cancelled',
        ];
    }

    public static function getCurrencies()
    {
        return [
            'UGX' => 'Ugandan Shilling',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
        ];
    }
}
