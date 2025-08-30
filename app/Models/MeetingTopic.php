<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingTopic extends Model
{
    protected $fillable = [
        'meeting_id', 'topic', 'discussion',
        'action_items', 'assigned_to', 'task_id', 'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
