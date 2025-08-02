<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>عرض سعر</title>
<style>
  body {
    font-family: 'Cairo', sans-serif;
    margin: 0;
    padding: 20px;
    font-size: 14px;
    direction: rtl;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }

  .company-info{
    padding: 10px;
    width: 30%;
    float: left;
    text-align: left;
  }
  .quote-info {
    padding: 10px;
    width: 190px;
    float: right;
  }
  .customer-info{
    padding: 10px;
    width: 100%;
  }

  .title {
    text-align: center;
    font-size: 24px;
    font-weight: bold;
    margin: 20px 0;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th, td {
    padding: 10px;
    border: 1px solid #000;
    text-align: center;
  }

  th {
    background-color: #f0f0f0;
  }

  .total {
    color: red;
    font-weight: bold;
    margin: 20px;
    text-align: right;
  }

  .notes {
    margin-top: 20px;
    border: 1px solid #000;
    padding: 10px;
  }

  .company-stamp {
    text-align: center;
    margin-top: 40px;
  }
  
  .company-stamp div {
    /* border: 1px solid #000; */
    width: 150px;
    height: 150px;
    margin: 0 auto;
  }
</style>
</head>
<body>

<div class="header">
  <div class="company-info">
    <p>Funcamp.ae</p>
    <p>Dubai U.A.E</p>
    <p>0566674766</p>
    <a href="www.funcamp.ae">www.funcamp.ae</a>
  </div>
  
  <div class="quote-info">
    <p>عرض سعر رقم: {{ $order->id }}</p>
    <p>تاريخ الإصدار: {{ date('Y/m/d') }}</p>
    <p>تاريخ انتهاء عرض السعر: {{ date('Y/m/d', strtotime('+1 day')) }}</p>
  </div>
</div>

<div class="title">عرض سعر</div>

<div class="customer-info">
  <p>اسم العميل: <strong>{{ $order->customer->name }}</strong></p>
  <p>رقم الهاتف المتحرك: <strong>{{ $order->customer->phone }}</strong></p>
  <p>البريد الإلكتروني: <strong>{{ $order->customer->email }}</strong></p>
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
      @php $totalPrice += $service->pivot->price; @endphp
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $service->name }}</td>
        <td>1</td>
        <td>{{ $service->pivot->price }} درهم</td>
      </tr>
    @endforeach
  </tbody>
</table>

<p class="total">المجموع الكلي: {{ $totalPrice }} درهم</p>

<div class="notes">
  <p><strong>ملاحظات:</strong></p>
  <ul>
    <li>يتم دفع عربون وقدره (500 درهم) لضمان تأكيد الحجز ويعتبر جزء من قيمة مبلغ المخيم.</li>
    <li>يتم دفع مبلغ التأمين عند استلام المخيم، ويتم رده خلال 24 ساعة.</li>
    <li>تطبق الشروط والأحكام.</li>
  </ul>
</div>

<div class="company-stamp">
  <p>ختم الشركة</p>
</div>

</body>
</html>
