<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyEmailLog extends Model
{
    use SoftDeletes;

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
