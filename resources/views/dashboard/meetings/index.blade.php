@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.meetings'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3>@lang('dashboard.meetings')</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('meetings.create') }}" class="btn btn-primary">
                    @lang('dashboard.create_meeting')
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle  table-row-dashed fs-6 gy-5">
                    <thead class="text-gray-400 fw-bolder">
                    <tr>
                        <th>@lang('dashboard.meeting_number')</th>
                        <th>@lang('dashboard.date')</th>
                        <th>@lang('dashboard.time')</th>
                        <th>@lang('dashboard.meeting_location')</th>
                        <th>@lang('dashboard.attendees_count')</th>
                        <th>@lang('dashboard.topics_count')</th>
                        <th>@lang('dashboard.meeting_created_by')</th>
                        <th>@lang('dashboard.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($meetings as $meeting)
                        <tr>
                            <td>{{ $meeting->meeting_number }}</td>
                            <td>{{ $meeting->date->format('Y-m-d') }}</td>
                            <td>{{ $meeting->start_time }} - {{ $meeting->end_time }}</td>
                            <td>{{ $meeting->location->name }}</td>
                            <td>{{ $meeting->attendees->count() }}</td>
                            <td>{{ $meeting->topics->count() }}</td>
                            <td>{{ $meeting->creator->name }}</td>
                            <td>
                                <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-sm btn-info">
                                    @lang('dashboard.view')
                                </a>
                                <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-sm btn-warning">
                                    @lang('dashboard.edit')
                                </a>
                                <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        @lang('dashboard.delete')
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
