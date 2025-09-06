<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampReportItem extends Model
{
    protected $fillable = [
        'camp_report_id',
        'item_name',
        'notes',
        'photo_path',
        'audio_path',
        'video_path'
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CampReport::class);
    }
}
