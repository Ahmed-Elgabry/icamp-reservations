<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Violation extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'violation_type_id',
        'employee_id',
        'created_by',
        'employee_justification',
        'action_taken',
        'deduction_amount',
        'notes'
    ];

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

    /**
     * Scope a query to filter results.
     *
     * @param Builder $query
     * @param  array  $filters
     * @return Builder
     */
    public function scopeFilter($query, array $filters)
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
