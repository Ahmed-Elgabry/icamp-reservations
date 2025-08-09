<?php

namespace App\Models;

use App\Models\Order;
use App\Traits\UploadTrait;
use App\Models\StockActivity;
use App\Models\StockQuantity;
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

    public function activities()
    {
        return $this->hasMany(StockActivity::class);
    }
    // في نموذج Stock
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
    }

}
