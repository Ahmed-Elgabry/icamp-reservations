<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyResponse extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_id', 'user_id', 'reservation_id', 'ip_address',
        'user_agent', 'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
