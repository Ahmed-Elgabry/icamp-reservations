<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> عرض السعر</title>
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
            /*margin-bottom: 25px;*/
            padding-left: 20px;
            padding-right: 20px;
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
            padding: 5px;
            text-align: center;
            border: 1px solid #65391B;
        }

        .details-table td {
            padding: 10px;
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
            <P style="font-size: 20px;margin: 0"><b> عرض السعر</b></P>
        </div>

        <div class="show_price_info">
            <strong>عرض سعر رقم:</strong><br>
            {{ 'QUO-' . $order->order_number }}<br>
            <strong>تاريخ انتهاء عرض السعر:</strong><br>
            {{ \Carbon\Carbon::parse($order->expired_price_offer)->format('d-m-Y') ?? date('Y/m/d', strtotime('+1 day')) }}
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="section">
        <!-- Table for reservation details -->
        <table class="details-table">
            <thead>
            <tr>
                <th>اسم العميل</th>
                <th>رقم الهاتف</th>
                <th>البريد الاكتروني</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $order->customer->name ?? 'غير محدد' }}</td>
                <td>{{ $order->customer->phone ?? 'غير محدد' }}</td>
                <td>{{ $order->customer->email ?? 'غير محدد' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    @php $totalPrice = 0 @endphp
    @if(!empty($order->services))
        <div class="section">
            <!-- Table for reservation details -->
            <table class="details-table">
                <thead>
                <tr>
                    <th>م</th>
                    <th>الخدمة</th>
                    <th>العدد</th>
                    <th>المبلغ / درهم</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($order->services as $index => $service)
                    @php $totalPrice += $service->price; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>1</td>
                        <td>{{ $service->price }} درهم</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if($order->addons->filter(fn($addon) => $addon->pivot->verified == 1)->isNotEmpty())
        <div class="section">
            <!-- Table for reservation details -->
            <table class="details-table">
                <thead>
                <tr>
                    <th>م</th>
                    <th>الاضافات</th>
                    <th>السعر</th>
                    <th>العدد</th>
                    <th>الإجمالى / درهم</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($order->addons as $index => $addon)
                    @if($addon->pivot->verified == 1)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $addon->name }}</td>
                            <td>{{ $addon->pivot->price }}</td>
                            <td>{{ $addon->pivot->count }}</td>
                            <td>{{ $addon->pivot->price * $addon->pivot->count }} درهم</td>
                        </tr>
                        @php $totalPrice += $addon->pivot->price * $addon->pivot->count; @endphp
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if(!empty($order->deposit) || !empty($order->insurance_amount))
        <div class="section">
            <!-- Table for reservation details -->
            <table class="details-table">
                <thead>
                <tr>
                    @if(!empty($order->deposit))
                        <th>العربون</th>
                    @endif
                    @if(!empty($order->insurance_amount))
                        <th>التامين</th>
                    @endif
                    <th>المجموع الكلي</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    @if(!empty($order->deposit))
                        <td>{{ $order->deposit }}</td>
                    @endif
                    @if(!empty($order->insurance_amount))
                        <td>{{ $order->insurance_amount }}</td>
                        @php $totalPrice += $order->insurance_amount @endphp
                    @endif
                    <td>{{ $totalPrice }} درهم</td>
                </tr>
                </tbody>
            </table>
        </div>
    @endif
    <div class="section">
        <div class="section-title">ملاحظات</div>
        <ul>
            <li>لضمان تأكيد الحجز،يتم دفع العربون المتفق عليه في عرض السعر،ويعتبر جزء من قيمة مبلغ المخيم.</li>
            <li>يتم استكمال سداد باقي الخدمة عند استلام المخيم.</li>
            <li>بتم دفع مبلغ التأمين المتفق عليه في عرض السعر عند استلام المخيم،ويتم رده خلال ٢٤ ساعة،بعد التأكد من سلامة مستلزمات المخيم.</li>
            <li>تطبق الشروط والأحكام.</li>
        </ul>
    </div>

    <!-- Additional Notes -->
    @if(!empty($order->show_price_notes))
        <div class="section">
            <div class="section-title">ملاحظات إضافية</div>
            <div>{!! $order->show_price_notes !!}</div>
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
