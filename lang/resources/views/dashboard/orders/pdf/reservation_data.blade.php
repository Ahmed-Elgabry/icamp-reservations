<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>بيانات الحجز</title>
    <style>
        @font-face {
            font-family: 'xbriyaz';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/xbriyaz.ttf') }}') format('truetype');
        }

        body {
            font-family: 'xbriyaz', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .company-info {
            text-align: left;
            flex: 1;
        }

        .logo {
            flex: 1;
            text-align: center;
        }

        .logo img {
            max-height: 80px;
        }

        .qr-code {
            flex: 1;
            text-align: right;
        }

        .qr-code img {
            width: 80px;
            height: 80px;
        }

        .section {
            margin-bottom: 25px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border-left: 5px solid #6a11cb;
        }

        .section-title {
            color: #6a11cb;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            border-top: 2px dashed #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .thank-you {
            font-size: 18px;
            color: #6a11cb;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
            padding: 15px;
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            border-radius: 8px;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icon {
            width: 30px;
            height: 30px;
        }

        .seal {
            width: 80px;
            height: 80px;
        }

        .export-date {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header Section -->
    <div class="header">
        <div class="company-info">
            <div>{{ $termsSittng->company_name }}</div>
            <div>E.A.U Dubai</div>
            <div>056674766</div>
            <div>www.funcamp.ae</div>
        </div>

        <div class="logo">
            <img class="seal" src="{{ public_path('imgs/funcamp_logo.jpg') }}" alt="Funcamp Logo">
            <P>بيانات الحجز</P>
        </div>

        <div class="qr-code">
            <img src="{{ $qrCodeFullPath }}" alt="QR Code">
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="section">
        <div class="section-title">تفاصيل الحجز</div>
        <div class="details-grid">
            <div class="detail-label">رقم الحجز:</div>
            <div>{{ $order->id }}</div>

            <div class="detail-label">تاريخ الحجز:</div>
            <div>{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('Y-m-d') : 'غير محدد' }}</div>

            <div class="detail-label">اسم العميل:</div>
            <div>{{ $order->customer->name ?? 'غير محدد' }}</div>

            <div class="detail-label">رقم الهاتف:</div>
            <div>{{ $order->customer->phone ?? 'غير محدد' }}</div>
        </div>
    </div>

    <!-- Terms and Conditions -->
    <div class="section">
        <div class="section-title">الشروط والأحكام</div>
        <div>{!! $termsSittng->terms !!}</div>
    </div>

    <!-- Additional Notes -->
    @if(!empty($order->additional_notes) && $order->order_data)
        @php
            $additionalData = json_decode($order->additional_notes, true);
        @endphp
        @if(isset($additionalData['notes']) && $additionalData['order_data'])
            <div class="section">
                <div class="section-title">ملاحظات إضافية</div>
                <div>{!! $additionalData['notes'] !!}</div>
            </div>
        @endif
    @endif

    <!-- Thank You Message -->
    <div class="thank-you">
        شكرًا لاختياركم لنا ونتمنى استقبالكُم مجددًا
    </div>

    <!-- Footer -->
    <div class="footer">
{{--        <div class="social-icons">--}}
{{--            <img src="{{ public_path('imgs/whatsapp.png') }}" alt="WhatsApp" class="social-icon">--}}
{{--            <img src="{{ public_path('imgs/instagram.png') }}" alt="Instagram" class="social-icon">--}}
{{--        </div>--}}

        <div class="export-date">
            تم إنشاء هذا المستند في: {{ now()->format('Y-m-d H:i') }}
        </div>

        <div class="seal-container">
            <img src="{{ public_path('imgs/funcamp_seal.jpg') }}" alt="Company Seal" class="seal">
        </div>
    </div>
</div>
</body>
</html>
