@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.meeting_details'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.meeting_details')</span>
                <span class="text-muted mt-1 fw-semibold fs-7">Meeting #{{ $meeting->meeting_number }}</span>
            </h3>
            <div class="card-toolbar">
                @can('meetings.export')
                    <a href="{{ route('meetings.export.single', $meeting) }}" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-pdf"></i> @lang('dashboard.export_pdf')
                    </a>
                @endcan
                <a href="{{ route('meetings.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_meetings')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <!-- Meeting Header Info -->
            <div class="d-flex flex-column flex-md-row gap-5 mb-7">
                <div class="d-flex flex-column">
                    <div class="text-muted fs-7 mb-1">@lang('dashboard.date')</div>
                    <div class="fs-5 fw-bold">
                        <i class="fas fa-calendar-day text-primary me-2"></i>
                        {{ $meeting->date->format('F j, Y') }}
                    </div>
                </div>

                <div class="d-flex flex-column">
                    <div class="text-muted fs-7 mb-1">@lang('dashboard.time')</div>
                    <div class="fs-5 fw-bold">
                        <i class="fas fa-clock text-primary me-2"></i>
                        {{ $meeting->start_time }} - {{ $meeting->end_time }}
                    </div>
                </div>

                <div class="d-flex flex-column">
                    <div class="text-muted fs-7 mb-1">@lang('dashboard.meeting_location')</div>
                    <div class="fs-5 fw-bold">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        {{ $meeting->location->name }} ({{ $meeting->location->address }})
                    </div>
                </div>
            </div>

            <!-- Meeting Notes -->
            <div class="mb-7">
                <div class="text-muted fs-7 mb-2">@lang('dashboard.notes')</div>
                <div class="card card-bordered bg-light-primary rounded">
                    <div class="card-body">
                        <p class="mb-0">{{ $meeting->notes }}</p>
                    </div>
                </div>
            </div>

            <!-- Attendees Section -->
            <div class="mb-7">
                <h4 class="text-gray-800 mb-4">
                    <i class="fas fa-users text-primary me-2"></i>
                    @lang('dashboard.attendees')
                </h4>
                <div class="d-flex flex-wrap gap-4">
                    @foreach($meeting->attendees as $attendee)
                        <div class="symbol symbol-50px symbol-circle">
                            <div class="symbol-label bg-light-primary text-primary fw-bold">
                                {{ substr($attendee->user->name, 0, 1) }}
                            </div>
{{--                            <div class="symbol-badge bg-success start-100 top-100"></div>--}}
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">{{ $attendee->user->name }}</span>
                            <span class="text-muted">{{ $attendee->job_title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Topics Section -->
            <div class="mb-4">
                <h4 class="text-gray-800 mb-4">
                    <i class="fas fa-list-ul text-primary me-2"></i>
                    @lang('dashboard.topics')
                </h4>

                @foreach($meeting->topics as $topic)
                    <div class="card card-bordered mb-5">
                        <div class="card-header bg-light">
                            <div class="card-title">
                                <h5 class="fw-bold text-gray-800">{{ $topic->topic }}</h5>
                            </div>
                            @if($topic->assigned_to)
                                <div class="card-toolbar">
                                    @if($topic->task)
                                        <a href="{{ route('tasks.index') }}"
                                           class="btn ms-4 btn-sm btn-info">
                                            <i class="fas fa-tasks me-2"></i>
                                            @lang('dashboard.view_task')
                                        </a>
                                    @endif
                                    <span class="badge badge-light-info">
                                        <i class="fas fa-user-tie me-2"></i>
                                        @lang('dashboard.meeting_assigned_to'): {{ $topic->assignee->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">@lang('dashboard.discussion')</h6>
                                <div class="fs-6" dir="ltr">
                                    {!! $topic->discussion !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .symbol {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .symbol-badge {
            position: absolute;
            border-radius: 100%;
            border: 2px solid #fff;
            width: 12px;
            height: 12px;
        }
        .card-bordered {
            border: 1px solid #E4E6EF;
            border-radius: 0.475rem;
        }
        .bg-light-primary {
            background-color: #F1FAFF;
        }
    </style>
@endpush
