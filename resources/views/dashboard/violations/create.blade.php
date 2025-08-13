@extends('dashboard.layouts.app')
@section('pageTitle', __('dashboard.create_violation'))

@section('content')
    <div class="card card-flush shadow-sm">
        <div class="card-header border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">
                    @lang('dashboard.create_violation')
                </span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('violations.index') }}" class="btn btn-light-primary">
                    <i class="fas fa-arrow-left me-2"></i> @lang('dashboard.back_to_list')
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <form action="{{ route('violations.store') }}" method="POST">
                @csrf

                <div class="mb-10">
                    <div class="row g-5">
                        <div class="col-md-6">
                            <label class="form-label required">@lang('dashboard.employee')</label>
                            <select name="employee_id" class="form-select form-select-solid" required>
                                <option value="">@lang('dashboard.select_employee')</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">@lang('dashboard.violation_type')</label>
                            <select name="violation_type_id" class="form-select form-select-solid" required>
                                <option value="">@lang('dashboard.select_violation_type')</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('violation_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">@lang('dashboard.employee_justification')</label>
                            <textarea name="employee_justification" class="form-control form-control-solid" rows="3">{{ old('employee_justification') }}</textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label required">@lang('dashboard.action_taken')</label>
                            <select name="action_taken" id="action-taken" class="form-select form-select-solid" required>
                                <option value="warning" {{ old('action_taken') == 'warning' ? 'selected' : '' }}>@lang('dashboard.warning')</option>
                                <option value="allowance" {{ old('action_taken') == 'allowance' ? 'selected' : '' }}>@lang('dashboard.allowance')</option>
                                <option value="deduction" {{ old('action_taken') == 'deduction' ? 'selected' : '' }}>@lang('dashboard.deduction')</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="deduction-amount-container" style="{{ old('action_taken') == 'deduction' ? '' : 'display: none;' }}">
                            @if(old('action_taken') == 'deduction' || $errors->has('deduction_amount'))
                                <label class="form-label required">@lang('dashboard.deduction_amount')</label>
                                <input type="number" name="deduction_amount" class="form-control form-control-solid"
                                       value="{{ old('deduction_amount') }}" step="0.01" min="0" required>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label class="form-label">@lang('dashboard.notes')</label>
                            <textarea name="notes" class="form-control form-control-solid" rows="3">{{ old('notes') }}</textarea>
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

@push('js')
    <script>
        $(document).ready(function() {
            $('#action-taken').change(function() {
                if ($(this).val() === 'deduction') {
                    $('#deduction-amount-container').html(`
                        <label class="form-label required">@lang('dashboard.deduction_amount')</label>
                        <input type="number" name="deduction_amount" class="form-control form-control-solid"
                            value="{{ old('deduction_amount') }}" step="0.01" min="0" required>
                    `).show();
                } else {
                    $('#deduction-amount-container').empty().hide();
                }
            });
        });
    </script>
@endpush

@push('css')
    <style>
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush
