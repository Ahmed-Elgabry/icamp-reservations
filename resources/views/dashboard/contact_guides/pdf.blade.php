<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}">
    <title>{{ __('dashboard.contacts_export') }}</title>
    <style>
        @if(app()->getLocale() == 'ar')
            @font-face {
                font-family: 'Cairo';
                font-style: normal;
                font-weight: 400;
                src: local('Cairo'), local('Cairo-Regular'),
                     url('{{ public_path('fonts/Cairo-Regular.ttf') }}') format('truetype');
                font-display: swap;
                unicode-range: U+0600-06FF, U+200C-200E, U+2010-2011, U+204F, U+2E41, U+FB50-FDFF, U+FE80-FEFF;
            }
            body {
                font-family: 'Cairo', Arial, sans-serif;
            }
        @else
            body {
                font-family: Arial, sans-serif;
            }
        @endif
        
        body {
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        
        .date {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }
        
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
        @endif
        <h1>{{ __('dashboard.contacts_list') }}</h1>
        <div class="date">
            {{ __('dashboard.generated_on') }}: {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('dashboard.entity_name') }}</th>
                <th>{{ __('dashboard.contact_person_name') }}</th>
                <th>{{ __('dashboard.primary_phone') }}</th>
                <th>{{ __('dashboard.secondary_phone') }}</th>
                <th>{{ __('dashboard.fixed_phone') }}</th>
                <th>{{ __('dashboard.email') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $index => $contact)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $contact->entity_name }}</td>
                    <td>{{ $contact->contact_person_name }}</td>
                    <td>{{ $contact->primary_phone }}</td>
                    <td>{{ $contact->secondary_phone }}</td>
                    <td>{{ $contact->fixed_phone }}</td>
                    <td>{{ $contact->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">{{ __('dashboard.no_contacts_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        {{ config('app.name') }} - {{ now()->format('Y') }} | {{ __('dashboard.page') }} <span class="page"></span>
    </div>
    
    <script type="text/php">
        if (isset($pdf)) {
            $text = "{PAGE_NUM} / {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
