@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.violation_types'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3>@lang('dashboard.violation_types')</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('violation-types.create') }}" class="btn btn-primary">
                    @lang('dashboard.add_new')
                </a>
            </div>
        </div>
        <div class="card-body pt-0 table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead class="text-gray-400 fw-bolder">
                <tr>
                    <th>@lang('dashboard.name')</th>
                    <th>@lang('dashboard.description')</th>
                    <th>@lang('dashboard.violation_type_status')</th>
                    <th>@lang('dashboard.created_date')</th>
                    <th>@lang('dashboard.created_time')</th>
                    <th>@lang('dashboard.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($types as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td>{{ Str::limit($type->description, 50) }}</td>
                        <td>
                            <span class="badge badge-light-{{ $type->is_active ? 'success' : 'danger' }}">
                                {{ $type->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                            </span>
                        </td>
                        <td>{{ $type->created_at->format('Y-m-d') }}</td>
                        <td>{{ $type->created_at->format('h:i A') }}</td>
                        <td>
{{--                            <a href="{{ route('violation-types.show', $type) }}" class="btn btn-sm btn-info">--}}
{{--                                @lang('dashboard.view')--}}
{{--                            </a>--}}
                            <a href="{{ route('violation-types.edit', $type) }}" class="btn btn-sm btn-warning">
                                @lang('dashboard.edit')
                            </a>
                            <form action="{{ route('violation-types.destroy', $type) }}" method="POST" class="d-inline">
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
