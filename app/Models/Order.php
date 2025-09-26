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
use App\Models\SurveyEmailLog;
use App\Models\SurveyResponse;
use App\Models\TermsSittng;
use App\Traits\UploadTrait;
use App\Models\OrderInternalNote;
use App\Models\InternalNote;
use App\Models\PreLoginImage;
use App\Models\PreLogoutImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, UploadTrait;
    
    protected $fillable = [
        'customer_id',
        'price',
        'deposit',
        'insurance_amount',
        'notes',
        'date',
        'time_from',
        'time_to',
        'time_of_receipt',
        'time_of_receipt_notes',
        'delivery_time',
        'delivery_time_notes',
        'voice_note',
        'video_note',
        'image_before_receiving',
        'image_after_delivery',
        'status',
        'refunds',
        'refunds_notes',
        'delayed_time',
        'inventory_withdrawal',
        'insurance_status',
        'confiscation_description',
        'report_text',
        'show_price_notes',
        'order_data_notes',
        'invoice_notes',
        'receipt_notes',
        'people_count',
        "client_notes",
        "insurance_approved"
    ];
    
    // Alternatively, you can keep using guarded if you prefer
    // protected $guarded = [];

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
    
    /**
     * Get all internal notes for this order
     */
    public function internalNote()
    {
        return $this->hasOne(OrderInternalNote::class)->with(['creator', 'internalNote'])->latest();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber()
    {
        $year = substr(date('Y'), -2);
        $month = date('m');

        // Get the latest order number for this year/month
        $latestOrder = static::where('order_number', 'like',  $year . $month . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($latestOrder) {
            $lastNumber = (int) substr($latestOrder->order_number, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return  $year . $month . $nextNumber;
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
            ->withPivot('quantity', 'stock_id', 'service_id');
    }

    public function preLoginImages()
    {
        return $this->hasMany(PreLoginImage::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

    public function PreLogoutImages()
    {
        return $this->hasMany(PreLogoutImage::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function verifiedPayments()
    {
        return $this->payments()->where('verified', "1");
    }


    public function paymentLinks()
    {
        return $this->hasMany(PaymentLink::class);
    }
    public function TermsSittng()
    {
        return $this->hasMany(TermsSittng::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'order_addon')
            ->using(OrderAddon::class)
            ->withPivot('verified','count', 'price', 'description', 'id' , 'account_id' , 'payment_method')
            ->withTimestamps();
    }
    public function verifiedAddons()
    {
       return $this->addons()->wherePivot('verified', true);
    }
    public function verifiedInsurance()
    {
        return $this->verifiedPayments()->where('statement', 'the_insurance');
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
    public function internalNotes()
    {
        return $this->hasMany(OrderInternalNote::class)->latest();
    }

    public function latestInternalNote()
    {
        return $this->hasOne(OrderInternalNote::class)->latestOfMany();
    }
    
    public function internalNoteTemplates()
    {
        return $this->hasManyThrough(
            InternalNote::class,
            OrderInternalNote::class,
            'order_id',
            'id',
            'id',
            'internal_note_id'
        );
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function verifiedExpenses()
    {
        return $this->expenses()->where('verified', true);
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

     public function items() { 
        return $this->hasMany(OrderItem::class);
     }
     // after partial confiscated the confiscated amount is set to  transaction related to any payment to order
    public function insuranceFromTransaction()
    {
        $payment = $this->verifiedPayments()->where('statement', 'the_insurance')->first();
        return $payment && $payment->transaction ? $payment->transaction->amount : 0;
     }
     public function verifiedInsuranceAmount()
     {
         return $this->verifiedPayments()->where('statement', 'the_insurance')->sum('price');
     }
     public function verifiedWarehouseSalesAmount(){
        return OrderItem::where('verified', true)->where("order_id", $this->id)->sum('total_price');
     }
     // the total payment calucate the amount of payments except the deposit and addons and warehouse sales
     public function totalPaymentsPrice() {
        $deposit = $this->payments()->where('statement', 'deposit')->where("verified", "1")->sum('price');
        $insurances = $this->payments()->where('statement','the_insurance')->where("verified", "1")->sum('price');
        $addons = $this->verifiedAddons()->sum('order_addon.price');
        $warehouseSales = $this->verifiedWarehouseSalesAmount();
        return $this->price - $deposit + $insurances + $addons + $warehouseSales;
     }
    //  totalPaidAmount is the total of payments that is paid
     public function totalPaidAmount() {
        $totalPayment = $this->payments()->sum('price');
        $totalWareHouse = OrderItem::where("order_id", $this->id)->sum('total_price');
        $addons = $this->addons()->sum('order_addon.price');

        return $totalPayment + $totalWareHouse + $addons;
     }
     public function verifiedItems() {
        return $this->items(OrderItem::class)->where('verified', true);
     } 
     public function stocksItems() { 
         return $this->belongsToMany(Stock::class, 'order_items')
             ->withPivot(['unit_price','quantity'])
             ->using(OrderItemPivot::class)
             ->withTimestamps();
     }

    public function getItemsTotalAttribute()
    {
        return $this->items->sum('subtotal');
    }

    /**
     * Get the survey responses associated with the order.
     */
    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'reservation_id');
    }

    /**
     * Get the survey email log associated with the order.
     */
    public function surveyEmailLog()
    {
        return $this->hasOne(SurveyEmailLog::class);
    }
}
