<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItemPivot extends Pivot
{
    protected $table = 'order_items';
    protected $guarded = [];
}
