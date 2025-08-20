<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>icamp - {{ __('dashboard.rate_order') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="canonical" href="{{ Request::fullUrl() }}" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Cairo", sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .rating-form-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .order-details {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .order-details h2 {
            margin-top: 0;
            color: #333;
        }

        .order-details p {
            margin: 5px 0;
            color: #666;
        }

        .rating-form-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            font-size: 24px;
        }

        .rating-form-body {
            padding: 20px;
        }

        .rating-form-footer {
            text-align: center;
            padding: 10px;
        }

        .rating-form-group {
            margin-bottom: 15px;
        }

        .rating-form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .rating-form-input,
        .rating-form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .rating-form-textarea {
            height: 100px;
            resize: vertical;
        }

        .rating-form-submit {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .rating-form-submit:hover {
            background-color: #218838;
        }

        .rating-form-stars {
            display: flex;
            justify-content: space-between;
            max-width: 200px;
            margin: 0 auto 20px;
        }

        .rating-form-stars input {
            display: none;
        }

        .rating-form-stars label {
            font-size: 30px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .rating-form-stars input:checked ~ label,
        .rating-form-stars input:hover ~ label,
        .rating-form-stars label:hover ~ label , .checked {
            color: #f5c518  !important
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #fff;
        }

        .alert-success {
            background-color: #28a745;
        }

        .alert-error {
            background-color: #dc3545;
        }
        .rating-scale-ar input[type="radio"],
        .rating-scale-ar input[type="checkbox"] {
            float: right;
            margin-left: 10px;
        }
    </style>

    @if (app()->getLocale() == 'ar')
        <style>
            .rating-form-stars {
                direction: ltr;
            }
        </style>
    @else
        <style>
            .rating-form-stars {
                direction: rtl;
            }
        </style>
    @endif
</head>
<body style="direction: @if (app()->getLocale() == 'ar') rtl @else ltr @endif" >
<div class="rating-form-container">
    <!-- Display return messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        @foreach($errors->all() as $e)
        <div class="alert alert-error">
            {{ $e }}
        </div>
        @endforeach
    @endif

    {{-- Switch lang to ar and en  --}}
    <div>
        @if (app()->getLocale() == 'ar')
            <a href="{{ route('set-lang', 'en') }}" >English</a>
        @else
            <a href="{{route('set-lang','ar')}}">العربية</a>
        @endif
    </div>


    <div class="order-details">
        <h2>{{ __('dashboard.order_details') }}</h2>
        <p>{{ __('dashboard.order_id') }}: 12345</p>
        <p>{{ __('dashboard.order_date_rev') }}: 2023-06-15</p>
        <p>{{ __('dashboard.customer') }}: أحمد محمد</p>
    </div>

    <div class="rating-form-header">{{ __('dashboard.rate_order') }}</div>
    <div class="rating-form-body">
        <form action="#" method="POST">
            @csrf
            <div>
                @foreach($survey['fields'] as $question)
                    <div class="mb-4 {{ $question['settings']['width'] ?? 'col-12' }}">
                        {!! SurveyHelper::generatePreviewHtml($question) !!}
                    </div>
                @endforeach
        </div>

            <div class="rating-form-footer">
                <button type="submit" class="rating-form-submit">{{ __('dashboard.send_review') }} ⭐</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>


{{-- resources/views/surveys/show.blade.php --}}
{{-- @extends('layouts.app')

@section('title', $survey->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="mb-0">{{ $survey->title }}</h2>
                    @if($survey->description)
                        <p class="text-muted mb-0">{{ $survey->description }}</p>
                    @endif
                </div>
                <div class="card-body">
                    <form id="surveyForm" method="POST" action="{{ route('surveys.submit', $survey) }}">
                        @csrf
                        @foreach($survey->questions as $question)
                            <div class="mb-4 {{ $question->settings['width'] ?? 'col-12' }}">
                                {!! SurveyHelper::generateQuestionHtml($question) !!}
                            </div>
                        @endforeach
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">إرسال الاستبيان</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
