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
            width: 100%;
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
        .question-section {
            margin-bottom: 30px;
        }
        .question-title {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
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

    <h2>{{ __('dashboard.question_options_breakdown') }}</h2>

    @foreach($questionsWithOptions as $question)
        <div class="question-section">
            <div class="question-title">
                {{ $question['title'] }}
                <span style="font-size: 0.8em; color: #666;">({{ $question['type'] }})</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>{{ __('dashboard.option') }}</th>
                        <th>{{ __('dashboard.count') }}</th>
                        <th>{{ __('dashboard.percentage') }}</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    // Initialize option counts
                    $optionCounts = [];
                    $totalAnswers = 0;

                    // Initialize counts for each option
                    foreach($question['options'] as $option) {
                        if (is_array($option)) {
                            $key = $option['value'] ?? $option['label'] ?? '';
                            $label = $option['label'] ?? $option['value'] ?? '';
                        } else {
                            $key = $option;
                            $label = $option;
                        }
                        $optionCounts[$key] = [
                            'label' => $label,
                            'count' => 0
                        ];
                    }

                    // Count the answers for this question
                    foreach($responses as $response) {
                        $answer = $response->answers->firstWhere('survey_question_id', $question['id']);
                        if ($answer) {
                            if ($question['type'] == 'checkbox') {
                                // For checkbox, answer_option is an array or JSON string of selected options
                                $selectedOptions = is_array($answer->answer_option) ? $answer->answer_option : json_decode($answer->answer_option, true);
                                if (is_array($selectedOptions)) {
                                    foreach($selectedOptions as $selectedOption) {
                                        if (isset($optionCounts[$selectedOption])) {
                                            $optionCounts[$selectedOption]['count']++;
                                            $totalAnswers++;
                                        }
                                    }
                                }
                            } elseif ($question['type'] == 'stars') {
                                // For stars type, handle different answer formats
                                $value = null;

                                // Check answer_text first
                                if (!empty($answer->answer_text)) {
                                    $value = $answer->answer_text;
                                }
                                // If empty, check answer_option
                                elseif (!empty($answer->answer_option)) {
                                    if (is_array($answer->answer_option)) {
                                        $value = $answer->answer_option[0] ?? null;
                                    } else {
                                        $value = $answer->answer_option;
                                    }
                                }

                                // Convert value to stars
                                if ($value !== null) {
                                    $starCount = (int)$value;
                                    if ($starCount > 0) {
                                        $stars = str_repeat('★', $starCount);
                                        if (isset($optionCounts[$stars])) {
                                            $optionCounts[$stars]['count']++;
                                            $totalAnswers++;
                                        }
                                    }
                                }
                            } elseif ($question['type'] == 'rating') {
                                // For rating type, handle numeric values
                                $value = null;

                                // Check answer_text first
                                if (!empty($answer->answer_text)) {
                                    $value = $answer->answer_text;
                                }
                                // If empty, check answer_option
                                elseif (!empty($answer->answer_option)) {
                                    if (is_array($answer->answer_option)) {
                                        $value = $answer->answer_option[0] ?? null;
                                    } else {
                                        $value = $answer->answer_option;
                                    }
                                }

                                // Convert value to numeric rating
                                if ($value !== null) {
                                    $rating = (int)$value;
                                    if ($rating > 0) {
                                        if (isset($optionCounts[(string)$rating])) {
                                            $optionCounts[(string)$rating]['count']++;
                                            $totalAnswers++;
                                        }
                                    }
                                }
                            } elseif ($question['type'] == 'select' || $question['type'] == 'radio') {
                                // For select and radio types
                                $value = null;

                                // Check answer_text first
                                if (!empty($answer->answer_text)) {
                                    $value = $answer->answer_text;
                                }
                                // If empty, check answer_option
                                elseif (!empty($answer->answer_option)) {
                                    if (is_array($answer->answer_option)) {
                                        $value = $answer->answer_option['value'] ?? $answer->answer_option[0] ?? null;
                                    } else {
                                        $value = $answer->answer_option;
                                    }
                                }

                                // Try to match the value with option values or labels
                                if ($value !== null) {
                                    $matched = false;

                                    // 1. First try to match with option values directly
                                    if (isset($optionCounts[$value])) {
                                        $optionCounts[$value]['count']++;
                                        $totalAnswers++;
                                        $matched = true;
                                    }

                                    // 2. Try to match with option labels
                                    if (!$matched) {
                                        foreach ($optionCounts as $key => $option) {
                                            if ($key == $value) {
                                                $optionCounts[$key]['count']++;
                                                $totalAnswers++;
                                                $matched = true;
                                                break;
                                            }
                                        }
                                    }

                                    // 3. If still not matched, try to decode JSON
                                    if (!$matched) {
                                        $decodedValue = json_decode($value, true);
                                        if (is_array($decodedValue)) {
                                            $valueToCheck = $decodedValue['value'] ?? $decodedValue[0] ?? $value;
                                            if (isset($optionCounts[$valueToCheck])) {
                                                $optionCounts[$valueToCheck]['count']++;
                                                $totalAnswers++;
                                                $matched = true;
                                            }
                                        }
                                    }

                                    // 4. Extra: map "option1", "option2", ... to actual labels
                                    if (!$matched && preg_match('/option(\d+)/i', $value, $matches)) {
                                        $optionNumber = (int)$matches[1];
                                        $optionIndex = $optionNumber - 1; // zero-based index
                                        if ($optionIndex >= 0 && $optionIndex < count($question['options'])) {
                                            $mappedLabel = $question['options'][$optionIndex];
                                            foreach ($optionCounts as $key => $opt) {
                                                if ($opt['label'] == $mappedLabel) {
                                                    $optionCounts[$key]['count']++;
                                                    $totalAnswers++;
                                                    $matched = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                @endphp

                    @foreach($optionCounts as $option)
                        <tr>
                            <td>{{ $option['label'] }}</td>
                            <td>{{ $option['count'] }}</td>
                            <td>
                                @if($totalAnswers > 0)
                                    {{ round(($option['count'] / $totalAnswers) * 100, 1) }}%
                                @else
                                    0%
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <p>{{ __('dashboard.generated_by_icamp_system') }}</p>
    </div>
</body>
</html>
