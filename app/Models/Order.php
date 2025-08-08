<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Addon;
use App\Models\Stock;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Customer;
use App\Models\OrderRate;
use App\Models\OrderReport;
use App\Models\TermsSittng;
use App\Traits\UploadTrait;
use App\Models\PreLoginImage;
use App\Models\PreLogoutImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, UploadTrait;
    protected $guarded = [];

    public function addHoursCount()
    {
        if ($this->time_from && $this->time_to) {
            $timeFrom = Carbon::parse($this->time_from);
            $timeTo = Carbon::parse($this->time_to);

            // Calculate the difference in hours
            $hoursCount = $timeTo->diffInHours($timeFrom);


            return $hoursCount;
        }

        return null; // Return null if one of the time fields is null
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_service')->withPivot('price');
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'order_stock')
            ->withPivot('quantity', 'stock_id', 'service_id', 'image');
    }

    public function preLoginImages()
    {
        return $this->hasMany(PreLoginImage::class);
    }

    public function PreLogoutImages()
    {
        return $this->hasMany(PreLogoutImage::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function TermsSittng()
    {
        return $this->hasMany(TermsSittng::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'order_addon')
            ->withPivot('count', 'price', 'description', 'id')
            ->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function rate()
    {
        return $this->hasOne(OrderRate::class, 'order_id');
    }


    public function reports()
    {
        return $this->hasMany(OrderReport::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function setImageBeforeReceivingAttribute($value)
    {
        if ($value) {
            $this->attributes['image_before_receiving'] = $this->storeFile('orders', $value);
        }
    }

    public function getImageBeforeReceivingAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
    }

    public function setImageAfterDeliveryAttribute($value)
    {
        if ($value) {
            $this->attributes['image_after_delivery'] = $this->storeFile('orders', $value);
        }
    }

    public function getImageAfterDeliveryAttribute($value)
    {
        if ($value) {
            return asset('storage/' . $value);
        }
    }
    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['price_min'] ?? false, function ($builder, $value) {
            $builder->where('price', '>=', $value);
        });
        $builder->when($filters['price_max'] ?? false, function ($builder, $value) {
            $builder->where('price', '<=', $value);
        });
        $builder->when($filters['price'] ?? false, function ($builder, $value) {
            $builder->where('price', 'like', "%{$value}%");
        });
    }

     public function items()       { return $this->hasMany(OrderItem::class); }
     public function stocksItems()      { return $this->belongsToMany(Stock::class, 'order_items')
                                                  ->withPivot(['unit_price','quantity'])
                                                  ->using(OrderItemPivot::class)
                                                  ->withTimestamps(); }

    public function getItemsTotalAttribute()
    {
        return $this->items->sum('subtotal');
    }

}
