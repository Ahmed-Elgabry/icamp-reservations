@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.answers'))
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="fw-bolder mb-5">@lang('dashboard.answers')</h2>

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <h3 class="card-title fw-bolder">{{ __('dashboard.answers_list') }}</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('dashboard.custmer_name')</th>
                        <th>@lang('dashboard.answers')</th>
                        <th>@lang('dashboard.date')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($question->answers as $answer)
                        <tr>
                            <td>
                                <a href="{{ route('answers.user', $answer->user_id) }}">
                                    {{ $answer->user->name ?? 'مجهول' }}
                                </a>
                            </td>
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