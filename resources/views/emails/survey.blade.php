<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقييم نوع المخيم | Service Evaluation</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .content {
            padding: 20px;
        }

        .order-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            background-color: #f2f2f2;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>تقييم نوع المخيم | Service Evaluation</h1>
        </div>

        <!-- Body -->
        <div class="content">

            <!-- Arabic -->
            <p class="section-title">🇸🇦 بالعربية:</p>
            <p>عزيزي العميل،</p>
            <p>شكراً لاستخدامك خدماتنا. نرجو منك تقييم تجربتك من خلال الاستبيان التالي:</p>

            <!-- English -->
            <p class="section-title">🇬🇧 In English:</p>
            <p>Dear Customer,</p>
            <p>Thank you for using our services. Please evaluate your experience through the following survey:</p>

            <!-- Order info -->
            <div class="order-info">
                <p><strong>رقم الطلب / Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>تاريخ الطلب / Order Date:</strong> {{ $order->date }}</p>
                <p><strong>اسم العميل / Customer Name:</strong> {{ $order->customer->name }}</p>
            </div>

            <!-- Button -->
            <div style="text-align: center;">
                <a href="{{ $surveyUrl }}" class="btn">
                    اضغط هنا للتقييم / Click here to evaluate
                </a>
            </div>

            <!-- Footer -->
            <p>مع تحياتنا، فريق العمل | Best regards, The Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة | All rights reserved</p>
        </div>
    </div>
</body>

</html>