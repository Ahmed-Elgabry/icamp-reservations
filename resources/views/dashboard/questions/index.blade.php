@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.questions'))
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="fw-bolder mb-5">@lang('dashboard.questions')</h2>
        <div class="mb-4">
            <a href="{{ route('questions.create') }}" class="btn btn-primary">@lang('dashboard.create_question')</a>
        </div>

        <div class="card mb-5 mb-xl-10">
            <div class="card-header border-0">
                <h3 class="card-title fw-bolder">{{ __('dashboard.questions_list') }}</h3>
            </div>
            <ul class="list-group">
                @foreach ($questions as $question)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $question->question }}
                            <a href="{{ route('questions.answers', $question->id) }}" class="badge bg-info">
                                {{ $question->answers_count }} @lang('dashboard.responses_count')
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('questions.edit', $question->id) }}"
                                class="btn btn-warning btn-sm">@lang('dashboard.edit')</a>
                            <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this question?');">@lang('dashboard.delete')</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>


    </div>
</div>
</div>
@endsection