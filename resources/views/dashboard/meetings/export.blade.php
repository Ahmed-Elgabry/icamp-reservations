<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@lang('dashboard.meetings') @lang('dashboard.export')</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;

            direction: {
                    {
                    app()->getLocale()==='ar' ? 'rtl': 'ltr'
                }
            }

            ;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .meeting-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .meeting-table th,
        .meeting-table td {
            border: 1px solid #ddd;
            padding: 8px;

            text-align: {
                    {
                    app()->getLocale()==='ar' ? 'right': 'left'
                }
            }

            ;
        }

        .meeting-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .attendee-list {
            font-size: 11px;
            line-height: 1.3;
        }

        .topic-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .topic-table th,
        .topic-table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 11px;

            text-align: {
                    {
                    app()->getLocale()==='ar' ? 'right': 'left'
                }
            }

            ;
        }

        .topic-table th {
            background-color: #f8f8f8;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .meeting-header {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 10px;
        }

        .discussion-content {
            font-size: 10px;
            line-height: 1.4;
            max-height: 100px;
            overflow: hidden;
        }

        .assigned-user {
            font-size: 10px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>@lang('dashboard.meetings') - {{ date('Y-m-d') }}</h1>
    </div>

    @foreach($meetings as $meeting)
    <div class="meeting mb-3">
        <div class="meeting-header">
            <h3>@lang('dashboard.meeting_number'): {{ $meeting->meeting_number }}</h3>
        </div>

        <table class="meeting-table">
            <tr>
                <th width="15%">@lang('dashboard.date')</th>
                <td width="25%">{{ $meeting->date->format('Y-m-d') }}</td>
                <th width="15%">@lang('dashboard.time')</th>
                <td width="45%">{{ $meeting->start_time }} - {{ $meeting->end_time }}</td>
            </tr>
            <tr>
                <th>@lang('dashboard.meeting_location')</th>
                <td>{{ $meeting->location->name ?? __('dashboard.not_available') }}</td>
                <th>@lang('dashboard.created_by')</th>
                <td>{{ $meeting->creator->name }}</td>
            </tr>
            @if($meeting->notes)
            <tr>
                <th>@lang('dashboard.notes')</th>
                <td colspan="3">{{ $meeting->notes }}</td>
            </tr>
            @endif
        </table>

        <!-- Attendees Section -->
        @if($meeting->attendees->count() > 0)
        <h4>@lang('dashboard.attendees') ({{ $meeting->attendees->count() }})</h4>
        <div class="attendee-list">
            @foreach($meeting->attendees as $attendee)
            <div style="margin-bottom: 3px;">
                • {{ $attendee->user->name }}
                @if($attendee->job_title)
                - {{ $attendee->job_title }}
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Topics Section -->
        @if($meeting->topics->count() > 0)
        <h4 style="margin-top: 15px;">@lang('dashboard.topics') ({{ $meeting->topics->count() }})</h4>
        <table class="topic-table">
            <thead>
                <tr>
                    <th width="25%">@lang('dashboard.topic')</th>
                    <th width="45%">@lang('dashboard.discussion')</th>
                    <th width="30%">@lang('dashboard.meeting_assigned_to')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($meeting->topics as $topic)
                <tr>
                    <td>{{ $topic->topic }}</td>
                    <td class="discussion-content">
                        {!! strip_tags($topic->discussion) !!}
                    </td>
                    <td class="assigned-user">
                        @if($topic->assigned_to)
                        {{ $topic->assignee->name ?? __('dashboard.not_available') }}
                        <!-- @if($topic->task)
                                    <br><small>(@lang('dashboard.task_created'))</small>
                                @endif -->
                        @else
                        @lang('dashboard.not_assigned')
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>@lang('dashboard.no_topics_found')</p>
        @endif
    </div>

    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach
</body>

</html>
