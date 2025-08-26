<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>إيصال قبض</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        .header {
            display: block;
            padding: 20px;
            background: linear-gradient(135deg, #B98220 0%, #6A3D1C 100%);
            color: white;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .company-info {
            text-align: left;
            float: left;
            width: 40%;
        }

        .logo {
            text-align: center;
            float: left;
            width: 20%;
        }

        .logo img {
            max-height: 80px;
        }

        .show_price_info {
            text-align: right;
            float: right;
            width: 30%;
        }

        .section {
            margin-bottom: 20px;
            padding: 15px 20px;
            /*padding-right: 20px;*/
            border-radius: 8px;
            background-color: #f8f9fa;
            border-left: 5px solid #65391B;
        }

        .section-title {
            color: #65391B;
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
        }

        /* Table Styles */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .details-table th {
            background-color: #65391B;
            color: white;
            padding: 10px;
            text-align: center;
            border: 1px solid #65391B;
        }

        .details-table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: white;
        }

        .details-table tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        .footer {
            position: relative;
            text-align: center;
            /*margin-top: 30px;*/
            /*padding: 20px;*/
            border-top: 2px dashed #ddd;
            display: block;
            overflow: hidden;
            /*min-height: 100px;*/
        }

        .thank-you {
            font-size: 18px;
            color: #fff;
            font-weight: bold;
            /*margin: 20px 0;*/
            text-align: center;
            padding: 5px 15px;
            background: linear-gradient(135deg, #B98220 0%, #6A3D1C 100%);
            border-radius: 8px;
        }

        .social-icons {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 30%;
        }

        .left-social {
            left: 0;
            text-align: left;
        }

        .right-social {
            right: 0;
            text-align: right;
        }

        .social-item {
            display: inline-block;
            vertical-align: middle;
        }

        .social-icon {
            width: 30px;
            height: 30px;
            vertical-align: middle;
            margin: 0 10px;
        }

        .social-number {
            font-size: 14px;
            vertical-align: middle;
            color: #333;
        }

        .seal {
            width: 80px;
            height: 80px;
        }

        .middle-section {
            width: 40%;
            margin: 0 auto;
            text-align: center;
        }

        .export-date {
            text-align: center;
            color: #777;
            /*margin-bottom: 10px;*/
        }

        .seal-container {
            text-align: center;
        }

        .seal {
            width: 80px;
            height: 80px;
        }

        /* Clearfix for floated elements */
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .footer-item {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .footer-item:first-child {
            justify-content: flex-start;
        }

        .footer-item:nth-child(2) {
            justify-content: center;
            flex-direction: column;
        }

        .footer-item:last-child {
            justify-content: flex-end;
        }

        .social-icon {
            width: 30px;
            height: 30px;
            margin: 0 10px;
        }

        .social-number {
            font-size: 14px;
            color: #333;
        }

        .export-date {
            text-align: center;
            color: #392d2d;
            /*margin-bottom: 10px;*/
        }

        .seal-container {
            text-align: center;
        }

        .seal {
            width: 120px;
            height: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header Section -->
    <div class="header clearfix">
        <div class="company-info">
            <div>{{ $termsSittng->company_name }}</div>
            <div>U.A.E Dubai</div>
            <div>0566674766</div>
            <div>www.funcamp.ae</div>
        </div>

        <div class="logo">
            <img class="seal" src="{{ public_path('imgs/funcamp_remove.png') }}" alt="Funcamp Logo">
            <P style="font-size: 20px;margin: 0"><b>إيصال قبض</b></P>
        </div>

        <div class="show_price_info">
            <strong> إيصال رقم:</strong><br>
            {{ 'REC-' . $order->order_number }}<br>
            <strong>تاريخ ووقت الإصدار:</strong><br>
            {{ now()->format('d-m-Y H:i') }}
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="section">
        <!-- Table for reservation details -->
        <table class="details-table">
            <thead>
            <tr>
                <th>رقم الحجز</th>
                <th>تاريخ الحجز</th>
                <th>اسم العميل</th>
                <th>رقم الهاتف</th>
                <th>البريد الاكتروني</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('Y-m-d') : 'غير محدد' }}</td>
                <td>{{ $order->customer->name ?? 'غير محدد' }}</td>
                <td>{{ $order->customer->phone ?? 'غير محدد' }}</td>
                <td>{{ $order->customer->email ?? 'غير محدد' }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <!-- Table for reservation details -->
        <table class="details-table">
            <thead>
            <tr>
                <th>الأسم</th>
                <th>المبلغ المستلم</th>
                <th>تاريخ السداد</th>
                <th>طريقة السداد</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $warehouseItem->stock->name }}</td>
                    <td>{{ $warehouseItem->total_price }}</td>
                    <td>{{ $warehouseItem->updated_at }}</td>
                    <td>@lang('dashboard.' . $warehouseItem->payment_method)</td>
                </tr>
            </tbody>
        </table>
    </div>
    @php $totalPrice = 0 @endphp
    @foreach ($order->services as $service)
        @php $totalPrice += $service->price; @endphp
    @endforeach
    @foreach ($order->addons as $addon)
        @if($addon->pivot->verified == 1)
            @php $totalPrice += $addon->pivot->price * $addon->pivot->count; @endphp
        @endif
    @endforeach
    @php $totalPrice += $order->insurance_amount @endphp

    @php $totalPaid = 0; @endphp
    @if(!empty($order->payments))
        @foreach($order->payments as $payment)
            @if($payment->verified == 1)
                @php $totalPaid += $payment->price @endphp
            @endif
        @endforeach
    @endif

    <div class="section">
        <!-- Table for reservation details -->
        <table class="details-table">
            <thead>
            <tr>
                <th>رقم الفاتورة</th>
                <th>تاريخ الفاتورة</th>
                <th>مبلغ الفاتورة</th>
                <th>المبلغ المدفوع</th>
                <th>المبلغ المتبقى</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{'INV-'. $order->order_number}}</td>
                    <td>{{ $order->date }}</td>
                    <td>{{ $totalPrice }} درهم </td>
                    <td>{{ $totalPaid }} درهم </td>
                    <td>{{ $totalPrice - $totalPaid }} درهم </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Additional Notes -->
    @if(!empty($order->receipt_notes))
        <div class="section">
            <div class="section-title">ملاحظات إضافية</div>
            <div>{!! $order->receipt_notes !!}</div>
        </div>
    @endif

    <!-- Thank You Message -->
    <div class="thank-you">
        شكرًا لاختياركم لنا ونتمنى استقبالكُم مجددًا
    </div>

    <!-- Footer -->
    <div class="footer" style="display: block; padding: 20px; border-radius: 10px; margin-top: 30px; overflow: hidden;">
        <div style="text-align: left; float: left; width: 30%;">
            <span style="font-size: 14px; vertical-align: middle; display: inline-block;">
                0566674766
            </span>
            <img src="{{ public_path('imgs/whatsapp.png') }}"
                 alt="WhatsApp"
                 style="width: 22px; height: 22px; vertical-align: middle; margin-right: 4px;">
        </div>


        <div style="text-align: center; float: left; width: 40%;">
            <div>
                <img src="{{ public_path('imgs/funcamp_seal.jpg') }}" alt="Company Seal" style="width: 120px; height: auto;">
            </div>
            <div style="margin-bottom: 10px;">
                {{ now()->format('Y-m-d H:i') }}
            </div>
        </div>

        <div style="text-align: right; float: right; width: 30%;">
            <span style="font-size: 14px; vertical-align: middle; display: inline-block;">
                funcamp.ae@
            </span>
            <img src="{{ public_path('imgs/instagram.png') }}"
                 alt="Instagram"
                 style="width: 22px; height: 22px; vertical-align: middle;">
        </div>
    </div>
</div>
</body>
</html>
