@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.location_details'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.location_details')</span>
                <span class="text-muted mt-1 fw-semibold fs-7">{{ $meetingLocation->name }}</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('meeting-locations.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_locations')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="d-flex flex-column flex-md-row gap-5 mb-7">
                <div class="d-flex flex-column">
                    <div class="text-muted fs-7 mb-1">@lang('dashboard.status')</div>
                    <div class="fs-5 fw-bold">
                        <span class="badge badge-light-{{ $meetingLocation->is_active ? 'success' : 'danger' }}">
                            {{ $meetingLocation->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                        </span>
                    </div>
                </div>

                <div class="d-flex flex-column">
                    <div class="text-muted fs-7 mb-1">@lang('dashboard.address')</div>
                    <div class="fs-5 fw-bold">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        {{ $meetingLocation->address }}
                    </div>
                </div>

                <div class="d-flex flex-column">
                    <div class="text-muted fs-7 mb-1">@lang('dashboard.created_at')</div>
                    <div class="fs-5 fw-bold">
                        <i class="fas fa-calendar-day text-primary me-2"></i>
                        {{ $meetingLocation->created_at->format('F j, Y') }}
                    </div>
                </div>
            </div>

            <div class="mb-7">
                <div class="text-muted fs-7 mb-2">@lang('dashboard.description')</div>
                <div class="card card-bordered bg-light-primary rounded">
                    <div class="card-body">
                        <p class="mb-0">{{ $meetingLocation->description ?? __('dashboard.no_description') }}</p>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('meeting-locations.edit', $meetingLocation) }}" class="btn btn-warning me-3">
                    <i class="fas fa-edit me-2"></i>@lang('dashboard.edit')
                </a>
                <form action="{{ route('meeting-locations.destroy', $meetingLocation) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('@lang('dashboard.confirm_delete')')">
                        <i class="fas fa-trash me-2"></i>@lang('dashboard.delete')
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
