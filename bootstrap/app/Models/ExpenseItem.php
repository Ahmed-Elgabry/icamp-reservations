<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
