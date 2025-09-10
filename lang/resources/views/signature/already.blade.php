<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>تم التوقيع - حجز {{ $order->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
    <style>
        :root {
            --primary: #3b82f6;
            --bg: #f6f8fc;
            --card: #ffffff;
            --border: #e7edf3;
            --text: #0f172a;
            --muted: #6b7280;
            --shadow: 0 18px 40px rgba(31, 41, 55, .08);
            --radius: 16px;
            --success: #22c55e;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Cairo", sans-serif;
            display: grid;
            place-items: center;
            min-height: 100vh;
            padding: 24px;
        }

        .shell {
            width: min(880px, 100%);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .brand img {
            height: 40px;
            border-radius: 8px
        }

        .brand h1 {
            margin: 0;
            font-size: 18px;
            color: var(--muted);
            font-weight: 600
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .header {
            padding: 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            color: #0a3d20;
            background: linear-gradient(180deg, rgba(34, 197, 94, .18), rgba(34, 197, 94, .05));
            border: 1px solid rgba(34, 197, 94, .35);
            padding: 8px 12px;
            border-radius: 999px;
        }

        .title {
            margin: 0 0 4px;
            font-size: 20px;
            font-weight: 800;
        }

        .subtitle {
            margin: 0;
            font-size: 13px;
            color: var(--muted);
        }

        .content {
            padding: 22px;
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 20px;
        }

        .sig-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            background: #fff;
        }

        .sig-box img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .meta {
            display: grid;
            gap: 10px;
        }

        .meta .item {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 10px 12px;
            border: 1px dashed var(--border);
            border-radius: 10px;
            background: #fff;
        }

        .meta .label {
            color: var(--muted);
            font-size: 13px;
        }

        .row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        @media (max-width:820px) {
            .content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="shell">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h1>نظام الإدارة</h1>
        </div>

        <div class="card">
            <div class="header">
                <span class="badge">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2" opacity=".25" />
                        <path d="M7 12.5l3.2 3.2L17 8.9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    تم التوقيع بنجاح
                </span>
            </div>

            <div class="content">
                <div class="sig-box">
                    <img src="{{ Storage::url($order->signature_path) }}" alt="Signature">
                    <div class="row">
                        <a class="btn btn-primary" href="{{ Storage::url($order->signature_path) }}" download>تنزيل
                            التوقيع</a>
                        <a class="btn btn-outline" target="_blank" href="{{ Storage::url($order->signature_path) }}">فتح
                            الصورة</a>
                    </div>
                </div>

                <div class="meta">
                    <div class="item"><span class="label">رقم الحجز</span><span>#{{ $order->id }}</span></div>
                    <div class="item"><span class="label">تاريخ التوقيع</span><span>{{ $order->signature }}</span></div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
