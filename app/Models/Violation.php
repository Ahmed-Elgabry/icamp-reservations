<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Violation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'violation_type_id',
        'employee_id',
        'created_by',
        'violation_date',
        'violation_time',
        'violation_place',
        'photo_path',
        'employee_justification',
        'action_taken',
        'deduction_amount',
        'notes'
    ];

    protected $casts = [
        'violation_date' => 'date',
        'deduction_amount' => 'decimal:2'
    ];

    // Relationships
    public function type()
    {
        return $this->belongsTo(ViolationType::class, 'violation_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor for photo URL
    public function getPhotoUrlAttribute()
    {
        return $this->photo_path ? Storage::url($this->photo_path) : null;
    }

    // Filter scope
    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['employee_id'] ?? null, function ($query, $employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->when($filters['violation_type_id'] ?? null, function ($query, $typeId) {
                $query->where('violation_type_id', $typeId);
            })
            ->when($filters['action_taken'] ?? null, function ($query, $actionTaken) {
                $query->where('action_taken', $actionTaken);
            });
    }
}
