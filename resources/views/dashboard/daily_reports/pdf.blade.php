<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Daily Reports</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { margin-top: 20px; text-align: right; font-size: 0.8em; }
    </style>
</head>
<body>
<div class="header">
    <h1>Daily Reports</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>Title</th>
        <th>Employee</th>
        <th>Created At</th>
        <th>Details</th>
        <th>Notes</th>
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

<div class="footer">
    Page {PAGENO} of {nbpg}
</div>
</body>
</html>
