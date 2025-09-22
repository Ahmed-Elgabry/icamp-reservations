<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $directory->name }} - Equipment Directory</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .directory-name { font-size: 24px; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .media-container { margin-top: 10px; }
        .media-item { display: inline-block; margin-right: 10px; margin-bottom: 10px; }
        .media-item img { max-width: 150px; max-height: 150px; }
    </style>
</head>
<body>
<div class="header">
    <div class="directory-name">{{ $directory->name }}</div>
    <div>Generated on: {{ now()->format('Y-m-d H:i') }}</div>
</div>

@foreach($directory->items as $item)
    <div class="item-section">
        <h3>{{ $loop->iteration }}. {{ $item->type }} - {{ $item->name }}</h3>
        <table class="table">
            <tr>
                <th>Location</th>
                <td>{{ $item->location }}</td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td>{{ $item->quantity }}</td>
            </tr>
            <tr>
                <th>Notes</th>
                <td>{{ $item->notes }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
            </tr>
        </table>

        @if($item->media->count() > 0)
            <h4>Media</h4>
            <div class="media-container">
                @foreach($item->media as $media)
                    <div class="media-item">
                        @if($media->file_type === 'image')
                            <img src="{{ storage_path('app/public/' . $media->file_path) }}" alt="">
                        @else
                            <div>[Video File]</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
