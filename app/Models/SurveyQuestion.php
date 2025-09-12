<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SurveyQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'survey_id',
        'question_text',
        'question_type',
        'options',
        'order',
        'hidden',
        'placeholder',
        'help_text',
        'error_message',
        'settings'
    ];

    protected $casts = [
        'options' => 'array',
        'settings' => 'array',
        'placeholder' => 'array',
        'help_text' => 'array',
        'question_text' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }


        public function getAnswersCountAttribute()
    {
        // If loaded via withCount(), use that (no extra query)
        if (array_key_exists('answers_count', $this->attributes)) {
            return $this->attributes['answers_count'];
        }

        // Otherwise, calculate dynamically
        return $this->answers()->count();
    }

}
