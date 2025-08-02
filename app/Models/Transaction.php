<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

     // Define the sender relationship
    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }

    // Define the receiver relationship
    public function receiver()
    {
        return $this->belongsTo(BankAccount::class, 'receiver_id');
    }

    // Define the supplier relationship
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Define the customer relationship
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function editRoute($id)
    {
        if($this->type == 'Expense'){
            return route('expenses.edit', $id);
        }elseif($this->type == 'Payment'){
            return route('payments.edit', $id);
        }elseif($this->type == 'Withdrawal'){
          return   route('bank-accounts.show', $this->id);
        }
    }

}
