<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>التقارير اليومية</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 20px; text-align: left; font-size: 0.8em; }
    </style>
</head>
<body>
<div class="header">
    <h1>التقارير اليومية</h1>
    <p>تاريخ الاصدار : {{ now()->format('Y-m-d H:i') }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>العنوان</th>
        <th>الموظف</th>
        <th>تاريخ الاضافة</th>
        <th>تفاصيل</th>
        <th>ملاحظات</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $report)
        <tr>
            <td>{{ $report->title }}</td>
            <td>{{ $report->employee->name }}</td>
            <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
            <td>{!! nl2br(e($report->details)) !!}</td>
            <td>{!! nl2br(e($report->notes)) !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
