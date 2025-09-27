<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderAddon extends Pivot
{
    use HasFactory;

    protected $table = 'order_addon';
    protected $guarded = [];
    public $timestamps = true;
    public $incrementing = true;
    protected $primaryKey = 'id';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }
}
