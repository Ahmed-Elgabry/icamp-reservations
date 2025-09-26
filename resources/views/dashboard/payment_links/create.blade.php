@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.payment_links'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="page-title">{{ __('dashboard.create_payment_link') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">{{ __('dashboard.home') }}</a>
                            </li>

                            <li class="breadcrumb-item active">{{ __('dashboard.create_payment_link') }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('bookings.payment-links.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> {{ __('dashboard.back_to_list') }}
                    </a>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('bookings.payment-links.store') }}" method="POST">
                @csrf

                <!-- Hidden fields for order_id if passed via URL -->
                @if(request('order_id'))
                <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                @endif

                <!-- Order Information Display -->
                @if(isset($selectedOrder) && $selectedOrder)
                <div class="alert alert-info mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>{{ __('dashboard.order_id') }}:</strong> {{ $selectedOrder->id }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('dashboard.customer') }}:</strong> {{ $selectedOrder->customer->name ?? __('dashboard.no_description') }}
                        </div>
                    </div>
                    @if($selectedOrder->customer->phone)
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>{{ __('dashboard.phone') }}:</strong> {{ $selectedOrder->customer->phone }}
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Main Container with Light Grey Border -->
                <div class="border border-light-secondary rounded p-4 bg-light">

                    <!-- First Row: Amount Input -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">{{ __('dashboard.payment_link_amount') }}</label>
                            <input type="number"
                                name="amount"
                                class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                step="0.01"
                                min="0.01"
                                value="{{ old('amount') }}"
                                placeholder="{{ __('dashboard.payment_link_amount_placeholder') }}"
                                required
                                style="background-color: white; border: 1px solid #e4e6ea;" />
                            @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light-secondary p-3 rounded text-center">
                                <span class="fw-bold">{{ __('dashboard.payment_link_amount') }} / {{ __('dashboard.currency_aed') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row: Create Link Button -->
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fa fa-plus me-2"></i> {{ __('dashboard.create_payment_link') }}
                            </button>
                        </div>
                    </div>

                    <!-- Third Row: QR Code -->


                </div>

                <!-- Additional Fields (Hidden by default, can be shown if needed) -->
                <div class="mt-4" id="additionalFields" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('dashboard.payment_link_expires_at') }}</label>
                            <input type="datetime-local"
                                name="expires_at"
                                class="form-control @error('expires_at') is-invalid @enderror"
                                value="{{ old('expires_at') }}" />
                            @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('dashboard.payment_link_description') }}</label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3"
                                placeholder="{{ __('dashboard.payment_link_description_placeholder') }}">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <!-- Email Notification Option -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox"
                                class="form-check-input"
                                id="send_email"
                                name="send_email"
                                value="1"
                                {{ old('send_email', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="send_email">
                                <i class="fa fa-envelope me-2"></i> {{ __('dashboard.send_email_to_customer') }}
                            </label>
                            <small class="form-text text-muted d-block mt-1">
                                {{ __('dashboard.send_email_to_customer_help') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Notification Option -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox"
                                class="form-check-input"
                                id="send_whatsapp"
                                name="send_whatsapp"
                                value="1"
                                {{ old('send_whatsapp', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="send_whatsapp">
                                <i class="fab fa-whatsapp me-2 text-success"></i> {{ __('dashboard.send_whatsapp_to_customer') }}
                            </label>
                            <small class="form-text text-muted d-block mt-1">
                                {{ __('dashboard.send_whatsapp_to_customer_help') }}
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle Additional Fields
        $('#toggleFields').click(function() {
            $('#additionalFields').toggle();
            const isVisible = $('#additionalFields').is(':visible');
            $(this).html(isVisible ? '<i class="fa fa-eye-slash"></i> {{ __('
                dashboard.hide_options ') }}' : '<i class="fa fa-cog"></i> {{ __('
                dashboard.additional_options ') }}');
        });

        // Remove validation errors on input
        $('input, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    });
</script>
@endpush
@endsection
