@extends('dashboard.layout')

@section('title', trans('dashboard.survey_answers') . ' - ' . $survey->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('dashboard.survey_answers') }} - {{ $survey->title }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('dashboard.id') }}</th>
                                <th>{{ trans('dashboard.respondent') }}</th>
                                <th>{{ trans('dashboard.submitted_at') }}</th>
                                @foreach ($survey->questions as $question)
                                    <th>{{ $question->text }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($responses as $response)
                                <tr>
                                    <td>{{ $response->id }}</td>
                                    <td>{{ $response->respondent_name ?? 'N/A' }}</td>
                                    <td>{{ $response->created_at }}</td>
                                    @foreach ($survey->questions as $question)
                                        <td>
                                            @php
                                                $answer = $response->answers->where('question_id', $question->id)->first();
                                            @endphp
                                            @if ($answer)
                                                @if ($question->type === 'multiple_choice' || $question->type === 'dropdown')
                                                    {{ $answer->option->text ?? 'N/A' }}
                                                @elseif ($question->type === 'checkboxes')
                                                    {{ $answer->options->pluck('text')->join(', ') }}
                                                @else
                                                    {{ $answer->text }}
                                                @endif
                                            @else
                                                {{ trans('dashboard.no_answer') }}
                                            @endif
                                        </td>
                                    @endforeach
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
