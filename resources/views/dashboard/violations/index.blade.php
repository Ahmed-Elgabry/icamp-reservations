@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.violations'))

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h3>@lang('dashboard.violations')</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('violations.create') }}" class="btn btn-primary">
                    @lang('dashboard.create_violation')
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="mb-5">
                <form action="{{ route('violations.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">@lang('dashboard.employee')</label>
                        <select name="employee_id" class="form-select form-select-solid">
                            <option value="">@lang('dashboard.all')</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('dashboard.violation_type')</label>
                        <select name="violation_type_id" class="form-select form-select-solid">
                            <option value="">@lang('dashboard.all')</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ request('violation_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">@lang('dashboard.action_taken')</label>
                        <select name="action_taken" class="form-select form-select-solid">
                            <option value="">@lang('dashboard.all')</option>
                            <option value="warning" {{ request('action_taken') == 'warning' ? 'selected' : '' }}>@lang('dashboard.warning')</option>
                            <option value="allowance" {{ request('action_taken') == 'allowance' ? 'selected' : '' }}>@lang('dashboard.allowance')</option>
                            <option value="deduction" {{ request('action_taken') == 'deduction' ? 'selected' : '' }}>@lang('dashboard.deduction')</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-2"></i>@lang('dashboard.filter')
                        </button>
                        <a href="{{ route('violations.index') }}" class="btn btn-light">
                            <i class="fas fa-sync-alt me-2"></i>@lang('dashboard.reset')
                        </a>
                    </div>
                </form>
            </div>

            <table class="table align-middle table-row-dashed fs-6 gy-5">
                <thead class="text-gray-400 fw-bolder">
                <tr>
                    <th>@lang('dashboard.employee')</th>
                    <th>@lang('dashboard.violation_type')</th>
                    <th>@lang('dashboard.violation_date')</th>
                    <th>@lang('dashboard.violation_time')</th>
                    <th>@lang('dashboard.violation_place')</th>
                    <th>@lang('dashboard.action_taken')</th>
                    <th>@lang('dashboard.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($violations as $violation)
                    <tr>
                        <td>{{ $violation->employee->name }}</td>
                        <td>{{ $violation->type->name }}</td>
                        <td>{{ $violation->violation_date ? $violation->violation_date->format('Y-m-d') : '-' }}</td>
                        <td>{{ $violation->violation_time ?? '-' }}</td>
                        <td>{{ Str::limit($violation->violation_place, 30) ?? '-' }}</td>
                        <td>
                            <span class="badge badge-light-{{
                                $violation->action_taken === 'warning' ? 'warning' :
                                ($violation->action_taken === 'allowance' ? 'success' : 'danger')
                            }}">
                                @lang('dashboard.' . $violation->action_taken)
                                @if($violation->action_taken === 'deduction')
                                    ({{ $violation->deduction_amount }})
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('violations.show', $violation) }}" class="btn btn-sm btn-info">
                                @lang('dashboard.view')
                            </a>
                            <a href="{{ route('violations.edit', $violation) }}" class="btn btn-sm btn-warning">
                                @lang('dashboard.edit')
                            </a>
                            <form action="{{ route('violations.destroy', $violation) }}" method="POST" class="d-inline">
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
