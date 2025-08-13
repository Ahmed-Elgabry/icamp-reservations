<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    protected $fillable = [
        'meeting_number', 'date', 'start_time', 'end_time',
        'location_id', 'notes', 'created_by'
    ];

    protected $casts = [
        'date' => 'date',
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(MeetingAttendee::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(MeetingTopic::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latestMeeting = Meeting::latest()->first();
            $nextNumber = $latestMeeting ? (int) str_replace('MTG-', '', $latestMeeting->meeting_number) + 1 : 1;
            $model->meeting_number = 'MTG-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }

    public function location()
    {
        return $this->belongsTo(MeetingLocation::class, 'location_id');
    }
}
