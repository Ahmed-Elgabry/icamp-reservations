<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_status_check' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope للاستعلامات الدورية - المدفوعات التي لم يتم فحصها مؤخراً
     */
    public function scopeNeedsStatusCheck($query, $minutes = 5)
    {
        return $query->where(function ($q) use ($minutes) {
            $q->whereNull('last_status_check')
                ->orWhere('last_status_check', '<=', now()->subMinutes($minutes));
        });
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function getStatusBadgeAttribute()
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return match ($this->status) {
                'paid' => '<span class="badge badge-success">تم الدفع</span>',
                'pending' => '<span class="badge badge-warning">معلق</span>',
                'cancelled' => '<span class="badge badge-danger">ملغي</span>',
                'expired' => '<span class="badge badge-danger">منتهي الصلاحية</span>',
                default => '<span class="badge badge-secondary">غير محدد</span>'
            };
        } else {
            return match ($this->status) {
                'paid' => '<span class="badge badge-success">Paid</span>',
                'pending' => '<span class="badge badge-warning">Pending</span>',
                'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
                'expired' => '<span class="badge badge-danger">Expired</span>',
                default => '<span class="badge badge-secondary">Unknown</span>'
            };
        }
    }
}
