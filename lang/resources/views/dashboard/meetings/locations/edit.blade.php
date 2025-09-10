@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.edit_location'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">@lang('dashboard.edit_location')</span>
                <span class="text-muted mt-1 fw-semibold fs-7">{{ $meetingLocation->name }}</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('meeting-locations.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_locations')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ route('meeting-locations.update', $meetingLocation) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-5 mb-10">
                    <div class="col-md-6">
                        <label class="form-label required">@lang('dashboard.name')</label>
                        <input type="text" name="name" class="form-control form-control-solid"
                               value="{{ old('name', $meetingLocation->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">@lang('dashboard.status')</label>
                        <select name="is_active" class="form-select form-select-solid">
                            <option value="1" {{ old('is_active', $meetingLocation->is_active) ? 'selected' : '' }}>@lang('dashboard.active')</option>
                            <option value="0" {{ !old('is_active', $meetingLocation->is_active) ? 'selected' : '' }}>@lang('dashboard.inactive')</option>
                        </select>
                    </div>
                </div>

                <div class="mb-10">
                    <label class="form-label required">@lang('dashboard.address')</label>
                    <input type="text" name="address" class="form-control form-control-solid"
                           value="{{ old('address', $meetingLocation->address) }}" required>
                </div>

                <div class="mb-10">
                    <label class="form-label">@lang('dashboard.description')</label>
                    <textarea name="description" class="form-control form-control-solid" rows="3">{{ old('description', $meetingLocation->description) }}</textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>@lang('dashboard.update_location')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
