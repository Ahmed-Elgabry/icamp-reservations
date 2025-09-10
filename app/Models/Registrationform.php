<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewRegistrationformsNotification;

class Registrationform extends Model
{
    protected $table = 'registrationforms';
    protected $fillable = [
        'service_id',
        'booking_date',
        'camp_type',
        'time_slot',
        'checkin_time',
        'checkout_time',
        'terms_accepted',
        'persons',
        'first_name',
        'last_name',
        'mobile_phone',
        'email',
        'notes',
    ];
    protected $casts = [
        'booking_date'   => 'date',
        'checkin_time'   => 'datetime:H:i',
        'checkout_time'  => 'datetime:H:i',
        'terms_accepted' => 'boolean',
    ];

    public function service(){ return $this->belongsTo(Service::class , 'service_id'); }
    public function getFullNameAttribute() { return $this->first_name . ' ' . $this->last_name; }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->request_code = 'REQ' . strtoupper(\Str::random(8));
            Notification::send(User::whereUser_type(1)->get(), new NewRegistrationformsNotification($model));
        });
    }
}
