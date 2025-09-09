<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'report_date',
        'service_id',
        'camp_name',
        'created_by',
        'general_notes'
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CampReportItem::class);
    }
}
