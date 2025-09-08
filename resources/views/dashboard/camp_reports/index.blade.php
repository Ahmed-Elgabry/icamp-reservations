@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.camp_reports'))

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
                @can('camp-reports.export')
                    <a href="{{ route('camp-reports.export') }}" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-pdf"></i> @lang('dashboard.export_pdf')
                    </a>
                @endcan
                <a href="{{ route('camp-reports.create') }}" class="btn btn-primary">
                    @lang('dashboard.create_report')
                </a>
            </div>
        </div>

        <div class="card-body pt-0 table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                <tr class="text-center text-gray-400 fw-bolder fs-6 text-uppercase gs-0" style="background-color: #f8f9fa; font-weight: 900 !important;">
                    <th class="fw-bolder">@lang('dashboard.report_date')</th>
                    <th class="fw-bolder">@lang('dashboard.service')</th>
                    <th class="fw-bolder">@lang('dashboard.camp_name')</th>
                    <th class="fw-bolder">@lang('dashboard.report_created_by')</th>
                    <th class="fw-bolder">@lang('dashboard.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->report_date->format('Y-m-d') }}</td>
                        <td>{{ $report->service->name ?? '' }}</td>
                        <td>{{ $report->camp_name ?? '' }}</td>
                        <td>{{ $report->creator->name }}</td>
                        <td>
                            <a href="{{ route('camp-reports.show', $report) }}" class="btn btn-sm btn-info">
                                @lang('dashboard.view')
                            </a>
                            <a href="{{ route('camp-reports.edit', $report) }}" class="btn btn-sm btn-primary">
                                @lang('dashboard.edit')
                            </a>
                            <form action="{{ route('camp-reports.destroy', $report) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('@lang('dashboard.confirm_delete')')">
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
@endsection
