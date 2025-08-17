@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.create_payment_link'))

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">

                <!--begin::Card-->
                <div class="card mb-5 mb-xl-10">
                    <div class="card-header border-0">
                        <div class="card-title m-0">
                            <h3 class="fw-bolder m-0 text-danger">{{ __('dashboard.create_payment_link') }}</h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('payment-links.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('dashboard.back_to_list') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('payment-links.store') }}" method="POST" id="createPaymentLinkForm">
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
                                        <input type="number"
                                               name="amount"
                                               class="form-control form-control-lg"
                                               step="0.01"
                                               min="0.01"
                                               value="{{ old('amount') }}"
                                               placeholder="{{ __('dashboard.payment_link_amount_placeholder') }}"
                                               required
                                               style="background-color: white; border: 1px solid #e4e6ea;" />
                                    </div>
                                    <div class="col-md-4">
                                        <div class="bg-light-secondary p-3 rounded text-center">
                                            <span class="fw-bold">{{ __('dashboard.payment_link_amount') }} / {{ __('dashboard.currency_aed') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Second Row: Copy Link, Link Display, Create Link -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success w-100" id="copyLinkBtn" disabled>
                                            {{ __('dashboard.payment_link_copy') }}
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-control bg-white" style="border: 1px solid #e4e6ea; min-height: 50px;">
                                            <span class="text-muted" id="linkDisplay">{{ __('dashboard.payment_link_example') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success w-100">
                                            + {{ __('dashboard.create_payment_link') }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Third Row: QR Code -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="bg-success text-white p-3 rounded text-center">
                                            <span class="fw-bold">{{ __('dashboard.payment_link_qr_code') }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Additional Fields (Hidden by default, can be shown if needed) -->
                            <div class="mt-4" id="additionalFields" style="display: none;">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('dashboard.payment_link_expires_at') }}</label>
                                        <input type="datetime-local"
                                               name="expires_at"
                                               class="form-control"
                                               value="{{ old('expires_at') }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('dashboard.payment_link_description') }}</label>
                                        <textarea name="description"
                                                  class="form-control"
                                                  rows="3"
                                                  placeholder="{{ __('dashboard.payment_link_description_placeholder') }}">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Toggle Additional Fields Button -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="toggleFields">
                                    <i class="fa fa-cog"></i> {{ __('dashboard.additional_options') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">{{ __('dashboard.payment_link_creation_success') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                        <p>{{ __('dashboard.payment_link_creation_success') }}</p>
                        <div class="mt-3">
                            <a href="#" id="generatedLink" target="_blank" class="btn btn-primary">
                                <i class="fa fa-external-link"></i> {{ __('dashboard.open_payment_link') }}
                            </a>
                            <button type="button" class="btn btn-secondary" id="copyGeneratedLink">
                                <i class="fa fa-copy"></i> {{ __('dashboard.payment_link_copy') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.close') }}</button>
                    <a href="{{ route('payment-links.index') }}" class="btn btn-primary">{{ __('dashboard.back_to_list') }}</a>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            // Toggle Additional Fields
            $('#toggleFields').click(function() {
                $('#additionalFields').toggle();
                const isVisible = $('#additionalFields').is(':visible');
                $(this).html(isVisible ? '<i class="fa fa-eye-slash"></i> {{ __('dashboard.hide_options') }}' : '<i class="fa fa-cog"></i> {{ __('dashboard.additional_options') }}');
            });

            // Form Submission
            $('#createPaymentLinkForm').submit(function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');

                // تعطيل الزر
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fa fa-spinner fa-spin"></i> {{ __('dashboard.creating') }}...');

                // إرسال النموذج
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            // تحديث عرض الرابط
                            $('#linkDisplay').html(`<a href="${response.payment_url}" target="_blank">${response.payment_url}</a>`);

                            // تفعيل زر نسخ الرابط
                            $('#copyLinkBtn').prop('disabled', false);

                            // عرض النجاح
                            $('#generatedLink').attr('href', response.payment_url);
                            $('#successModal').modal('show');

                            toastr.success('{{ __('dashboard.payment_link_creation_success') }}');
                        } else {
                            toastr.error(response.message || '{{ __('dashboard.payment_link_errors.creation_error') }}');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // أخطاء التحقق
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                const field = form.find(`[name="${key}"]`);
                                field.addClass('is-invalid');
                                field.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                            });
                        } else {
                            toastr.error('{{ __('dashboard.payment_link_errors.creation_error') }}');
                        }
                    },
                    complete: function() {
                        // إعادة تفعيل الزر
                        submitBtn.prop('disabled', false);
                        submitBtn.html('+ {{ __('dashboard.create_payment_link') }}');
                    }
                });
            });

            // نسخ الرابط المُنشأ
            $('#copyGeneratedLink').click(function() {
                const link = $('#generatedLink').attr('href');
                navigator.clipboard.writeText(link).then(function() {
                    toastr.success('{{ __('dashboard.payment_link_copy_success') }}');
                });
            });

            // نسخ الرابط من الصفحة
            $('#copyLinkBtn').click(function() {
                const linkText = $('#linkDisplay').text();
                if (linkText && !linkText.includes('{{ __('dashboard.payment_link_example') }}')) {
                    navigator.clipboard.writeText(linkText).then(function() {
                        toastr.success('{{ __('dashboard.payment_link_copy_success') }}');
                    });
                }
            });

            // إزالة أخطاء التحقق عند الكتابة
            $('input, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
        });
    </script>
@endsection
