<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>فاتورة ضريبية</title>
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      padding: 20px;
      font-size: 14px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .header h1 {
      margin: 2px 0;
    }

    .header p {
      margin: 2px 0;
    }

    .logo {
      width: 100px;
      height: auto;
    }

    .company-details {
      text-align: left;
      display: inline-block;
      line-height: 1.5;
      /* border: #000 1px solid; */
      float: left;
      width: 40%;
    }

    .invoice-title {
      font-size: 24px;
      text-align: center;
      font-weight: bold;
      width: 100%;
    }

    .quote-number {
      font-size: 16px;
      color: #666;
    }

    .details {
      margin-top: 30px;
      width: 190px;
      float: right;
      /* border: #000 1px solid; */
    }

    .detailss div {
      font-size: 12px;
      line-height: 1.5;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table,
    th,
    td {
      border: 1px solid black;
      text-align: center;
    }

    th,
    td {
      padding: 10px;
    }

    th {
      background-color: #f0f0f0;
    }

    .totals {
      text-align: left;
      margin-top: 20px;
      font-size: 16px;
      font-weight: bold;
    }

    .totals .label {
      font-weight: bold;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      font-weight: bold;
    }

    .footer p {
      margin: 5px 0;
    }

    .stamp {
      width: 200px;
      height: 100px;
      border: 1px solid black;
      text-align: center;
      margin: 20px auto;
      line-height: 100px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="invoice-container">

    <div class="header">
      <div class="company-details">
        <h1>Funcamp.ae</h1>
        <p>Dubai U.A.E</p>
        <p>056674766</p>
        <p>info@funcamp.ae</p>
        <p>www.funcamp.ae</p>
      </div>
      <div class="details">
        <p>فاتورة رقم: #INV-{{$order->id}}</p>
        <p>تاريخ الإصدار: {{ date('Y/m/d') }}</p>
      </div>
      <div class="invoice-title">
        فاتورة ضريبية
      </div>
    </div>

    <div class="detailss">
      <div>
        <p>اسم العميل: {{ $order->customer->name }}</p>
        <p>رقم الهاتف: {{ $order->customer->phone }}</p>
        <p>البريد الإلكتروني: {{ $order->customer->email }}</p>
        <p>رقم الحجز: {{ $order->id }}</p>
        <p>تاريخ الحجز: {{ date('Y/m/d', strtotime($order->date)) }}</p>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>م</th>
          <th>الخدمة والوصف</th>
          <th>العدد</th>
          <th>المبلغ</th>
        </tr>
      </thead>
      <tbody>
        @php $totalPrice = 0 @endphp
        @foreach ($order->services as $index => $service)
      @php  $totalPrice += $service->pivot->price; @endphp
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $service->name }}</td>
        <td>1</td>
        <td>{{ $service->pivot->price }} درهم</td>
      </tr>
    @endforeach
      </tbody>
    </table>

    <div class="totals">
      <p>المجموع الكلي: {{ $totalPrice }} درهم</p>
    </div>

    <div class="footer">
      <p>شكرًا لاختياركم لنا ونتمنى استقبالكُم مجددًا</p>
    </div>

    <div class="stamp">
      ختم الشركة
    </div>
  </div>
</body>

</html>