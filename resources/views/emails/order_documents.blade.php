<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Cairo', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #B98220 0%, #6A3D1C 100%);
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .email-body {
            padding: 30px;
        }
        .email-footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #B98220 0%, #6A3D1C 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .documents-list {
            margin: 20px 0;
        }
        .document-item {
            padding: 10px;
            background-color: #f8f9fa;
            margin-bottom: 10px;
            border-left: 4px solid #65391B;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Funcamp</h1>
    </div>

    <div class="email-body">
        <h2>عزيزي العميل،</h2>
        <p>مرفق لك المستندات التالية:</p>

        <div class="documents-list">
            @if(in_array('show_price', $documents))
                <div class="document-item">عرض السعر</div>
            @endif

            @if(in_array('reservation_data', $documents))
                <div class="document-item">بيانات الحجز</div>
            @endif

            @if(in_array('invoice', $documents))
                <div class="document-item">الفاتورة</div>
            @endif

            @if(in_array('receipt', $documents))
                <div class="document-item">إيصال القبض</div>
            @endif

            <!-- Add receipt types -->
            @foreach($documents as $document)
                @if(str_starts_with($document, 'addon_receipt_'))
                    <div class="document-item">إيصال إضافة #{{ str_replace('addon_receipt_', '', $document) }}</div>
                @elseif(str_starts_with($document, 'payment_receipt_'))
                    <div class="document-item">إيصال دفع #{{ str_replace('payment_receipt_', '', $document) }}</div>
                @elseif(str_starts_with($document, 'warehouse_receipt_'))
                    <div class="document-item">إيصال مخزن #{{ str_replace('warehouse_receipt_', '', $document) }}</div>
                @endif
            @endforeach
        </div>

        <p>شكرًا لكم،،</p>
        <p>مع تحيات AE.FUNCAMP</p>

        <p>
            <strong>الموقع الإلكتروني:</strong>
            <a href="https://www.funcamp.ae">WWW.FUNCAMP.AE</a>
        </p>
    </div>

    <div class="email-footer">
        <p>© {{ date('Y') }} Funcamp. جميع الحقوق محفوظة.</p>
    </div>
</div>
</body>
</html>
