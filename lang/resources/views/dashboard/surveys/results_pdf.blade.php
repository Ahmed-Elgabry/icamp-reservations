<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.survey_results') }}</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
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
        <h1>{{ __('dashboard.survey_results') }}</h1>
        <p>{{ __('dashboard.generated_on') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>{{ __('dashboard.total_responses') }}: {{ $responses->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('dashboard.serial_number') }}</th>
                <th>{{ __('dashboard.order_number') }}</th>
                <th>{{ __('dashboard.customer_name') }}</th>
                <th>{{ __('dashboard.answer_date') }}</th>
                <th>{{ __('dashboard.answers_count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($responses as $index => $response)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $response->reservation_id }}</td>
                    <td>{{ $response->order ? ($response->order->customer ? $response->order->customer->name : __('dashboard.not_available')) : __('dashboard.not_available') }}</td>
                    <td>{{ $response->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $response->answers->whereNotNull('answer_text')->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ __('dashboard.generated_by_icamp_system') }}</p>
    </div>
</body>
</html>