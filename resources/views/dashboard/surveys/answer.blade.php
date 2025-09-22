@section('pageTitle', __('dashboard.view_answers'))
@extends('dashboard.layouts.app')
@section('content')
<!--begin::Post-->
<div class="post d-flex flex-column-fluid" id="kt_post">
    <!--begin::Container-->
    <div id="kt_content_container" class="container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="fw-bold">@lang('dashboard.survey_answers')</h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <a href="{{ route('surveys.results',1) }}" class="btn btn-light-primary">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                        @lang('dashboard.back_to_list')
                    </a>
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Order Details-->
                <div class="mb-10">
                    <div class="card card-flush border-0">
                        <div class="card-header border-0 pt-6">
                            <div class="card-title">
                                <h3 class="fw-bold">@lang('dashboard.order_details')</h3>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-row-dashed align-middle gs-0 gy-4">
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                        <tr>
                                            <td class="text-gray-800">@lang('dashboard.order_number'):</td>
                                            <td class="fw-bolder text-gray-800">{{ $response->order? $response->order->id : __('dashboard.not_available') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-800">@lang('dashboard.customer_name'):</td>
                                            <td class="fw-bolder text-gray-800">{{ $response->order? ($response->order->customer ? $response->order->customer->name : __('dashboard.not_available')) : __('dashboard.not_available') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-800">@lang('dashboard.answer_date'):</td>
                                            <td class="fw-bolder text-gray-800">{{ $response->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Order Details-->
                <!--begin::Survey Form-->
                <div class="card card-flush">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h3 class="fw-bold">@lang('dashboard.survey_answers')</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10">
                            <div class="rating-form-container">
                                <div class="rating-form-body">
                                    <!-- Display rating -->
                                    {{-- <div class="rating-form-group">
                                        <label class="rating-form-label">التقييم</label>
                                        <div class="rating-form-stars">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio"
                                                    id="star{{$i}}" name="rating" value="{{$i}}" disabled @if($response->rating == $i) checked @endif>
                                                <label for="star{{$i}}" @if($response->rating >= $i) class="checked" @endif>★</label>
                                            @endfor
                                        </div>
                                    </div>
                                    <!-- Display review -->
                                    <div class="rating-form-group">
                                        <label class="rating-form-label">مراجعة العميل</label>
                                        <textarea class="rating-form-textarea" disabled>{{ $response->review }}</textarea>
                                    </div> --}}
                                    <!-- Display survey answers -->
                                    @foreach($response->answers as $answer)
                                        <div class="mb-4">
                                            <div class="fw-bold mb-2">{{ SurveyHelper::getLocalizedText($answer->question->question_text) }}</div>
                                            {!! SurveyHelper::getResponseHtml($answer) !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Survey Form-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Category-->
    </div>
    <!--end::Container-->
</div>
<!--end::Post-->
<style>
    /* Responsive container */
    .rating-form-container {
        max-width: 800px;
        width: 100%;
        margin: 40px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .rating-form-container {
            margin: 20px auto;
            padding: 15px;
        }
    }

    /* Form group styling */
    .rating-form-group {
        margin-bottom: 20px;
    }

    .rating-form-label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        font-size: 16px;
        color: #333;
    }

    /* Input styling */
    .rating-form-input,
    .rating-form-textarea,
    .form-control,
    .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    .rating-form-input:focus,
    .rating-form-textarea:focus,
    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .rating-form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    /* Star rating styling */
    .rating-form-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 5px;
        margin: 15px 0;
    }

    .rating-form-stars input {
        display: none;
    }

    .rating-form-stars label {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-form-stars input:checked ~ label,
    .rating-form-stars label.checked {
        color: #ffc107;
    }

    /* RTL support */
    [dir="rtl"] .rating-form-group {
        text-align: right;
    }

    [dir="rtl"] .rating-form-input,
    [dir="rtl"] .rating-form-textarea,
    [dir="rtl"] .form-control,
    [dir="rtl"] .form-select {
        direction: rtl;
        text-align: right;
    }

    .rating-scale-ar input[type="radio"],
    .rating-scale-ar input[type="checkbox"] {
        margin-left: 10px;
        margin-right: 0;
    }

    /* Checkbox and radio styling */
    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .form-check-input {
        margin-top: 0.25rem;
    }

    .form-check-label {
        margin-bottom: 0;
    }

    /* Rating scale styling */
    .rating-scale {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
    }

    .rating-scale .form-check-inline {
        display: flex;
        align-items: center;
        margin-right: 0;
        margin-left: 0;
    }

    .rating-scale input[type="radio"],
    .rating-scale input[type="checkbox"] {
        margin-left: 0;
        margin-right: 10px;
    }
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .rating-form-stars label {
            font-size: 24px;
        }

        .rating-scale {
            gap: 10px;
        }
    }
</style>
@endsection
