@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.payment_links_index'))

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            
            <!--begin::Card-->
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">
                            @if(isset($filteredOrder))
                                {{ __('dashboard.payment_links_for_order', ['order_id' => $filteredOrder->id, 'customer_name' => $filteredOrder->customer->name ?? __('dashboard.not_specified')]) }}
                            @else
                                {{ __('dashboard.payment_links_index') }}
                            @endif
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        @if(isset($filteredOrder))
                            <a href="{{ route('orders.edit', $filteredOrder->id) }}" class="btn btn-secondary me-2">
                                <i class="fa fa-arrow-left"></i> {{ __('dashboard.back_to_order') }}
                            </a>
                        @endif
                        <a href="{{ route('payment-links.create') }}{{ isset($filteredOrder) ? '?order_id=' . $filteredOrder->id : '' }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> {{ __('dashboard.create_new_payment_link') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bolder text-muted">
                                    <th class="w-25px">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1" data-kt-check="true" data-kt-check-target=".widget-9-check" />
                                        </div>
                                    </th>
                                    <th class="min-w-150px">{{ __('dashboard.serial_number') }}</th>
                                    <th class="min-w-140px">{{ __('dashboard.customer_name') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.reservation_number') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.amount_aed') }}</th>
                                    <th class="min-w-120px">Request ID</th>
                                    <th class="min-w-120px">Checkout ID</th>
                                    <th class="min-w-120px">Checkout Key</th>
                                    <th class="min-w-120px">{{ __('dashboard.date_time') }}</th>
                                    <th class="min-w-100px">{{ __('dashboard.status') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentLinks as $index => $paymentLink)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input widget-9-check" type="checkbox" value="{{ $paymentLink->id }}" />
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->order_id ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ number_format($paymentLink->amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->request_id ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->checkout_id ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->checkout_key ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! $paymentLink->status_badge !!}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-sack">
                                            <div class="d-flex">
                                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 qr-btn" 
                                                        data-id="{{ $paymentLink->id }}" 
                                                        title="QR Code">
                                                    <i class="fa fa-qrcode"></i>
                                                </button>
                                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 resend-btn" 
                                                        data-id="{{ $paymentLink->id }}" 
                                                        title="{{ __('dashboard.resend_link') }}">
                                                    <i class="fa fa-share"></i>
                                                </button>
                                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 copy-btn" 
                                                        data-id="{{ $paymentLink->id }}" 
                                                        title="{{ __('dashboard.view_and_copy') }}">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">
                                        <div class="alert alert-info">
                                            {{ __('dashboard.no_payment_links') }}
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">{{ __('dashboard.qr_code_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContainer">
                    <!-- سيتم عرض QR Code هنا -->
                </div>
                <div class="mt-3">
                    <a href="#" id="paymentLinkUrl" target="_blank" class="btn btn-primary">
                        <i class="fa fa-external-link"></i> {{ __('dashboard.open_payment_link') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // QR Code Button
    $('.qr-btn').click(function() {
        const id = $(this).data('id');
        $.get(`/payment-links/${id}/qr-code`, function(response) {
            if (response.success) {
                $('#qrCodeContainer').html(`
                    <div class="alert alert-info">
                        <i class="fa fa-qrcode fa-3x"></i>
                        <p class="mt-2">{{ __('dashboard.qr_code_coming_soon') }}</p>
                    </div>
                `);
                $('#paymentLinkUrl').attr('href', response.url);
                $('#qrModal').modal('show');
            }
        });
    });

    // Resend Button
    $('.resend-btn').click(function() {
        const id = $(this).data('id');
        const btn = $(this);
        
        btn.prop('disabled', true);
        btn.html('<i class="fa fa-spinner fa-spin"></i>');
        
        $.post(`/payment-links/${id}/resend`, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        }).always(function() {
            btn.prop('disabled', false);
            btn.html('<i class="fa fa-share"></i>');
        });
    });

    // Copy Button
    $('.copy-btn').click(function() {
        const id = $(this).data('id');
        
        $.get(`/payment-links/${id}/copy`, function(response) {
            if (response.success) {
                // نسخ الرابط إلى الحافظة
                navigator.clipboard.writeText(response.url).then(function() {
                    toastr.success('{{ __('dashboard.payment_link_copied') }}');
                });
            }
        });
    });

    // Update Status
    function updateStatuses() {
        $('.payment-link-row').each(function() {
            const id = $(this).data('id');
            $.get(`/payment-links/${id}/update-status`, function(response) {
                if (response.success) {
                    // تحديث الحالة في الجدول
                    location.reload();
                }
            });
        });
    }

    // تحديث الحالات كل دقيقة
    setInterval(updateStatuses, 60000);
});
</script>
@endpush
