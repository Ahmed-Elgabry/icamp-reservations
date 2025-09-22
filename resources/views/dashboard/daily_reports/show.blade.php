@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.daily_report_details'))

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-header">
            <h3 class="card-title">@lang('dashboard.daily_report_details')</h3>
        </div>

        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-6">
                    <p><strong>@lang('dashboard.title'):</strong> {{ $dailyReport->title }}</p>
                    <p><strong>@lang('dashboard.details'):</strong> {{ $dailyReport->details }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>@lang('dashboard.employee'):</strong> {{ $dailyReport->employee->name }}</p>
                    <p><strong>@lang('dashboard.created_at'):</strong> {{ $dailyReport->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>

            @if($dailyReport->notes)
                <div class="row mb-5">
                    <div class="col-md-12">
                        <p><strong>@lang('dashboard.notes'):</strong></p>
                        <p>{{ $dailyReport->notes }}</p>
                    </div>
                </div>
            @endif

            @if($dailyReport->photo_attachment)
                <div class="row mb-5">
                    <div class="col-md-12">
                        <p><strong>@lang('dashboard.photo_attachment'):</strong></p>
                        <img src="{{ Storage::url($dailyReport->photo_attachment) }}" class="img-fluid" style="max-width: 300px;">
                    </div>
                </div>
            @endif

            @if($dailyReport->audio_attachment)
                <div class="row mb-5">
                    <div class="col-md-12">
                        <p><strong>@lang('dashboard.audio_attachment'):</strong></p>
                        <audio controls>
                            <source src="{{ Storage::url($dailyReport->audio_attachment) }}">
                        </audio>
                    </div>
                </div>
            @endif

            @if($dailyReport->video_attachment)
                <div class="row mb-5">
                    <div class="col-md-12">
                        <p><strong>@lang('dashboard.video_attachment'):</strong></p>
                        <video width="320" height="240" controls>
                            <source src="{{ Storage::url($dailyReport->video_attachment) }}">
                        </video>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
