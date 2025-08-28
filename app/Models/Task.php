<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'created_by',
        'due_date',
        'priority',
        'status',
        'failure_reason',
        'photo_attachment',
        'video_attachment',
        'audio_attachment',
        'task_type_id'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class);
    }

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($task) {
            DatabaseNotification::where('type', 'App\Notifications\TaskAssignedNotification')
                ->where('data->task_id', $task->id)
                ->delete();
        });
    }
}
