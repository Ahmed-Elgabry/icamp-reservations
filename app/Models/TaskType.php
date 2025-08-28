<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationship with tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Scope for active task types
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for inactive task types
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
