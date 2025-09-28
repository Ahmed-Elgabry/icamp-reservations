<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAsset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'video_path',
        'audio_path',
        'image_path',
        'notes',
    ];

    /**
     * Get the order that owns the asset.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
