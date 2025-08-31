<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ["order_id",'account_id' , 'price'  ,'payment_method','statement','notes', "verified","account_id"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }

    public function scopeVerified()
    {
        return $this->where('verified', true);
    }
}