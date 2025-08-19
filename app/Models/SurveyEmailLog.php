<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyEmailLog extends Model
{

    protected $fillable = [
        'order_id',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    /**
     * Get the order that the email was sent for.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
