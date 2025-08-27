<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Stock;
use App\Models\ServiceReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'service_stock')
                ->withPivot(['count' , 'is_completed', 'not_completed_reason' , 'required_qty','id','latest_activity']);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_service');
    }

    public function reports()
    {
        return $this->hasMany(ServiceReport::class);
    }

    public function registrationforms()
    {
        return $this->hasMany(Registrationform::class, 'service_id');
    }


}
