@extends('dashboard.layouts.app')

@section('pageTitle', __('dashboard.payment_links_show'))

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
                        @can('bookings.payment-links.create')
                        <a href="{{ route('bookings.payment-links.create') }}{{ isset($filteredOrder) ? '?order_id=' . $filteredOrder->id : '' }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> {{ __('dashboard.create_new_payment_link') }}
                        </a>
                        @endcan
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
                                    <th class="min-w-120px">{{ __('dashboard.reservation_number') }}</th>

                                    <th class="min-w-140px">{{ __('dashboard.customer_name') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.amount_aed') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.request_id') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.checkout_id') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.checkout_key') }}</th>
                                    <th class="min-w-120px">@lang('dashboard.created_date')</th>
                                    <th class="min-w-120px">@lang('dashboard.created_time')</th>
                                    <th class="min-w-100px">{{ __('dashboard.status') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentLinks as $index => $paymentLink)
                                <tr class="payment-link-row" data-id="{{ $paymentLink->id }}">
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
                                            {{ $paymentLink->order_id ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ number_format($paymentLink->amount, 2) }}
                                        </span>
                                    </td>

                                    <!-- Request ID -->
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">

                                            @can('bookings.payment-links.show')
                                            @if($paymentLink->request_id)
                                            <i class="fa fa-eye text-primary preview-icon"
                                                style="cursor: pointer;"
                                                data-payment-id="{{ $paymentLink->id }}"
                                                data-customer-name="{{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}"
                                                data-amount="{{ number_format($paymentLink->amount, 2) }}"
                                                data-order-id="{{ $paymentLink->order_id ?? __('dashboard.not_specified') }}"
                                                data-status="{{ $paymentLink->status ?? __('dashboard.not_specified') }}"
                                                data-created-at="{{ $paymentLink->created_at->format('Y-m-d H:i') }}"
                                                data-request-id="{{ $paymentLink->request_id ?? __('dashboard.not_specified') }}"
                                                data-checkout-id="{{ $paymentLink->checkout_id ?? __('dashboard.not_specified') }}"
                                                data-checkout-key="{{ $paymentLink->checkout_key ?? __('dashboard.not_specified') }}"
                                                title="{{ __('dashboard.view_details') }}"></i>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>

                                    <!-- Checkout ID -->
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @can('bookings.payment-links.show')
                                            @if($paymentLink->checkout_id)
                                            <i class="fa fa-eye text-primary preview-icon"
                                                style="cursor: pointer;"
                                                data-payment-id="{{ $paymentLink->id }}"
                                                data-customer-name="{{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}"
                                                data-amount="{{ number_format($paymentLink->amount, 2) }}"
                                                data-order-id="{{ $paymentLink->order_id ?? __('dashboard.not_specified') }}"
                                                data-status="{{ $paymentLink->status ?? __('dashboard.not_specified') }}"
                                                data-created-at="{{ $paymentLink->created_at->format('Y-m-d H:i') }}"
                                                data-request-id="{{ $paymentLink->request_id ?? __('dashboard.not_specified') }}"
                                                data-checkout-id="{{ $paymentLink->checkout_id ?? __('dashboard.not_specified') }}"
                                                data-checkout-key="{{ $paymentLink->checkout_key ?? __('dashboard.not_specified') }}"
                                                title="{{ __('dashboard.view_details') }}"></i>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>

                                    <!-- Checkout Key -->
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @can('bookings.payment-links.show')
                                            @if($paymentLink->checkout_key)
                                            <i class="fa fa-eye text-primary preview-icon"
                                                style="cursor: pointer;"
                                                data-payment-id="{{ $paymentLink->id }}"
                                                data-customer-name="{{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}"
                                                data-amount="{{ number_format($paymentLink->amount, 2) }}"
                                                data-order-id="{{ $paymentLink->order_id ?? __('dashboard.not_specified') }}"
                                                data-status="{{ $paymentLink->status ?? __('dashboard.not_specified') }}"
                                                data-created-at="{{ $paymentLink->created_at->format('Y-m-d H:i') }}"
                                                data-request-id="{{ $paymentLink->request_id ?? __('dashboard.not_specified') }}"
                                                data-checkout-id="{{ $paymentLink->checkout_id ?? __('dashboard.not_specified') }}"
                                                data-checkout-key="{{ $paymentLink->checkout_key ?? __('dashboard.not_specified') }}"
                                                title="{{ __('dashboard.view_details') }}"></i>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>

                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->created_at->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bolder text-h6 d-block fs-6">
                                            {{ $paymentLink->created_at->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td>
                                        {!! $paymentLink->status_badge !!}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-sack">
                                            <div class="d-flex">
                                                @can('bookings.scan-qr')
                                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 qr-btn"
                                                    data-id="{{ $paymentLink->id }}"
                                                    title="QR Code">
                                                    <i class="fa fa-qrcode"></i>
                                                </button>
                                                @endcan
                                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 copy-btn"
                                                    data-id="{{ $paymentLink->id }}"
                                                    title="{{ __('dashboard.view_and_copy') }}">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                                @can('bookings.send-email')

                                                @if($paymentLink->customer && $paymentLink->customer->email)
                                                <button class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1 resend-email-btn"
                                                    data-id="{{ $paymentLink->id }}"
                                                    data-customer-name="{{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}"
                                                    title="{{ __('dashboard.resend_email') }}">
                                                    <i class="fa fa-envelope"></i>
                                                </button>
                                                @endif
                                                @endcan
                                                @can('bookings.send-whatsapp')
                                                @if($paymentLink->customer && $paymentLink->customer->phone)
                                                <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1 resend-whatsapp-btn"
                                                    data-id="{{ $paymentLink->id }}"
                                                    data-customer-name="{{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}"
                                                    data-payment-url="{{ $paymentLink->payment_url }}"
                                                    title="{{ __('dashboard.resend_whatsapp') }}">
                                                    <i class="fab fa-whatsapp"></i>
                                                </button>
                                                @endif
                                                @endcan
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

<!-- Details Modal - Only for showing record details -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">{{ __('dashboard.payment_link_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.close') }}</button>
            </div>
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
                    <!-- QR Code will be displayed here -->
                </div>
                <div class="mt-3">
                    <button id="downloadQrBtn" class="btn btn-success me-2">
                        <i class="fa fa-download"></i> {{ __('dashboard.download_qr_code') }}
                    </button>

                    <a href="#" id="qrLinkBtn" target="_blank" class="btn btn-primary">
                        <i class="fa fa-external-link"></i> {{ __('dashboard.open_payment_link') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentPaymentUrl = '';
        let currentQrCodeDataURL = null;

        // Fetch API helper - THIS WAS MISSING
        async function fetchAPI(url, options = {}) {
            try {
                const response = await fetch(url, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        ...options.headers
                    },
                    ...options
                });
                return await response.json();
            } catch (error) {
                console.error('API Error:', error);
                return {
                    success: false,
                    message: 'Network error'
                };
            }
        }

        // Generate QR Code function - THIS WAS MISSING
        function generateQRCode(url, containerId) {
            try {
                const qr = qrcode(0, 'M');
                qr.addData(url);
                qr.make();

                const qrCodeHtml = qr.createImgTag(4, 8);
                document.getElementById(containerId).innerHTML = qrCodeHtml;

                // Generate canvas for download
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const modules = qr.getModuleCount();
                const cellSize = 8;
                const margin = 32;
                const size = modules * cellSize + (margin * 2);

                canvas.width = size;
                canvas.height = size;

                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, size, size);

                ctx.fillStyle = '#000000';
                for (let row = 0; row < modules; row++) {
                    for (let col = 0; col < modules; col++) {
                        if (qr.isDark(row, col)) {
                            ctx.fillRect(
                                margin + col * cellSize,
                                margin + row * cellSize,
                                cellSize,
                                cellSize
                            );
                        }
                    }
                }

                currentQrCodeDataURL = canvas.toDataURL('image/png');
                return true;
            } catch (error) {
                console.error('QR Code generation error:', error);
                document.getElementById(containerId).innerHTML = '<p class="text-danger">QR Code creation error</p>';
                return false;
            }
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const existingToasts = document.querySelectorAll('.custom-toast');
            existingToasts.forEach(toast => toast.remove());

            const toastId = 'toast_' + Date.now();
            const bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
            const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';

            const toastElement = document.createElement('div');
            toastElement.id = toastId;
            toastElement.className = 'custom-toast';
            toastElement.style.backgroundColor = bgColor;
            toastElement.innerHTML = `
            <i class="fa ${icon} me-2"></i>
            <strong>${message}</strong>
            <button type="button" class="btn-close btn-close-white ms-2" onclick="document.getElementById('${toastId}').remove()"></button>
        `;

            document.body.appendChild(toastElement);

            setTimeout(function() {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        }

        // Enhanced Copy to clipboard function specifically for iPhone Safari
        async function copyToClipboard(text) {
            console.log('Attempting to copy:', text);

            // Method 1: Try modern clipboard API first (but it often fails on iPhone)
            if (navigator.clipboard && window.isSecureContext) {
                try {
                    await navigator.clipboard.writeText(text);
                    console.log('Clipboard API success');
                    return true;
                } catch (err) {
                    console.log('Clipboard API failed:', err);
                    // Continue to fallback
                }
            }

            // Method 2: Enhanced fallback for iPhone Safari
            return iPhoneSafariCopy(text);
        }

        // Specialized copy function for iPhone Safari
        function iPhoneSafariCopy(text) {
            try {
                console.log('Using iPhone Safari copy method');

                // Create a textarea (works better than input on iOS)
                const textArea = document.createElement('textarea');
                textArea.value = text;

                // Critical iOS Safari positioning and styling
                textArea.style.position = 'absolute';
                textArea.style.left = '-9999px';
                textArea.style.top = '0';
                textArea.style.opacity = '0';
                textArea.style.pointerEvents = 'none';
                textArea.style.userSelect = 'text';
                textArea.style.webkitUserSelect = 'text';
                textArea.style.MozUserSelect = 'text';
                textArea.style.msUserSelect = 'text';

                // iOS specific attributes
                textArea.setAttribute('readonly', false);
                textArea.contentEditable = true;
                textArea.readOnly = false;

                document.body.appendChild(textArea);

                // Focus and select - critical for iOS
                textArea.focus();
                textArea.select();

                // Try different selection methods for iOS compatibility
                if (textArea.setSelectionRange) {
                    textArea.setSelectionRange(0, text.length);
                }

                // Additional iOS selection method
                const range = document.createRange();
                range.selectNodeContents(textArea);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);

                // Execute copy command
                let successful = false;
                try {
                    successful = document.execCommand('copy');
                    console.log('execCommand result:', successful);
                } catch (err) {
                    console.log('execCommand failed:', err);
                }

                // Clean up
                document.body.removeChild(textArea);

                return successful;
            } catch (err) {
                console.error('iPhone Safari copy failed:', err);
                return false;
            }
        }

        // Detect if user is on iPhone
        function isIPhone() {
            return /iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        }

        // Show success state on button
        function showSuccessState(button, originalText) {
            button.classList.remove('btn-bg-light');
            button.classList.add('btn-success');
            button.innerHTML = '<i class="fa fa-check"></i>';

            setTimeout(function() {
                button.classList.remove('btn-success');
                button.classList.add('btn-bg-light');
                button.disabled = false;
                button.innerHTML = originalText;
            }, 2000);
        }

        // Reset button to original state
        function resetButton(button, originalText) {
            button.disabled = false;
            button.innerHTML = originalText;
        }

        // Show manual copy modal for iPhone when automatic copy fails
        function showManualCopyModal(url) {
            // Remove existing manual copy modal if any
            const existingModal = document.getElementById('manualCopyModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Create manual copy modal
            const modalHtml = `
            <div class="modal fade" id="manualCopyModal" tabindex="-1" aria-labelledby="manualCopyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="manualCopyModalLabel">{{ __('dashboard.copy_payment_link') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">{{ __('dashboard.tap_and_hold_to_copy') }}</p>
                            <div class="input-group">
                                <input type="text" class="form-control" id="manualCopyInput" value="${url}" readonly>
                                <button class="btn btn-primary" type="button" id="manualCopyBtn">
                                    <i class="fa fa-copy"></i> {{ __('dashboard.copy') }}
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">{{ __('dashboard.or_tap_link_to_open') }}</small>
                            <div class="mt-3">
                                <a href="${url}" target="_blank" class="btn btn-outline-primary w-100">
                                    <i class="fa fa-external-link"></i> {{ __('dashboard.open_payment_link') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('manualCopyModal'));
            modal.show();

            // Handle manual copy button
            document.getElementById('manualCopyBtn').addEventListener('click', async function() {
                const input = document.getElementById('manualCopyInput');
                const button = this;
                const originalText = button.innerHTML;

                // Select the text
                input.focus();
                input.select();
                input.setSelectionRange(0, 99999);

                // Try to copy
                const success = await copyToClipboard(url);

                if (success) {
                    button.innerHTML = '<i class="fa fa-check"></i> {{ __("dashboard.copy_success") }}';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    showToast('{{ __("dashboard.payment_link_copied") }}', 'success');

                    setTimeout(() => {
                        modal.hide();
                    }, 1000);
                } else {
                    showToast('{{ __("dashboard.please_copy_manually") }}', 'info');
                }
            });

            // Auto-select text when modal is shown
            modal._element.addEventListener('shown.bs.modal', function() {
                const input = document.getElementById('manualCopyInput');
                setTimeout(() => {
                    input.focus();
                    input.select();
                    input.setSelectionRange(0, 99999);
                }, 100);
            });

            // Clean up when modal is hidden
            modal._element.addEventListener('hidden.bs.modal', function() {
                document.getElementById('manualCopyModal').remove();
            });
        }

        // Show details in modal (client-side only)
        function showDetailsModal(data) {
            console.log('Showing modal with data:', data);

            // Handle case where data might be undefined or null
            if (!data) {
                console.error('No data provided to showDetailsModal');
                return;
            }

            const content = `
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>{{ __('dashboard.customer_name') }}:</strong><br>
                        <span class="text-muted">${data.customer_name || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('dashboard.amount_aed') }}:</strong><br>
                        <span class="text-muted">${data.amount || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('dashboard.reservation_number') }}:</strong><br>
                        <span class="text-muted">${data.order_id || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('dashboard.status') }}:</strong><br>
                        <span class="text-muted">${data.status || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('dashboard.date_time') }}:</strong><br>
                        <span class="text-muted">${data.created_at || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>{{ __('dashboard.request_id') }}:</strong><br>
                        <span class="text-muted user-select-all">${data.request_id || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('dashboard.checkout_id') }}:</strong><br>
                        <span class="text-muted user-select-all">${data.checkout_id || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('dashboard.checkout_key') }}:</strong><br>
                        <span class="text-muted user-select-all">${data.checkout_key || '{{ __('dashboard.not_specified') }}'}</span>
                    </div>
                </div>
            </div>
        `;

            document.getElementById('modalContent').innerHTML = content;

            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        }

        // Handle preview icon clicks - show record details (client-side only)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('preview-icon')) {
                const icon = e.target;

                // Get data from data attributes
                const data = {
                    customer_name: icon.getAttribute('data-customer-name'),
                    amount: icon.getAttribute('data-amount'),
                    order_id: icon.getAttribute('data-order-id'),
                    status: icon.getAttribute('data-status'),
                    created_at: icon.getAttribute('data-created-at'),
                    request_id: icon.getAttribute('data-request-id'),
                    checkout_id: icon.getAttribute('data-checkout-id'),
                    checkout_key: icon.getAttribute('data-checkout-key')
                };

                // Show modal with the data
                showDetailsModal(data);
            }
        });

        // Handle QR button clicks
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.qr-btn')) {
                const button = e.target.closest('.qr-btn');
                const id = button.getAttribute('data-id');

                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                try {
                    const response = await fetchAPI(`/payment-links/${id}/qr-code`);

                    if (response.success) {
                        currentPaymentUrl = response.url;

                        if (generateQRCode(response.url, 'qrCodeContainer')) {
                            document.getElementById('qrLinkBtn').href = response.url;

                            const modal = new bootstrap.Modal(document.getElementById('qrModal'));
                            modal.show();
                        }
                    } else {
                        showToast(response.message || '{{ __("dashboard.failed_to_load_qr") }}', 'error');
                    }
                } catch (error) {
                    showToast('{{ __("dashboard.failed_to_load_qr") }}', 'error');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fa fa-qrcode"></i>';
                }
            }
        });

        // Updated Copy button event handler with iPhone-specific handling
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.copy-btn')) {
                const button = e.target.closest('.copy-btn');
                const id = button.getAttribute('data-id');
                const originalText = button.innerHTML;

                // Change button state immediately
                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                try {
                    const response = await fetchAPI(`/payment-links/${id}/copy`);

                    if (response.success) {
                        const textToCopy = response.url;

                        // Special handling for iPhone
                        if (isIPhone()) {
                            console.log('iPhone detected, using enhanced copy method');

                            // For iPhone, we need to be more aggressive and provide fallback
                            const copySuccess = await copyToClipboard(textToCopy);

                            if (copySuccess) {
                                showSuccessState(button, originalText);

                            } else {
                                // iPhone fallback: Show a modal with the link for manual copy
                                showManualCopyModal(textToCopy);
                                resetButton(button, originalText);
                            }
                        } else {
                            // For other devices, use standard method
                            const copySuccess = await copyToClipboard(textToCopy);

                            if (copySuccess) {
                                showSuccessState(button, originalText);

                            } else {
                                showToast('{{ __("dashboard.failed_to_copy") }}', 'error');
                                resetButton(button, originalText);
                            }
                        }
                    } else {
                        showToast(response.message || '{{ __("dashboard.failed_to_get_payment_link") }}', 'error');
                        resetButton(button, originalText);
                    }
                } catch (error) {
                    console.error('Copy error:', error);
                    showToast('{{ __("dashboard.failed_to_copy_link") }}', 'error');
                    resetButton(button, originalText);
                }
            }
        });

        // Handle Resend Email button clicks
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.resend-email-btn')) {
                const button = e.target.closest('.resend-email-btn');
                const id = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-customer-name');

                // Confirm before sending
                if (!confirm('{{ __('
                        dashboard.confirm_resend_email ') }} ' + customerName + '?')) {
                    return;
                }

                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                try {
                    const response = await fetchAPI(`/payment-links/${id}/resend-email`, {
                        method: 'POST'
                    });

                    if (response.success) {
                        showToast(response.message || '{{ __("dashboard.email_sent_to_customer") }}', 'success');
                    } else {
                        showToast(response.message || '{{ __("dashboard.payment_link_email_resent_error") }}', 'error');
                    }
                } catch (error) {
                    showToast('{{ __("dashboard.payment_link_email_resent_error") }}', 'error');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fa fa-envelope"></i>';
                }
            }

            // Handle Resend WhatsApp button clicks
            if (e.target.closest('.resend-whatsapp-btn')) {
                const button = e.target.closest('.resend-whatsapp-btn');
                const id = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-customer-name');
                const paymentUrl = button.getAttribute('data-payment-url');

                // Confirm before sending
                if (!confirm('{{ __('
                        dashboard.confirm_resend_whatsapp ') }} ' + customerName + '?')) {
                    return;
                }

                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                try {
                    const response = await fetchAPI(`/payment-links/${id}/resend-whatsapp`, {
                        method: 'POST',
                        body: JSON.stringify({
                            payment_url: paymentUrl
                        })
                    });

                    if (response.success) {
                        showToast(response.message || '{{ __("dashboard.payment_link_whatsapp_resent_success") }}', 'success');
                    } else {
                        showToast(response.message || '{{ __("dashboard.payment_link_whatsapp_resent_error") }}', 'error');
                    }
                } catch (error) {
                    showToast('{{ __("dashboard.payment_link_whatsapp_resent_error") }}', 'error');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fab fa-whatsapp"></i>';
                }
            }
        });

        // Download QR Code
        document.getElementById('downloadQrBtn').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;

            if (!currentQrCodeDataURL) {
                showToast('{{ __("dashboard.qr_code_prepare_error") }}', 'error');
                return;
            }

            try {
                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Downloading...';

                const downloadLink = document.createElement('a');
                downloadLink.href = currentQrCodeDataURL;
                downloadLink.download = 'payment-qr-code.png';

                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);

                button.classList.remove('btn-success');
                button.classList.add('btn-info');
                button.innerHTML = '<i class="fa fa-check"></i> {{ __("dashboard.downloaded") }}';

                showToast('{{ __("dashboard.qr_code_downloaded_success") }}', 'success');

                setTimeout(function() {
                    button.classList.remove('btn-info');
                    button.classList.add('btn-success');
                    button.disabled = false;
                    button.innerHTML = originalText;
                }, 2000);

            } catch (error) {
                console.error('Download failed:', error);
                button.disabled = false;
                button.innerHTML = originalText;
                showToast('{{ __("dashboard.failed_to_download_qr") }}', 'error');
            }
        });
    });
</script>

<style>
    /* Toast styling */
    .custom-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .btn-close-white {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        padding: 0;
        margin: 0;
    }

    /* QR Code styling */
    #qrCodeContainer {
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: inline-block;
        border: 2px solid #f8f9fa;
    }

    #qrCodeContainer img {
        border-radius: 8px;
        max-width: 200px;
        height: auto;
    }

    /* Preview icon hover effects */
    .preview-icon:hover {
        transform: scale(1.2);
        transition: transform 0.2s ease;
    }

    /* Button hover effects */
    .btn-icon:hover {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
    }

    /* Make text selectable for copying in modal */
    .user-select-all {
        user-select: all !important;
        cursor: text;
        padding: 2px 4px;
        background-color: #f8f9fa;
        border-radius: 3px;
    }

    .user-select-all:hover {
        background-color: #e9ecef;
    }

    /* Modal enhancements */
    .modal-lg {
        max-width: 800px;
    }

    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        font-size: 0.875rem;
        line-height: 1.5;
        color: #495057;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
    }
</style>

@endsection