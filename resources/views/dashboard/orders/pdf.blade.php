<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@lang('dashboard.Terms_and_Conditions')</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.1/fabric.min.js"></script>
  <style>
    #pdf-canvas {
      border: 1px solid black;
    }

    #signature-pad {
      border: 1px solid black;
      margin-top: 10px;
    }

    body {
      font-family: 'cairo', sans-serif;
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
      border-radius: 50%;
      width: 120px;
      height: 120px;
    }

    .company-details {
      text-align: left;
      display: inline-block;
      line-height: 1.5;
      margin-left: 10px;
      width: 50%;
      float: left;
    }

    .quote {
      font-size: 24px;
      text-align: center;
      font-weight: bold;
      margin: 0;
    }

    .quote-number {
      font-size: 16px;
      color: #666;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 10px;
      border-bottom: 1px solid #aaa;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    .signature-line {
      margin-top: 30px;
      text-align: center;
    }

    .text-signature {
      margin-top: 10px;
      width: 100%;
      padding: 10px;
      font-size: 14px;
    }

    .qr-code {
      text-align: right;
      display: inline-block;
      line-height: 1.5;
      margin-left: 10px;
      width: 20%;
      float: right;
    }

    .order_ditails {
      text-align: center;
    }

    .receipt-title {
      text-align: center;
      font-size: 18px;
      margin: 20px 0;
      font-weight: bold;
    }

    .receipt-details,
    .payment-details {
      width: 100%;
      margin-bottom: 30px;
    }

    hr {
      margin: 40px 0;
    }

    .terms {
      margin-top: 30px;
      border: 1px solid #aaa;
      padding: 20px;
      background-color: #f9f9f9;
    }

    .terms h3 {
      text-align: center;
      margin-bottom: 20px;
    }

    .thanks h3 {
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <div class="reservation-details">
    <div class="header">
      <div class="company-details">
        <img src="{{ Storage::url($termsSittng->logo) }}" alt="Company Logo" class="logo">
        <div>
          <h1>{{$termsSittng->company_name}}</h1>
          <p>Dubai U.A.E</p>
          <p>056674766</p>
          <p>www.funcamp.ae</p>
        </div>
      </div>

      <div class="qr-code">
        <img src="{{asset('images/qr-code.png')}}" alt="QR Code" />
      </div>

    </div>
    <div class="receipt-title">
      @lang('dashboard.Reservation_information')
    </div>

    <div class="receipt-details">
      <table>
        <tr>
          <td>{{ $order->customer->name }}</td>
          <th>: @lang('dashboard.Customer_Name')</th>

        </tr>
        <tr>
          <td>INV-{{$order->id}}</td>
          <th>: @lang('dashboard.Reference_Number')</th>
        </tr>
        <tr>
          <td>{{$termsSittng->created_at}}</td>
          <th>: @lang('dashboard.order_date')</th>
        </tr>
        <tr>
          <td class="amount-received">AED {{ $order->services->first()->name ?? 'N/A' }}</td>
          <th>: @lang('dashboard.service')</th>
        </tr>
      </table>
    </div>
  </div>
  <hr>
  <!-- الشروط والاحكام والسياسة -->
  <div class="terms">
    @lang('dashboard.Terms_desc')
  </div>

  <!-- رسالة شكر  -->
  <div class="thanks">
    <h3>@lang('dashboard.thanks')</h3>
  </div>
  <!-- ختم الشركة -->
  <div style="text-align: center; margin-top: 10px;"> <img src="https://via.placeholder.com/150" alt="ختم الشركة" />
  </div>
  
  <p style="text-align: center; font-weight: bold; margin-top: 5px;">@lang('dashboard.company_stamp')</p>
  <!-- التوقييع -->
  <h3 style="margin-bottom: 120px; font-weight: bold; margin-top: 5px;">@lang('dashboard.Signature')</h3>
  <canvas id="pdf-canvas"></canvas>
  <canvas id="signature-pad" width="400" height="200"></canvas>
  <!-- <button id="save-signature">Save Signature</button> -->
  <script>
    document.getElementById('save-signature').addEventListener('click', function () {
      const dataUrl = signaturePad.toDataURL();
      const link = document.createElement('a');
      link.href = dataUrl;
      link.download = 'signature.png';
      link.click();
    });
  </script>
</body>

</html>