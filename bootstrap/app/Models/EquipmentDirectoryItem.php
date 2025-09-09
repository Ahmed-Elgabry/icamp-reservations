<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentDirectoryItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'directory_id', 'type', 'name', 'location',
        'quantity', 'notes', 'is_active', 'created_by'
    ];

    public function directory(): BelongsTo
    {
        return $this->belongsTo(EquipmentDirectory::class, 'directory_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(EquipmentDirectoryMedia::class, 'item_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
