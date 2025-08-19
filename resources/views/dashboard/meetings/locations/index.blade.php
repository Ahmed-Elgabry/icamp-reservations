@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.meeting_locations'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3>@lang('dashboard.meeting_locations')</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('meeting-locations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>@lang('dashboard.create_location')
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead class="text-gray-400 fw-bolder">
                    <tr>
                        <th>@lang('dashboard.name')</th>
                        <th>@lang('dashboard.address')</th>
                        <th>@lang('dashboard.status')</th>
                        <th>@lang('dashboard.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>{{ $location->name }}</td>
                            <td>{{ $location->address }}</td>
                            <td>
                            <span class="badge badge-light-{{ $location->is_active ? 'success' : 'danger' }}">
                                {{ $location->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                            </span>
                            </td>
                            <td>
                                {{--                            <a href="{{ route('meeting-locations.show', $location) }}" class="btn btn-sm btn-info">--}}
                                {{--                                <i class="fas fa-eye me-1"></i>@lang('dashboard.view')--}}
                                {{--                            </a>--}}
                                <a href="{{ route('meeting-locations.edit', $location) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit me-1"></i>@lang('dashboard.edit')
                                </a>
                                <form action="{{ route('meeting-locations.destroy', $location) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('@lang('dashboard.confirm_delete')')">
                                        <i class="fas fa-trash me-1"></i>@lang('dashboard.delete')
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
