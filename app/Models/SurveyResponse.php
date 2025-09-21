<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Survey;
class SurveyResponse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_id',
        'user_id',
        'reservation_id',
        'ip_address',
        'user_agent',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
    public function reservation()
    {
        return $this->belongsTo(Order::class , 'reservation_id');
    }   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }


    public function order()
    {
        return $this->belongsTo(Order::class, 'reservation_id');
    }
}
