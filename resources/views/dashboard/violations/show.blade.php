@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.violation_details'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">
                    @lang('dashboard.violation_details')
                </span>
                <span class="text-muted mt-1 fw-semibold fs-7">#{{ $violation->id }}</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('violations.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_list')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="mb-7">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.employee')</div>
                        <div class="fs-5 fw-bold">{{ $violation->employee->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.violation_type')</div>
                        <div class="fs-5 fw-bold">{{ $violation->type->name }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.date')</div>
                        <div class="fs-5 fw-bold">{{ $violation->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.action_taken')</div>
                        <div class="fs-5 fw-bold">
                            <span class="badge badge-light-{{
                                $violation->action_taken === 'warning' ? 'warning' :
                                ($violation->action_taken === 'allowance' ? 'success' : 'danger')
                            }}">
                                @lang('dashboard.' . $violation->action_taken)
                                @if($violation->action_taken === 'deduction')
                                    ({{ $violation->deduction_amount }})
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.recorded_by')</div>
                        <div class="fs-5 fw-bold">{{ $violation->creator->name }}</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.employee_justification')</div>
                        <div class="card card-bordered bg-light-primary rounded">
                            <div class="card-body">
                                <p class="mb-0">{{ $violation->employee_justification ?? __('dashboard.no_justification') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="text-muted fs-7 mb-1">@lang('dashboard.notes')</div>
                        <div class="card card-bordered bg-light-primary rounded">
                            <div class="card-body">
                                <p class="mb-0">{{ $violation->notes ?? __('dashboard.no_notes') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

{{--            <div class="d-flex justify-content-end">--}}
{{--                <a href="{{ route('violations.edit', $violation) }}" class="btn btn-warning me-2">--}}
{{--                    <i class="fas fa-edit me-2"></i>@lang('dashboard.edit')--}}
{{--                </a>--}}
{{--                <form action="{{ route('violations.destroy', $violation) }}" method="POST">--}}
{{--                    @csrf @method('DELETE')--}}
{{--                    <button type="submit" class="btn btn-danger">--}}
{{--                        <i class="fas fa-trash me-2"></i>@lang('dashboard.delete')--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection
