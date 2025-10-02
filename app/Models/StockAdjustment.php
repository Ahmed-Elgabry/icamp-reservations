<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustments';

    protected $fillable = [
        'stock_id',
        'available_quantity_after',
        'type',
        'order_id',
        'reason',
        'custom_reason',
        'note',
        'verified',
        'available_quantity_before',
        'available_percentage_before',
        'percentage',
        "source",
        'image',
        'employee_name',
        'date_time',
        'order_item_id',
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    /**
     * Return true if this adjustment is a decrement.
     */
    public function isDecrement(): bool
    {
        return $this->type === 'decrement';
    }

    /**
     * Return true if this adjustment is an increment.
     */
    public function isIncrement(): bool
    {
        return $this->type === 'increment';
    }
}
