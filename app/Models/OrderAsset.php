<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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
     * Relationship: Each asset belongs to an order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors - Return full URLs for media files from Hetzner
    |--------------------------------------------------------------------------
    */

    /**
     * Get full URL of the image.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? Storage::disk('hetzner')->url($this->image_path)
            : null;
    }

    /**
     * Get full URL of the audio.
     */
    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_path
            ? Storage::disk('hetzner')->url($this->audio_path)
            : null;
    }

    /**
     * Get full URL of the video.
     */
    public function getVideoUrlAttribute(): ?string
    {
        return $this->video_path
            ? Storage::disk('hetzner')->url($this->video_path)
            : null;
    }
}
