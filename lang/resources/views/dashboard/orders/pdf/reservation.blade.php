<!DOCTYPE html>
<html dir="@lang('dashboard.direction')" lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@lang('dashboard.Reservation_information')</title>
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

        .qr-code {
            text-align: right;
            float: right;
            width: 20%;
        }

        .qr-code img {
            width: 80px;
            height: 80px;
            border: none; /* Remove any border */
            padding: 0; /* Remove padding */
            background: transparent; /* Make background transparent */
            box-shadow: none;
        }

        .section {
            margin-bottom: 20px;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border-left: 5px solid #65391B;
        }

        .section-title {
            color: #65391B;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
        }

        /* Table Styles */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .details-table th {
            background-color: #65391B;
            color: white;
            padding: 12px;
            text-align: center;
            border: 1px solid #65391B;
        }

        .details-table td {
            padding: 12px;
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
            display: block;
            overflow: hidden;
        }

        .thank-you {
            font-size: 18px;
            color: #fff;
            font-weight: bold;
            margin: 20px 0;
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
            margin-bottom: 10px;
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
            margin-bottom: 10px;
        }

        .seal-container {
            text-align: center;
        }

        .seal {
            width: 120px;
            height: auto;
        }

        /* RTL/LTR direction support */
        .rtl-text {
            direction: rtl;
            text-align: right;
        }

        .ltr-text {
            direction: ltr;
            text-align: left;
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
            <P style="@if(app()->getLocale() == 'en') font-size:15px; @else font-size:20px; @endif margin: 0"><b> @lang('dashboard.Reservation_info')</b></P>
        </div>

        <div class="qr-code">
            <img src="{{ $qrCodeFullPath }}" alt="QR Code">
        </div>
    </div>

    <!-- Reservation Details -->
    <div class="section @if(app()->getLocale() == 'en') ltr-text @else rtl-text @endif">
        <div class="section-title">@lang('dashboard.order_details')</div>

        <!-- Table for reservation details -->
        <table class="details-table">
            <thead>
            <tr>
                <th>@lang('dashboard.reservation_number')</th>
                <th>@lang('dashboard.order_date')</th>
                <th>@lang('dashboard.Customer_Name')</th>
                <th>@lang('dashboard.phone')</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('Y-m-d') : __('dashboard.not_available') }}</td>
                <td>{{ $order->customer->name ?? __('dashboard.not_available') }}</td>
                <td>{{ $order->customer->phone ?? __('dashboard.not_available') }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- Terms and Conditions -->
    <div class="section @if(app()->getLocale() == 'en') ltr-text @else rtl-text @endif">
        <div class="section-title">@lang('dashboard.Terms_and_Conditions')</div>
        <div>{!! $termsSittng->commercial_license !!}</div>
    </div>

    <!-- Additional Notes -->
    @if(!empty($order->order_data_notes))
        <div class="section @if(app()->getLocale() == 'en') ltr-text @else rtl-text @endif">
            <div class="section-title">@lang('dashboard.additional_notes')</div>
            <div>{!! $order->order_data_notes !!}</div>
        </div>
    @endif

    <!-- Thank You Message -->
    <div class="thank-you">
        @lang('dashboard.thanks')
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
