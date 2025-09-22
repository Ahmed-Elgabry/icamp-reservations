@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.daily_reports'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <input type="text" class="form-control form-control-solid w-250px ps-14"
                           placeholder="@lang('dashboard.search_reports')" id="search-input"/>
                </div>
            </div>
            <div class="card-toolbar">
                @can('daily-reports.export')
                    <a href="{{ route('daily-reports.export') }}" class="btn btn-success me-2">
                        @lang('dashboard.export_pdf')
                    </a>
                @endcan
                @can('daily-reports.create')
                    <a href="{{ route('daily-reports.create') }}" class="btn btn-primary">
                        @lang('dashboard.create_report')
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body pt-0">
            <form method="GET" class="mb-5">
                <div class="row">
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
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-7">@lang('dashboard.filter')</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                    <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                        <th class="fw-bolder">@lang('dashboard.title')</th>
                        <th class="fw-bolder">@lang('dashboard.employee')</th>
                        <th class="fw-bolder">@lang('dashboard.created_at')</th>
                        <th class="fw-bolder">@lang('dashboard.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->employee->name }}</td>
                            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @can('daily-reports.show')
                                    <a href="{{ route('daily-reports.show', $report) }}" class="btn btn-sm btn-info">
                                        @lang('dashboard.view')
                                    </a>
                                @endcan
                                @can('daily-reports.edit')
                                    <a href="{{ route('daily-reports.edit', $report) }}" class="btn btn-sm btn-primary">
                                        @lang('dashboard.edit')
                                    </a>
                                @endcan
                                @can('daily-reports.destroy')
                                    <form action="{{ route('daily-reports.destroy', $report) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('@lang('dashboard.confirm_delete')')">
                                            @lang('dashboard.delete')
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
