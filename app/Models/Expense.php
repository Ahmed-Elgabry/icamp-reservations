<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'expense_item_id', 
        'price', 
        'payment_method', 
        'date', 
        'source', 
        'notes', 
        'statement', 
        'account_id', 
        'order_id', 
        'verified', 
        'image_path',
        'handled_by'
    ];

    public function expenseItem()
    {
        return $this->belongsTo(ExpenseItem::class);
    }
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'expense_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeVerified()
    {
        return $this->where('verified', true);
    }
}
