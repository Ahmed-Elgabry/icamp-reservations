<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceLink extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة  
    protected $fillable = ['order_id', 'link'];

    // (اختياري) إذا كنت ترغب في تعريف العلاقة مع نموذج Order  
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}