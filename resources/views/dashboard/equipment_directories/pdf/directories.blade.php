<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Equipment Directories</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 12px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
<h2>Equipment Directories</h2>
<p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>

<table>
    <thead>
    <tr>
        <th>Directory Name</th>
        <th>Sub Items Count</th>
        <th>Media Count</th>
        <th>Creator / Date</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($directories as $directory)
        <tr>
            <td>{{ $directory->name }}</td>
            <td>{{ $directory->items_count }}</td>
            <td>{{ $directory->media_count }}</td>
            <td>
                {{ $directory->creator->name }}<br>
                {{ $directory->created_at->format('Y-m-d h:i A') }}
            </td>
            <td>
                        <span class="badge badge-{{ $directory->is_active ? 'success' : 'danger' }}">
                            {{ $directory->is_active ? 'Active' : 'Inactive' }}
                        </span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
