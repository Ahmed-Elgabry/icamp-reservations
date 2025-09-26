@extends('dashboard.layout')

@section('pageTitle', trans('dashboard.survey_statistics') . ' - ' . $survey->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('dashboard.survey_statistics') }} - {{ $survey->title }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('dashboard.question') }}</th>
                                <th>{{ trans('dashboard.type') }}</th>
                                <th>{{ trans('dashboard.total_responses') }}</th>
                                <th>{{ trans('dashboard.answers') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($survey->questions as $question)
                                <tr>
                                    <td>{{ $question->text }}</td>
                                    <td>{{ trans('dashboard.' . $question->type) }}</td>
                                    <td>{{ $responses->count() }}</td>
                                    <td>
                                        @if ($question->type === 'multiple_choice' || $question->type === 'dropdown' || $question->type === 'checkboxes')
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('dashboard.option') }}</th>
                                                        <th>{{ trans('dashboard.count') }}</th>
                                                        <th>{{ trans('dashboard.percentage') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($question->options as $option)
                                                        <tr>
                                                            <td>{{ $option->text }}</td>
                                                            <td>
                                                                @php
                                                                    $count = 0;
                                                                    foreach ($responses as $response) {
                                                                        $answer = $response->answers->where('question_id', $question->id)->first();
                                                                        if ($answer) {
                                                                            if ($question->type === 'checkboxes') {
                                                                                if ($answer->options->contains($option->id)) {
                                                                                    $count++;
                                                                                }
                                                                            } else {
                                                                                if ($answer->option_id == $option->id) {
                                                                                    $count++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                {{ $count }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $percentage = $responses->count() > 0 ? ($count / $responses->count()) * 100 : 0;
                                                                @endphp
                                                                {{ number_format($percentage, 2) }}%
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @elseif ($question->type === 'text' || $question->type === 'textarea')
                                            <ul>
                                                @foreach ($responses as $response)
                                                    @php
                                                        $answer = $response->answers->where('question_id', $question->id)->first();
                                                    @endphp
                                                    @if ($answer && !empty($answer->text))
                                                        <li>{{ $answer->text }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ trans('dashboard.no_answer') }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
