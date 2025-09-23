@extends('dashboard.layouts.app')

@section('title', __('dashboard.payment_link_created'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="page-title">{{ __('dashboard.payment_link_created') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}">{{ __('dashboard.home') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('bookings.payment-links.index', ['order_id' => $order->id]) }}">{{ __('dashboard.payment-links') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('dashboard.payment_link_created') }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('bookings.payment-links.create', ['order_id' => $order->id]) }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> {{ __('dashboard.create_another_payment_link') }}
                    </a>
                    <a href="{{ route('bookings.payment-links.index', ['order_id' => $order->id]) }}" class="btn btn-secondary">
                        <i class="fa fa-list"></i> {{ __('dashboard.view_all_payment_links') }}
                    </a>
                </div>
            </div>

            <!-- Success Alert -->
            <div class="alert alert-success">
                <div class="d-flex align-items-center">
                    <i class="fa fa-check-circle me-3" style="font-size: 2rem;"></i>
                    <div>
                        <h4 class="alert-heading">{{ __('dashboard.payment_link_creation_success') }}</h4>
                        <p class="mb-0">{{ __('dashboard.payment_link_has_been_created') }}</p>
                        @if(request('email_sent'))
                        <div class="mt-2">
                            <i class="fa fa-envelope text-success me-2"></i>
                            <small class="text-success">{{ __('dashboard.email_sent_to_customer') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Link Details -->

            <!-- Generated Payment Link -->
            <div class="card mt-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">{{ __('dashboard.generated_payment_link') }}</h5>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> {{ __('dashboard.back_to_order') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text"
                                    class="form-control form-control-lg"
                                    value="{{ $payment_url }}"
                                    id="paymentLinkInput"
                                    readonly>
                                <button class="btn btn-success" type="button" id="copyLinkBtn">
                                    <i class="fa fa-copy"></i> {{ __('dashboard.copy_link') }}
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ $payment_url }}" target="_blank" class="btn btn-primary btn-lg w-100">
                                <i class="fa fa-external-link-alt"></i> {{ __('dashboard.open_payment_link') }}
                            </a>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="text-center mt-4">
                        <div id="qrcode" class="d-inline-block"></div>
                        <p class="mt-2 text-muted">{{ __('dashboard.scan_qr_code_to_pay') }}</p>
                        <button id="downloadQrBtn" class="btn btn-secondary mt-2" disabled>
                            <i class="fa fa-download"></i> {{ __('dashboard.download_qr_code') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                <a href="{{ route('bookings.payment-links.index', ['order_id' => $order->id]) }}" class="btn btn-outline-primary">
                    <i class="fa fa-list"></i> {{ __('dashboard.view_payment_links') }}
                </a>
                <a href="{{ route('bookings.payment-links.create', ['order_id' => $order->id]) }}" class="btn btn-success">
                    <i class="fa fa-plus"></i> {{ __('dashboard.create_new_payment_link') }}
                </a>

                @if($order->customer && $order->customer->email)
                <button class="btn btn-warning resend-email-btn"
                    data-id="{{ $paymentLink->id }}"
                    data-customer-name="{{ $order->customer->name ?? __('dashboard.not_specified') }}">
                    <i class="fa fa-envelope"></i> {{ __('dashboard.resend_email') }}
                </button>
                @endif

                @if($order->customer && $order->customer->phone)
                <button class="btn btn-success resend-whatsapp-btn"
                    data-id="{{ $paymentLink->id }}"
                    data-customer-name="{{ $order->customer->name ?? __('dashboard.not_specified') }}"
                    data-payment-url="{{ $payment_url }}">
                    <i class="fab fa-whatsapp"></i> {{ __('dashboard.resend_whatsapp') }}
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let qrCodeDataURL = null; // Store QR code data URL for download

        // Generate QR Code with payment URL using qrcode-generator library
        try {
            const qr = qrcode(0, 'M'); // Type 0, Error correction level M
            qr.addData("{{ $payment_url }}");
            qr.make();

            // Create the QR code HTML
            const qrCodeHtml = qr.createImgTag(4, 8); // cellSize=4, margin=8
            document.getElementById('qrcode').innerHTML = qrCodeHtml;

            // Generate canvas version for download
            generateQRCodeCanvas("{{ $payment_url }}");

            console.log('QR Code generated successfully!');
            console.log('Payment URL:', "{{ $payment_url }}");
        } catch (error) {
            console.error('QR Code generation error:', error);
            console.log('Trying fallback QR code generation...');

            // Try fallback method using QRCode.js
            try {
                if (typeof QRCode !== 'undefined') {
                    const qrcodeElement = document.getElementById('qrcode');
                    qrcodeElement.innerHTML = '';

                    QRCode.toCanvas(qrcodeElement, "{{ $payment_url }}", {
                        width: 200,
                        margin: 2,
                        color: {
                            dark: '#000000',
                            light: '#FFFFFF'
                        }
                    }, function(error, canvas) {
                        if (error) {
                            console.error('Fallback QR code generation failed:', error);
                            qrcodeElement.innerHTML = '<p class="text-danger">{{ __("dashboard.qr_code_creation_error") }}</p>';
                            document.getElementById('downloadQrBtn').style.display = 'none';
                        } else {
                            console.log('Fallback QR code generated successfully');
                            // Store canvas for download
                            qrCodeDataURL = canvas.toDataURL('image/png');
                            document.getElementById('downloadQrBtn').disabled = false;
                        }
                    });
                } else {
                    throw new Error('Fallback library not available');
                }
            } catch (fallbackError) {
                console.error('Fallback QR code generation also failed:', fallbackError);
                document.getElementById('qrcode').innerHTML = '<p class="text-danger">{{ __("dashboard.qr_code_creation_error") }}</p>';
                document.getElementById('downloadQrBtn').style.display = 'none';
            }
        }

        // Generate QR Code canvas for download
        function generateQRCodeCanvas(url) {
            try {
                console.log('Generating QR code canvas for URL:', url);

                const qr = qrcode(0, 'M');
                qr.addData(url);
                qr.make();

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const modules = qr.getModuleCount();
                const cellSize = 8;
                const margin = 32;
                const size = modules * cellSize + (margin * 2);

                console.log('Canvas size:', size, 'Modules:', modules);

                canvas.width = size;
                canvas.height = size;

                // Fill white background
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, size, size);

                // Draw QR code modules
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

                // Store the data URL for download
                qrCodeDataURL = canvas.toDataURL('image/png');
                console.log('QR Code canvas generated successfully. Data URL length:', qrCodeDataURL.length);

                // Enable download button
                document.getElementById('downloadQrBtn').disabled = false;

            } catch (error) {
                console.error('Canvas QR Code generation error:', error);
                document.getElementById('downloadQrBtn').style.display = 'none';
                qrCodeDataURL = null;
            }
        }

        // Enhanced Copy payment link functionality
        document.getElementById('copyLinkBtn').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            const input = document.getElementById('paymentLinkInput');
            const textToCopy = input.value;

            // Change button appearance while copying
            button.disabled = true;
            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __("dashboard.copying") }}';

            // Select the text in the input field
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            // Try to copy using different methods
            let copySuccess = false;

            // Method 1: Modern clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(function() {
                    copySuccess = true;
                    showSuccessState(button, originalText);
                    showToast('{{ __("dashboard.payment_link_copied_success") }}', 'success');
                }).catch(function(err) {
                    console.error('Clipboard API failed: ', err);
                    // Try fallback method
                    tryFallbackCopy(textToCopy, button, originalText);
                });
            } else {
                // Method 2: execCommand fallback
                tryFallbackCopy(textToCopy, button, originalText);
            }
        });

        // Download QR Code functionality
        document.getElementById('downloadQrBtn').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;

            console.log('Download button clicked. QR Code data URL available:', !!qrCodeDataURL);

            if (!qrCodeDataURL) {
                showToast('{{ __("dashboard.failed_to_prepare_qr_download") }}', 'error');
                console.error('No QR code data URL available for download');
                return;
            }

            try {
                // Change button state
                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __("dashboard.downloading") }}';

                // Create download link
                const downloadLink = document.createElement('a');
                downloadLink.href = qrCodeDataURL;
                downloadLink.download = 'payment-qr-code.png';
                downloadLink.style.display = 'none';

                // Trigger download
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);

                // Show success state
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-success');
                button.innerHTML = '<i class="fa fa-check"></i> {{ __("dashboard.downloaded") }}';

                showToast('{{ __("dashboard.qr_code_downloaded_success") }}', 'success');
                console.log('QR code download initiated successfully');

                // Reset button after 2 seconds
                setTimeout(function() {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-secondary');
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

        // Fallback copy method
        function tryFallbackCopy(text, button, originalText) {
            try {
                // Create temporary input element
                const tempInput = document.createElement('input');
                tempInput.value = text;
                tempInput.style.position = 'fixed';
                tempInput.style.left = '-9999px';
                tempInput.style.top = '-9999px';
                document.body.appendChild(tempInput);

                // Select and copy
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);

                const successful = document.execCommand('copy');
                document.body.removeChild(tempInput);

                if (successful) {
                    showSuccessState(button, originalText);
                    showToast('{{ __("dashboard.payment_link_copied_success") }}', 'success');
                } else {
                    throw new Error('Copy command failed');
                }
            } catch (err) {
                console.error('All copy methods failed: ', err);
                resetButton(button, originalText);
                showToast('{{ __("dashboard.copy_failed_error") }}', 'error');

                // Last resort: prompt user to copy manually
                prompt('{{ __("dashboard.copy_manually_prompt") }}', text);
            }
        }

        // Show success state on copy button
        function showSuccessState(button, originalText) {
            button.classList.remove('btn-success');
            button.classList.add('btn-info');
            button.innerHTML = '<i class="fa fa-check"></i> {{ __("dashboard.copy_success") }}';

            // Reset button after 2 seconds
            setTimeout(function() {
                button.classList.remove('btn-info');
                button.classList.add('btn-success');
                button.disabled = false;
                button.innerHTML = originalText;
            }, 2000);
        }

        // Reset button to original state
        function resetButton(button, originalText) {
            button.disabled = false;
            button.innerHTML = originalText;
        }

        // Enhanced toast notification function
        function showToast(message, type) {
            // Remove any existing toasts first
            const existingToasts = document.querySelectorAll('.custom-toast');
            existingToasts.forEach(toast => toast.remove());

            const toastId = 'toast_' + Date.now();
            const bgColor = type === 'success' ? '#28a745' : '#dc3545';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            // Create toast element
            const toastElement = document.createElement('div');
            toastElement.id = toastId;
            toastElement.className = 'custom-toast';
            toastElement.style.backgroundColor = bgColor;
            toastElement.innerHTML = `
            <i class="fa ${icon} me-2"></i>
            <strong>${message}</strong>
            <button type="button" class="btn-close btn-close-white ms-2" onclick="document.getElementById('${toastId}').remove()"></button>
        `;

            // Add toast to body
            document.body.appendChild(toastElement);

            // Auto-hide toast after 3 seconds
            setTimeout(function() {
                const toast = document.getElementById(toastId);
                if (toast) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        }

        // Add some visual feedback on QR code hover
        const qrcodeElement = document.getElementById('qrcode');

        qrcodeElement.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s ease';
        });

        qrcodeElement.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });

        // Optional: Add click to copy functionality on the QR code
        qrcodeElement.style.cursor = 'pointer';
        qrcodeElement.title = '{{ __("dashboard.click_to_copy_link") }}';
        qrcodeElement.addEventListener('click', function() {
            document.getElementById('copyLinkBtn').click();
        });

        // Handle Resend Email button clicks
        document.addEventListener('click', async function(e) {
            if (e.target.closest('.resend-email-btn')) {
                const button = e.target.closest('.resend-email-btn');
                const id = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-customer-name');

                console.log('Resend email button clicked:', {
                    id,
                    customerName
                });

                // Confirm before sending
                if (!confirm('{{ __('
                        dashboard.confirm_resend_email ') }} ' + customerName + '?')) {
                    return;
                }

                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('
                dashboard.sending ') }}...';

                try {
                    console.log('Sending request to:', `/payment-links/${id}/resend-email`);
                    const response = await fetch(`/payment-links/${id}/resend-email`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                        }
                    });

                    console.log('Response status:', response.status);
                    const result = await response.json();
                    console.log('Response result:', result);

                    if (result.success) {
                        showToast(result.message || '{{ __('
                            dashboard.payment_link_email_resent_success ') }}', 'success');
                    } else {
                        showToast(result.message || '{{ __('
                            dashboard.payment_link_email_resent_error ') }}', 'error');
                    }
                } catch (error) {
                    console.error('Error in resend email:', error);
                    showToast('{{ __('
                        dashboard.payment_link_email_resent_error ') }}', 'error');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fa fa-envelope"></i> {{ __('
                    dashboard.resend_email ') }}';
                }
            }

            // Handle Resend WhatsApp button clicks
            if (e.target.closest('.resend-whatsapp-btn')) {
                const button = e.target.closest('.resend-whatsapp-btn');
                const id = button.getAttribute('data-id');
                const customerName = button.getAttribute('data-customer-name');
                const paymentUrl = button.getAttribute('data-payment-url');

                console.log('Resend WhatsApp button clicked:', {
                    id,
                    customerName,
                    paymentUrl
                });

                // Confirm before sending
                if (!confirm('{{ __('
                        dashboard.confirm_resend_whatsapp ') }} ' + customerName + '?')) {
                    return;
                }

                button.disabled = true;
                button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ __('
                dashboard.sending ') }}...';

                try {
                    console.log('Sending request to:', `/payment-links/${id}/resend-whatsapp`);
                    const response = await fetch(`/payment-links/${id}/resend-whatsapp`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            payment_url: paymentUrl
                        })
                    });

                    console.log('Response status:', response.status);
                    const result = await response.json();
                    console.log('Response result:', result);

                    if (result.success) {
                        showToast(result.message || '{{ __('
                            dashboard.payment_link_whatsapp_resent_success ') }}', 'success');
                    } else {
                        showToast(result.message || '{{ __('
                            dashboard.payment_link_whatsapp_resent_error ') }}', 'error');
                    }
                } catch (error) {
                    console.error('Error in resend WhatsApp:', error);
                    showToast('{{ __('
                        dashboard.payment_link_whatsapp_resent_error ') }}', 'error');
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fab fa-whatsapp"></i> {{ __('
                    dashboard.resend_whatsapp ') }}';
                }
            }
        });

        // Show toast notification function
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
    });
</script>

<style>
    /* Enhanced QR Code styling */
    #qrcode {
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
        border: 2px solid #f8f9fa;
    }

    #qrcode img {
        border-radius: 8px;
        max-width: 200px;
        height: auto;
    }

    /* Copy button enhancements */
    #copyLinkBtn {
        transition: all 0.3s ease;
        border-radius: 0 8px 8px 0;
    }

    #copyLinkBtn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Input field styling */
    #paymentLinkInput {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        border-radius: 8px 0 0 8px;
        border-right: none;
    }

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

    /* Download button styling */
    #downloadQrBtn {
        transition: all 0.3s ease;
        border-radius: 8px;
        min-width: 150px;
    }

    #downloadQrBtn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    #downloadQrBtn:disabled {
        transform: none;
        box-shadow: none;
        opacity: 0.6;
        cursor: not-allowed;
    }

    #downloadQrBtn:disabled:hover {
        transform: none;
        box-shadow: none;
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
</style>

@endsection
