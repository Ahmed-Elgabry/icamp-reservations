<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = ["order_id",'account_id' , 'price' ,"insurance_status" ,'payment_method','statement','notes', "verified","account_id", "image_path", "handled_by"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class , 'payment_id');
    }

    public function scopeVerified()
    {
        return $this->where('verified', true);
    }

    public function isInsuranceReturned()
    {
        return $this->insurance_status === 'returned' && $this->order && $this->order->insurance_approved;
    }
}