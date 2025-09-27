<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];
    protected $table = 'order_items';
    
    protected $fillable = [
        'order_id',
        'stock_id',
        'quantity',
        'total_price',
        'account_id',
        'notes',
        'handled_by',
        'account_id',
        'payment_method',
        'verified'

    ];


    public function order() { return $this->belongsTo(Order::class); }
    public function stock() { return $this->belongsTo(Stock::class); }
    public function account() { return $this->belongsTo(BankAccount::class, 'account_id'); }
    public function handledBy() { return $this->belongsTo(User::class, 'handled_by'); }
}
