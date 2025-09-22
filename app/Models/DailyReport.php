<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'details',
        'notes',
        'employee_id',
        'audio_attachment',
        'video_attachment',
        'photo_attachment'
    ];

    /**
     * Relationship to the employee who created the report
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * Filter scope for reports
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['employee_id'] ?? false, fn($query, $employeeId) =>
        $query->where('employee_id', $employeeId)
        )
            ->when($filters['date_from'] ?? false, fn($query, $date) =>
            $query->whereDate('created_at', '>=', $date)
            )
            ->when($filters['date_to'] ?? false, fn($query, $date) =>
            $query->whereDate('created_at', '<=', $date)
            )
            ->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where(function($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('details', 'like', '%'.$search.'%')
                    ->orWhere('notes', 'like', '%'.$search.'%');
            })
            );
    }

    /**
     * Get the URL for an attachment
     */
    public function getAttachmentUrl(string $field): ?string
    {
        return $this->$field ? Storage::url($this->$field) : null;
    }

    /**
     * Check if report has any media attachments
     */
    public function hasAttachments(): bool
    {
        return $this->audio_attachment ||
            $this->video_attachment ||
            $this->photo_attachment;
    }
}
