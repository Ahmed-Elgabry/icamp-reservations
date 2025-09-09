<?php

namespace App\Exports;

use App\Models\Survey;
use App\Helpers\SurveyHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyStatisticsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function collection()
    {
        $allQuestions = $this->survey->questions()
            ->withCount(['answers' => function($query) {
                $query->whereNotNull('answer_text');
            }])
            ->orderBy('answers_count', 'desc')
            ->get();

        return $allQuestions;
    }

    public function headings(): array
    {
        return [
            'Question ID',
            'Question Text',
            'Question Type',
            'Answers Count'
        ];
    }

    public function map($question): array
    {
        return [
            $question->id,
            SurveyHelper::getLocalizedText($question->question_text),
            $question->question_type,
            $question->answers_count
        ];
    }
}
