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
                                    <th class="min-w-120px">{{ __('dashboard.request_id') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.checkout_id') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.checkout_key') }}</th>
                                    <th class="min-w-120px">{{ __('dashboard.date_time') }}</th>
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
                                        <td>
                                            <div class="d-flex align-items-center">
                                              
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
                                            </div>
                                        </td>
                                        
                                        <!-- Checkout ID -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                               
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
                                            </div>
                                        </td>
                                        
                                        <!-- Checkout Key -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                          
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
                                            </div>
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
                                            
                                                    <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 copy-btn"
                                                            data-id="{{ $paymentLink->id }}"
                                                            title="{{ __('dashboard.view_and_copy') }}">
                                                        <i class="fa fa-copy"></i>
                                                    </button>

                                                    @if($paymentLink->customer && $paymentLink->customer->email)
                                                        <button class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1 resend-email-btn"
                                                                data-id="{{ $paymentLink->id }}"
                                                                data-customer-name="{{ $paymentLink->customer->name ?? __('dashboard.not_specified') }}"
                                                                title="{{ __('dashboard.resend_email') }}">
                                                            <i class="fa fa-envelope"></i>
                                                        </button>
                                                    @endif
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

    // Copy to clipboard function
    async function copyToClipboard(text) {
        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(text);
                return true;
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = text;
                tempInput.style.position = 'fixed';
                tempInput.style.left = '-9999px';
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                const successful = document.execCommand('copy');
                document.body.removeChild(tempInput);
                return successful;
            }
        } catch (err) {
            console.error('Copy failed:', err);
            return false;
        }
    }

    // Generate QR Code
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
            document.getElementById(containerId).innerHTML = '<p class="text-danger">خطأ في إنشاء رمز QR</p>';
            return false;
        }
    }

    // Fetch API helper
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
            return { success: false, message: 'Network error' };
        }
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
                    showToast(response.message || 'فشل في تحميل QR Code', 'error');
                }
            } catch (error) {
                showToast('حدث خطأ في تحميل QR Code', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = '<i class="fa fa-qrcode"></i>';
            }
        }
    });

    // Handle Copy button clicks
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.copy-btn')) {
            const button = e.target.closest('.copy-btn');
            const id = button.getAttribute('data-id');
            
            try {
                const response = await fetchAPI(`/payment-links/${id}/copy`);
                
                if (response.success) {
                    const success = await copyToClipboard(response.url);
                    showToast(success ? '{{ __('dashboard.payment_link_copied') }}' : 'فشل في النسخ', success ? 'success' : 'error');
                } else {
                    showToast(response.message || 'فشل في الحصول على الرابط', 'error');
                }
            } catch (error) {
                showToast('حدث خطأ في نسخ الرابط', 'error');
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
            if (!confirm('{{ __('dashboard.confirm_resend_email') }} ' + customerName + '?')) {
                return;
            }
            
            button.disabled = true;
            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            
            try {
                const response = await fetchAPI(`/payment-links/${id}/resend-email`, {
                    method: 'POST'
                });
                
                if (response.success) {
                    showToast(response.message || '{{ __('dashboard.payment_link_email_resent_success') }}', 'success');
                } else {
                    showToast(response.message || '{{ __('dashboard.payment_link_email_resent_error') }}', 'error');
                }
            } catch (error) {
                showToast('{{ __('dashboard.payment_link_email_resent_error') }}', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = '<i class="fa fa-envelope"></i>';
            }
        }
    });

    // Download QR Code
    document.getElementById('downloadQrBtn').addEventListener('click', function() {
        const button = this;
        const originalText = button.innerHTML;
        
        if (!currentQrCodeDataURL) {
            showToast('فشل في تحضير QR Code للتحميل', 'error');
            return;
        }
        
        try {
            button.disabled = true;
            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> جاري التحميل...';
            
            const downloadLink = document.createElement('a');
            downloadLink.href = currentQrCodeDataURL;
            downloadLink.download = 'payment-qr-code.png';
            
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            
            button.classList.remove('btn-success');
            button.classList.add('btn-info');
            button.innerHTML = '<i class="fa fa-check"></i> تم التحميل!';
            
            showToast('تم تحميل QR Code بنجاح', 'success');
            
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
            showToast('فشل في تحميل QR Code', 'error');
        }
    });
    
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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