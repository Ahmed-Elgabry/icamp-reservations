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
        'quantity',
        'type',
        'order_id',
        'reason',
        'custom_reason',
        'note',
        'verified',
    'available_quantity_before',
        'image',
        'employee_name',
        'date_time',
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
