@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.create_violation_type'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">
                    @lang('dashboard.create_violation_type')
                </span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('violation-types.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_list')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ route('violation-types.store') }}" method="POST">
                @csrf

                <div class="mb-10">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <label class="form-label required">@lang('dashboard.name')</label>
                            <input type="text" name="name" class="form-control form-control-solid" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('dashboard.violation_type_status')</label>
                            <select name="is_active" class="form-select form-select-solid">
                                <option value="1" selected>@lang('dashboard.active')</option>
                                <option value="0">@lang('dashboard.inactive')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">@lang('dashboard.description')</label>
                            <textarea name="description" class="form-control form-control-solid" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        @lang('dashboard.save')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
