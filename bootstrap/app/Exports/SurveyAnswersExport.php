<?php

namespace App\Exports;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Helpers\SurveyHelper;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyAnswersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $response;

    public function __construct(SurveyResponse $response)
    {
        $this->response = $response;
    }

    public function collection()
    {
        return $this->response->answers;
    }

    public function headings(): array
    {
        return [
            'Question ID',
            'Question Text',
            'Question Type',
            'Answer Text'
        ];
    }

    public function map($answer): array
    {
        return [
            $answer->question_id,
            SurveyHelper::getLocalizedText($answer->question->question_text),
            $answer->question->question_type,
            $answer->answer_text ?? 'N/A'
        ];
    }
}
