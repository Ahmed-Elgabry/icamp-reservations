@extends('dashboard.layouts.app')

@section('title', __('dashboard.send_manual_message'))

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold m-0">@lang('dashboard.send_manual_message')</h3>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('dashboard.manual-whatsapp-sends.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Message Title -->
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label required">@lang('dashboard.message_title')</label>
                        <input type="text" 
                               name="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" 
                               placeholder="@lang('dashboard.enter_message_title')"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">@lang('dashboard.message_title_help')</div>
                    </div>
                </div>

                <!-- Template Selection -->
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label required">@lang('dashboard.select_template')</label>
                        <select name="template_id" 
                                class="form-select @error('template_id') is-invalid @enderror" 
                                required>
                            <option value="">@lang('dashboard.choose_template')</option>
                            @foreach($manualTemplates as $template)
                                <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('template_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Customer Selection -->
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label">@lang('dashboard.select_customers')</label>
                        <select name="customer_ids[]" 
                                id="customer-select" 
                                class="form-select @error('customer_ids') is-invalid @enderror" 
                                multiple>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ in_array($customer->id, old('customer_ids', [])) ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->phone }})
                                    @if($customer->orders->count() > 0)
                                        - {{ $customer->orders->first()->order_number ?? 'Order #' . $customer->orders->first()->id }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('customer_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">@lang('dashboard.select_customers_help')</div>
                    </div>
                </div>

                <!-- Manual Phone Numbers -->
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label">@lang('dashboard.manual_phone_numbers')</label>
                        <div id="manual-numbers-container">
                            <div class="input-group mb-2">
                                <input type="text" 
                                       name="manual_numbers[]" 
                                       class="form-control @error('manual_numbers.*') is-invalid @enderror" 
                                       placeholder="@lang('dashboard.enter_phone_number')">
                                <button type="button" class="btn btn-outline-danger" onclick="removeManualNumber(this)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addManualNumber()">
                            <i class="fa-solid fa-plus"></i> @lang('dashboard.add_phone_number')
                        </button>
                        @error('manual_numbers.*')
                            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">@lang('dashboard.manual_phone_numbers_help')</div>
                    </div>
                </div>

                <!-- Custom Message -->
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label">@lang('dashboard.custom_message')</label>
                        <textarea name="custom_message" 
                                  class="form-control @error('custom_message') is-invalid @enderror" 
                                  rows="4" 
                                  placeholder="@lang('dashboard.enter_custom_message')">{{ old('custom_message') }}</textarea>
                        @error('custom_message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">@lang('dashboard.custom_message_help')</div>
                    </div>
                </div>

                <!-- File Attachments -->
                <div class="row mb-6">
                    <div class="col-12">
                        <label class="form-label">@lang('dashboard.attachments')</label>
                        <input type="file" 
                               name="attachments[]" 
                               class="form-control @error('attachments.*') is-invalid @enderror" 
                               multiple 
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        @error('attachments.*')
                            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">@lang('dashboard.attachments_help')</div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('dashboard.manual-whatsapp-sends.index') }}" 
                               class="btn btn-light">
                                @lang('dashboard.cancel')
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-paper-plane"></i>
                                @lang('dashboard.send_messages')
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for customer selection
    $('#customer-select').select2({
        placeholder: '@lang("dashboard.select_customers")',
        allowClear: true,
        width: '100%'
    });

    // Add manual number input
    window.addManualNumber = function() {
        const container = document.getElementById('manual-numbers-container');
        const inputGroup = document.createElement('div');
        inputGroup.className = 'input-group mb-2';
        inputGroup.innerHTML = `
            <input type="text" 
                   name="manual_numbers[]" 
                   class="form-control" 
                   placeholder="@lang('dashboard.enter_phone_number')">
            <button type="button" class="btn btn-outline-danger" onclick="removeManualNumber(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        container.appendChild(inputGroup);
    };

    // Remove manual number input
    window.removeManualNumber = function(button) {
        button.closest('.input-group').remove();
    };
});
</script>
@endpush
