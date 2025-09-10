<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Receipt</title>
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      padding: 20px;
      font-size: 14px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    .logo {
      border-radius: 50%;
      width: 120px;
      height: 120px;
    }
    .company-details {
      text-align: left;
      display:inline-block;
      line-height: 1.5;
      margin-left:10px
    }
    
    .company-details p 
     {
        margin:2px 0;
     }

    .company-details h1 {
      font-size: 24px;
      margin: 0;
      text-transform: uppercase;
      font-weight: bold;
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
    .receipt-details table,
    .payment-details table {
      width: 100%;
      border-collapse: collapse;
    }
    .receipt-details th,
    .payment-details th,
    .receipt-details td,
    .payment-details td {
      text-align: left;
      padding: 8px;
      border-bottom: 1px solid #ddd;
    }
    .amount-received {
      background-color: #D9EAD3;
      color: #274E13;
      text-align: center;
      padding: 8px;
      float: right;
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="logo">
    <div class="company-details">
      <h1>funcamp</h1>
      <p>Dubai U.A.E</p>
      <p>056674766</p>
      <p>info@funcamp.ae</p>
      <p>www.funcamp.ae</p>
    </div>
  </div>
  <hr>
  <div class="receipt-title">
    PAYMENT RECEIPT
  </div>
  <div class="receipt-details">
    <table>
      <tr>
        <th>Payment Date</th>
        <td>{{$payment->created_at}}</td>
        <th>Amount Received</th>
        <td class="amount-received">AED{{$payment->price}}</td>
      </tr>
      <tr>
        <th>Reference Number</th>
        <td>INV-{{$payment->id}}</td>
      </tr>
      <tr>
        <th>Payment Mode</th>
        <td>{{$payment->method_type}}</td>
      </tr>
    </table>
  </div>
  <div class="payment-details">
    <p>Bill To</p>
    <p>{{$payment->order->customer->name}}</p>
    <table>
      <tr>
        <th colspan="4">Payment for</th>
      </tr>
      <tr>
        <th>Invoice Number</th>
        <th>Invoice Amount</th>
        <th>Invoice Date</th>
        <th>Payment Amount</th>
    </tr>
    <tr>
        <td>INV-{{$payment->id}}</td>
        <td>AED{{$payment->price}}</td>
        <td>{{$payment->created_at}}</td>
        <td>AED{{$payment->price}}</td>
      </tr>
    </table>
  </div>
</body>
</html>
