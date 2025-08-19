<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>أصناف - {{ $equipmentDirectory->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 12px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
<h2>أصناف - {{ $equipmentDirectory->name }}</h2>
<p>تاريخ الاصدار: {{ now()->format('Y-m-d H:i') }}</p>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>نوع الصنف</th>
        <th>اسم الصنف</th>
        <th>الموقع</th>
        <th>الكمية</th>
        <th>ملاحظات</th>
        <th>المنشئ / التاريخ</th>
        <th>عدد الوسائط</th>
        <th>الحالة</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->type }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->location }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ Str::limit($item->notes, 30) }}</td>
            <td>
                {{ $item->creator->name }}<br>
                {{ $item->created_at->format('Y-m-d h:i A') }}
            </td>
            <td>{{ $item->media->count() }}</td>
            <td>
                <span class="badge badge-{{ $item->is_active ? 'success' : 'danger' }}">
                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
