<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.survey_statistics') }}</title>
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
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .stat-box {
            display: flex;
            width: 48%;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0 0 10px 0;
            color: #555;
        }
        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
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
        <h1>{{ __('dashboard.survey_statistics') }}</h1>
        <p>{{ __('dashboard.generated_on') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="stats-container">
        <div class="stat-box">
            <h3>{{ __('dashboard.total_surveys') }}</h3>
            <div class="number">1</div>
        </div>
        <div class="stat-box">
            <h3>{{ __('dashboard.total_answers') }}</h3>
            <div class="number">{{ $totalResponses }}</div>
        </div>
    </div>

    <h2>{{ __('dashboard.all_questions') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('dashboard.question_id') }}</th>
                <th>{{ __('dashboard.question_text') }}</th>
                <th>{{ __('dashboard.question_type') }}</th>
                <th>{{ __('dashboard.answers_count') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allQuestionsData as $question)
                <tr>
                    <td>{{ $question['id'] }}</td>
                    <td>{{ $question['title'] }}</td>
                    <td>{{ $question['type'] }}</td>
                    <td>{{ $question['answers_count'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>{{ __('dashboard.question_types') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('dashboard.question_type') }}</th>
                <th>{{ __('dashboard.count') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ __('dashboard.short_text') }}</td>
                <td>{{ $questionTypes['text'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>{{ __('dashboard.long_text') }}</td>
                <td>{{ $questionTypes['textarea'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>{{ __('dashboard.single_choice') }}</td>
                <td>{{ $questionTypes['radio'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>{{ __('dashboard.multiple_choice') }}</td>
                <td>{{ $questionTypes['checkbox'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>{{ __('dashboard.dropdown_list') }}</td>
                <td>{{ $questionTypes['select'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>{{ __('dashboard.star_rating') }}</td>
                <td>{{ $questionTypes['stars'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>{{ __('dashboard.rating_scale') }}</td>
                <td>{{ $questionTypes['rating'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>{{ __('dashboard.generated_by_icamp_system') }}</p>
    </div>
</body>
</html>
