<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralPayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'account_id',
        'order_id',
        'price',
        'payment_method',
        'statement',
        'notes',
        'verified',
        'date',
        'image_path',
        'handled_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class , 'general_payment_id');
    }
    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }
}