<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyResultsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    public function collection()
    {
        return $this->survey->responses()->with(['answers.question', 'order.customer'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Order Number',
            'Customer Name',
            'Answer Date',
            'Answers Count'
        ];
    }

    public function map($response): array
    {
        return [
            $response->id,
            $response->reservation_id,
            $response->order ? ($response->order->customer ? $response->order->customer->name : 'N/A') : 'N/A',
            $response->created_at->format('Y-m-d H:i:s'),
            $response->answers->whereNotNull('answer_text')->count()
        ];
    }
}
