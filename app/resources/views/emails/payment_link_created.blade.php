<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('dashboard.payment_link_created') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .content {
            padding: 30px;
        }
        .amount-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        .currency {
            color: #6c757d;
            font-size: 14px;
        }
        .payment-button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .payment-button:hover {
            background: #218838;
        }
        .details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #6c757d;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .expires-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .content, .header {
                padding: 20px;
            }
            .amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('dashboard.payment_link_created') }}</h1>
        </div>
        
        <div class="content">
            <p>{{ __('dashboard.hello') }} {{ $customerName }},</p>
            
            <p>{{ __('dashboard.payment_link_email_message') }}</p>
            
            <div class="amount-box">
                <div class="amount">{{ number_format($amount, 2) }}</div>
                <div class="currency">{{ __('dashboard.currency_aed') }}</div>
            </div>
            
            @if($description)
                <div class="details">
                    <div class="detail-row">
                        <span class="detail-label">{{ __('dashboard.payment_link_description') }}:</span>
                        <span class="detail-value">{{ $description }}</span>
                    </div>
                </div>
            @endif
            
            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">{{ __('dashboard.order_id') }}:</span>
                    <span class="detail-value">#{{ $orderId }}</span>
                </div>
                @if($expiresAt)
                    <div class="detail-row">
                        <span class="detail-label">{{ __('dashboard.payment_link_expires_at') }}:</span>
                        <span class="detail-value">{{ $expiresAt->format('Y-m-d H:i') }}</span>
                    </div>
                @endif
            </div>
            
            @if($expiresAt)
                <div class="expires-warning">
                    <strong>{{ __('dashboard.payment_link_expires_warning') }}</strong>
                </div>
            @endif
            
            <div style="text-align: center;">
                <a href="{{ $paymentUrl }}" class="payment-button">
                    {{ __('dashboard.pay_now') }}
                </a>
            </div>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                {{ __('dashboard.payment_link_email_footer') }}
            </p>
        </div>
        
        <div class="footer">
            <p>{{ __('dashboard.app_name') }} - {{ __('dashboard.payment_link_email_support') }}</p>
        </div>
    </div>
</body>
</html>
