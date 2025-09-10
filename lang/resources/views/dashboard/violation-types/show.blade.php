@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.violation_type_details'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">
                    @lang('dashboard.violation_type_details')
                </span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('violation-types.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_list')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="mb-7">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.name')</div>
                        <div class="fs-5 fw-bold">{{ $violationType->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.status')</div>
                        <div class="fs-5 fw-bold">
                            <span class="badge badge-light-{{ $violationType->is_active ? 'success' : 'danger' }}">
                                {{ $violationType->is_active ? __('dashboard.active') : __('dashboard.inactive') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.description')</div>
                        <div class="card card-bordered bg-light-primary rounded">
                            <div class="card-body">
                                <p class="mb-0">{{ $violationType->description ?? __('dashboard.no_description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('violation-types.edit', $violationType) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-2"></i>@lang('dashboard.edit')
                </a>
                <form action="{{ route('violation-types.destroy', $violationType) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>@lang('dashboard.delete')
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
