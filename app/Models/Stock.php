<?php

namespace App\Models;

use App\Models\Order;
use App\Traits\UploadTrait;
use App\Models\StockActivity;
use App\Models\StockQuantity;
use App\Models\ServiceStock;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Stock extends Model
{
    use HasFactory, UploadTrait;

    protected $guarded = [];

    public function setImageAttribute($value)
    {
        if ($value && is_file($value)) {
            $this->attributes['image'] = $this->StoreFile('images', $value);
        }
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    //calucate the required stock count in service stock
    //public function service_stock_count (){
        //return $this->service_stocks->sum('count');
   // }

    // دالة لحفظ الملفات
    protected function StoreFile($directory, $file)
    {
        try {
            return $file->store($directory, 'public');
        } catch (\Exception $e) {
            \Log::error('File storage error: ' . $e->getMessage());
            return null;
        }
    }
    public function quantities()
    {
        return $this->hasMany(StockQuantity::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_stock')
            ->withPivot('quantity');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activities()
    {
        return $this->hasMany(StockActivity::class);
    }
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class ,'stock_id' , 'id');
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when(isset($filters['quantity_min']) && $filters['quantity_min'] !== '', function ($builder, $value) {
            $builder->where('quantity', '>=', $value);
        });

        $builder->when(isset($filters['quantity_max']) && $filters['quantity_max'] !== '', function ($builder, $value) {
            $builder->where('quantity', '<=', $value);
        });

        $builder->when(isset($filters['quantity']) && $filters['quantity'] !== '', function ($builder, $value) {
            $builder->where('quantity', 'like', "%{$value}%");
        });
        $builder->when(isset($filters['higher_selling']) && $filters['higher_selling'], function ($builder) {
            $builder->withSum('orderItems as order_items_total', 'order_items.quantity')
                ->orderBy('order_items_total', 'desc');
        });
    }

}
                                                                                                                              
