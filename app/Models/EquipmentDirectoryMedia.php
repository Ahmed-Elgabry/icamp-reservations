<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentDirectoryMedia extends Model
{
    protected $fillable = ['item_id', 'file_path', 'file_type'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(EquipmentDirectoryItem::class, 'item_id');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
