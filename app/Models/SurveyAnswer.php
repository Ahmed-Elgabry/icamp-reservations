<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyAnswer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_response_id', 'survey_question_id', 'answer_text', 'answer_option'
    ];

    protected $casts = [
        'answer_option' => 'array'
    ];

    public function response()
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}
