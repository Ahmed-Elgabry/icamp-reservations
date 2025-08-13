<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentDirectory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'is_active', 'created_by'];

    public function items(): HasMany
    {
        return $this->hasMany(EquipmentDirectoryItem::class, 'directory_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getItemsCountAttribute(): int
    {
        return $this->items()->count();
    }

    public function getMediaCountAttribute(): int
    {
        return $this->items()->with('media')->get()->sum(function ($item) {
            return $item->media->count();
        });
    }
}
