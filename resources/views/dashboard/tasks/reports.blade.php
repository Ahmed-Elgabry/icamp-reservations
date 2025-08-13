@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.task_reports'))

@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3>@lang('dashboard.task_reports')</h3>
                    </div>
{{--                    <div class="card-toolbar">--}}
{{--                        <a href="{{ route('tasks.exportReports') }}?{{ http_build_query(request()->all()) }}"--}}
{{--                           class="btn btn-primary">--}}
{{--                            @lang('dashboard.export_to_excel')--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </div>

                <div class="card-body pt-0">
                    <!-- Filters -->
                    <form method="GET" class="mb-10">
                        <div class="row">
                            <div class="col-md-3">
                                <label>@lang('dashboard.status')</label>
                                <select name="status" class="form-select">
                                    <option value="">@lang('dashboard.all')</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>@lang('dashboard.pending')</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>@lang('dashboard.in_progress')</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>@lang('dashboard.completed')</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>@lang('dashboard.failed')</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>@lang('dashboard.employee')</label>
                                <select name="employee_id" class="form-select">
                                    <option value="">@lang('dashboard.all')</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>@lang('dashboard.date_from')</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label>@lang('dashboard.date_to')</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">@lang('dashboard.filter')</button>
                                <a href="{{ route('tasks.reports') }}" class="btn btn-light">@lang('dashboard.reset')</a>
                            </div>
                        </div>
                    </form>

                    <!-- Stats -->
                    <div class="row mb-10">
                        <div class="col-md-3">
                            <div class="card bg-light-success">
                                <div class="card-body">
                                    <h5>@lang('dashboard.total_tasks')</h5>
                                    <h2>{{ $completionStats['total'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-primary">
                                <div class="card-body">
                                    <h5>@lang('dashboard.completed')</h5>
                                    <h2>{{ $completionStats['completed'] }}</h2>
{{--                                    <small>{{ $completionStats['total'] > 0 ? round(($completionStats['completed']/$completionStats['total'])*100) : 0 }}%</small>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-warning">
                                <div class="card-body">
                                    <h5>@lang('dashboard.pending')</h5>
                                    <h2>{{ $completionStats['pending'] }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-danger">
                                <div class="card-body">
                                    <h5>@lang('dashboard.failed')</h5>
                                    <h2>{{ $completionStats['failed'] }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Table -->
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>@lang('dashboard.title')</th>
                            <th>@lang('dashboard.assigned_to')</th>
                            <th>@lang('dashboard.due_date')</th>
                            <th>@lang('dashboard.status')</th>
                            <th>@lang('dashboard.completion_date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->assignedUser->name }}</td>
                                <td>{{ $task->due_date->format('Y-m-d') }}</td>
                                <td>
                                <span class="badge badge-light-primary">
                                    {{ $task->status }}
                                </span>
                                </td>
                                <td>{{ $task->status == 'completed' ? $task->updated_at->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
