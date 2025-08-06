<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم الطلب</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
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
    </style>
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
        <p>{{ __('dashboard.order_id') }}: {{ $order->id }}</p>
        <p>{{ __('dashboard.order_date_rev') }}: {{ $order->date }}</p>
        <p>{{ __('dashboard.customer') }}: {{ $order->customer->name }}</p>
    </div>

    <div class="rating-form-header">{{ __('dashboard.rate_order') }}</div>
    <div class="rating-form-body">
        <form action="{{ route('rate.save') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="rating-form-group">
                <label class="rating-form-label">{{ __('dashboard.Rate') }}</label>
                <div class="rating-form-stars">
                    @for($i = 5; $i >= 1;$i--)
                    <input type="radio"
                        id="star{{$i}}" name="rating" value="{{$i}}"><label  @if($order->rate)
                                             {{$order->rate->rating >= $i ? "class=checked" : ''}}
                                        @endif  for="star{{$i}}">★</label>
                    @endfor
                </div>
            </div>
            <div class="rating-form-group">
                <label for="review" class="rating-form-label">{{ __('dashboard.review') }}</label>
                <textarea id="review" name="review" class="rating-form-textarea" placeholder={{ __('dashboard.review_placeholder') }}> {{$order->rate ? $order->rate->review : ''}}</textarea>
            </div>
            <div>
            @foreach ($questions as $question)
                <div style="margin-bottom: 20px; display: flex; align-items: center;">
                    <label style="font-family: cairo, sans-serif; font-size: 25px; margin-right: 15px; margin-bottom: 0;">{{ $question->question }}</label>
                    <div style="margin-left: 10px;">
                        <input type="radio" name="questions[{{ $question->id }}]" value="1" id="yes-{{ $question->id }}">
                        <label for="yes-{{ $question->id }}" style="font-family: cairo, sans-serif; font-size: 20px; margin-right: 10px;">{{ __('dashboard.yes') }}</label>
                        <input type="radio" name="questions[{{ $question->id }}]" value="0" id="no-{{ $question->id }}">
                        <label for="no-{{ $question->id }}" style="font-family: cairo, sans-serif; font-size: 20px;">{{ __('dashboard.no') }}</label>
                    </div>
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
