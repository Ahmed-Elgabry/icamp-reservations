<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>توقيع الحجز رقم {{ $order->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />

    <style type="text/css">
        :root {
            --primary: #3b82f6;
            --primary-600: #2563eb;
            --bg: #f6f8fc;
            --card: #ffffff;
            --border: #e7edf3;
            --text: #0f172a;
            --muted: #6b7280;
            --danger: #ef4444;
            --shadow: 0 18px 40px rgba(31, 41, 55, .08);
            --radius: 16px;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            font-family: "Cairo", sans-serif;
            color: var(--text);
            background: var(--bg);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .shell {
            width: min(960px, 100%);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .brand img {
            height: 40px;
            width: auto;
            border-radius: 8px;
        }

        .brand h1 {
            margin: 0;
            font-size: 18px;
            color: var(--muted);
            font-weight: 600;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: linear-gradient(180deg, rgba(59, 130, 246, .08), transparent);
        }

        .title {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
        }

        .subtitle {
            margin: 2px 0 0;
            font-size: 13px;
            color: var(--muted);
        }

        .content {
            padding: 22px;
        }

        .sig-wrap {
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 12px;
            background: #fff;
        }

        .canvas-box {
            position: relative;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        canvas {
            width: 100%;
            height: 320px;
            display: block;
            touch-action: none;
            cursor: crosshair;
        }

        .hint {
            font-size: 13px;
            color: var(--muted);
            margin: 10px 2px 0;
        }

        .row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 14px;
        }

        button {
            border: 0;
            border-radius: 10px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .05s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 8px 20px rgba(59, 130, 246, .25);
        }

        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            background: rgba(2, 6, 23, .04);
        }

        .btn-danger {
            background: var(--danger);
            color: #fff;
        }

        .footer {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .error {
            color: var(--danger);
            font-size: 14px;
            margin-top: 8px;
        }

        @media (max-width:520px) {
            .header {
                flex-direction: column;
                align-items: flex-start
            }

            canvas {
                height: 260px
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
                <div>
                    <h2 class="title">التوقيع للموافقة</h2>
                    <p class="subtitle">رقم الحجز: {{ $order->id }}</p>
                </div>
                <div class="row" style="margin:0">
                    <button id="undo" class="btn-ghost" type="button">تراجع</button>
                    <button id="clear" class="btn-danger" type="button">مسح</button>
                </div>
            </div>

            <div class="content">
                @if ($errors->any())
                    <div class="error">{{ $errors->first() }}</div>
                @endif

                <div class="sig-wrap">
                    <div class="canvas-box">
                        <canvas id="signature"></canvas>
                    </div>
                    <div class="hint">استخدم الماوس أو الإصبع لرسم توقيعك بوضوح.</div>
                </div>

                <form id="sig-form" method="POST" action="{{ route('signature.store', $order) }}">
                    @csrf
                    <input type="hidden" name="signature" id="signature-input">
                    <div class="row">
                        <button type="submit" class="btn-primary">إرسال التوقيع</button>
                        <button id="download" type="button" class="btn-ghost">تنزيل PNG</button>
                    </div>
                </form>
            </div>

            <div class="footer">
                <span class="hint">بالإرسال، تؤكد أن هذا توقيعك القانوني.</span>
                <span class="hint">لدعم فني: تواصل مع المسؤول.</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script type="text/javascript">
        const canvas = document.getElementById('signature');
        const form = document.getElementById('sig-form');
        const input = document.getElementById('signature-input');
        const clearB = document.getElementById('clear');
        const undoB = document.getElementById('undo');
        const dlB = document.getElementById('download');

        const signaturePad = new SignaturePad(canvas, { minWidth: 0.7, maxWidth: 2.2, throttle: 16, penColor: '#111827' });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            canvas.width = Math.floor(rect.width * ratio);
            canvas.height = Math.floor(rect.height * ratio);
            const ctx = canvas.getContext('2d');
            ctx.scale(ratio, ratio);
            const data = signaturePad.toData();
            signaturePad.clear();
            if (data && data.length) signaturePad.fromData(data);
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        clearB.addEventListener('click', () => signaturePad.clear());
        undoB.addEventListener('click', () => {
            const data = signaturePad.toData();
            if (data.length) { data.pop(); signaturePad.fromData(data); }
        });
        dlB.addEventListener('click', () => {
            if (signaturePad.isEmpty()) { alert('لا يوجد توقيع للتنزيل'); return; }
            const url = signaturePad.toDataURL('image/png');
            const a = document.createElement('a'); a.href = url; a.download = 'signature-order-{{ $order->id }}.png';
            document.body.appendChild(a); a.click(); a.remove();
        });

        form.addEventListener('submit', (e) => {
            if (signaturePad.isEmpty()) { e.preventDefault(); alert('من فضلك ارسم توقيعك أولاً.'); return; }
            input.value = signaturePad.toDataURL('image/png');
        });
    </script>
</body>

</html>
