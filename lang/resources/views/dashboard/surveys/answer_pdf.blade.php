<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.survey_answers') }}</title>
    <style>
        body {
            font-family: "Cairo", sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .order-details {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .order-details h2 {
            margin: 0 0 10px 0;
            color: #555;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-details table, .order-details th, .order-details td {
            border: none;
        }
        .order-details th, .order-details td {
            padding: 5px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        .order-details th {
            font-weight: bold;
            color: #555;
        }
        .answers-container {
            margin-bottom: 20px;
        }
        .answers-container h2 {
            margin: 0 0 10px 0;
            color: #555;
        }
        .answer-item {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .answer-item .question {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .answer-item .answer {
            color: #555;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('dashboard.survey_answers') }}</h1>
        <p>{{ __('dashboard.generated_on') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="order-details">
        <h2>{{ __('dashboard.order_details') }}</h2>
        <table>
            <tr>
                <th>{{ __('dashboard.order_number') }}:</th>
                <td>{{ $response->order? $response->order->id : __('dashboard.not_available') }}</td>
            </tr>
            <tr>
                <th>{{ __('dashboard.customer_name') }}:</th>
                <td>{{ $response->order? ($response->order->customer ? $response->order->customer->name : __('dashboard.not_available')) : __('dashboard.not_available') }}</td>
            </tr>
            <tr>
                <th>{{ __('dashboard.answer_date') }}:</th>
                <td>{{ $response->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <div class="answers-container">
        <h2>{{ __('dashboard.survey_answers') }}</h2>
        @foreach ($response->answers as $answer)
            <div class="answer-item">
                <div class="question">{{ SurveyHelper::getLocalizedText($answer->question->question_text) }}</div>
                <div class="answer">{!! SurveyHelper::getResponseHtml($answer) !!}</div>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>{{ __('dashboard.generated_by_icamp_system') }}</p>
    </div>
</body>
</html>