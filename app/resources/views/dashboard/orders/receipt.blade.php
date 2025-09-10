<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quote Receipt</title>
<style>
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
        margin:2px 0
    }
    .header p {
        margin:2px 0
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
      margin-left:10px;
      width: 50%;
      float:left;
    }

  .quote {
    font-size: 24px;
    float: right;
    width: 45%;
    text-align:right;
    font-weight: bold;
  }

  .quote-number {
    font-size: 16px;
    color: #666;
  }
  .details {
    display: flex;
    text-align:left;
    justify-content: space-between;
    margin-top: 30px;
  }
  .details div {
    font-size:12px;
    max-width: 100%;
  }
  .info {
    font-size: 12px;
    display:inline-block;
    float:right;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

 

  table, th, td {
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #aaa;
    text-align: left;
  }
  th {
    background-color: #f0f0f0;
  }
  .totals {
    text-align: right;
    margin-top: 10px;
  }
  .totals .label {
    font-weight: bold;
  }
  .notes {
    margin-top: 30px;
  }
  .notes p {
    margin: 5px 0;
  }
  .text-right
  {
    text-align:right
  }
  .graybackground
  {
    background:#eee;
    padding:10px;
    margin-top:3px;
    width:20%;
    text-align:center;
    float:right
  }
</style>
</head>
<body>
<div class="invoice-container">
   
   

    <div class="header">
        <div class="company-details">
          <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="logo">
            <h1>funcamp</h1>
            <p>Dubai U.A.E</p>
            <p>056674766</p>
            <p>info@funcamp.ae</p>
            <p>www.funcamp.ae</p>
        </div>
        <div class="quote">
            TAX RECEIPT<br>
             <span class="quote-number"># INV-{{$order->id}}</span>

             <div class="details">
              <div class="text-right">
                <p>Receipt Date :{{ date('Y/d/m') }}</p>
                <p>Terms: Due on Receipt</p>
                <p>Due Date: {{ date('Y/d/m', strtotime('+1 day')) }}</p>
              </div>
            </div>
          
        </div>
    </div>


  
  
  
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Description</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
             @php $totalPrice = 0 @endphp 
        @foreach ($order->services as $index => $service)
            @php $totalPrice += $service->pivot->price; @endphp 
        <tr>
            <td>{{$index}}</td>
            <td>
                {{$service->name}}
                <br> <small style="color:#aaa">{{$service->days}} day</small>
            </td>
            <td>1.00</td>
            <td>{{$service->pivot->price}}</td>
            <td>{{$service->pivot->price}}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
  
  <div class="totals">
    <p><span class="label">Total</span> {{$totalPrice }}</p>
    <p><span class="label">Payment Made</span> <span style="color:red">(-) {{ $order->payments->sum('price')}}</span></p>
    <p class="graybackground"><span class="label ">Balance Due</span> <span style="">{{$order->price - $order->payments->sum('price')}}</span></p>
  </div>
  
  <!--- 
  <div class="notes">
    <p>Deposit: 4000AE</p>
  </div> -->
 
</div>
</body>
</html>
