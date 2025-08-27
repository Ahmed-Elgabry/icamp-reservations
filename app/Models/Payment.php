<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

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