@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.edit_question'))
@section('content')
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="fw-bolder mb-5">@lang('dashboard.edit_question')</h2>

        <div class="card mb-5 mb-xl-10">
            <div class="card-body">
                <form action="{{ route('questions.update', $question->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="question" class="form-label">@lang('dashboard.question')</label>
                        <input type="text" class="form-control" id="question" name="question" value="{{ $question->question }}" required>
                        @if ($errors->has('question'))
                            <span class="text-danger">{{ $errors->first('question') }}</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">@lang('dashboard.save_changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
