@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.user_answers'))
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="fw-bolder mb-5">@lang('dashboard.user_answers')</h2>

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <h3 class="card-title fw-bolder">{{ __('dashboard.user_answers_list') }}</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>السؤال</th>
                        <th>الإجابة</th>
                        <th>وقت الإجابة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userAnswers as $answer)
                        <tr>
                            <td>{{ $answer->question->question }}</td>
                            <td>{{ $answer->response == 1 ? 'نعم' : 'لا' }}</td>
                            <td>{{ $answer->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection