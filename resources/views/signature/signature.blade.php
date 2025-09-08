<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <title>{{ env('APP_NAME')}} | @lang('dashboard.signature_title', ['id' => $order->id])</title>
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

        .language-switcher {
            position: absolute;
            top: 20px;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 20px;
            z-index: 1000;
        }

        .language-switcher button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .language-switcher button:hover {
            background: var(--primary-600);
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
        .d-flex {
            display : flex;
        }
        .justify-content-between {
            justify-content : space-between ; 
        }
        .align-items-center {
            align-items :center
        }

        .t-left {
            text-align :left ;
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

        .alert.alert-warning {
            background: rgba(251, 191, 36, .1);
            color: #b45309;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

    .text-center { text-align: center; }
    </style>
</head>

<body>

    <!-- Language Switcher -->
    <div class="language-switcher">
        <button onclick="changeLanguage('{{ app()->getLocale() == 'ar' ? 'en' : 'ar' }}')">
            {{ app()->getLocale() == 'ar' ? __('dashboard.switch_to_english') : __('dashboard.switch_to_arabic') }}
        </button>
    </div>

    <div class="shell">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h1>@lang('dashboard.management_system')</h1>
        </div>

        @if ($terms)
            <div class="alert alert-warning justify-content-between align-items-center  d-flex" style="direction: rtl;">
                <div style="margin-bottom: 8px;">
                    <strong>تنبيه</strong> {!! $terms?->commercial_license_ar !!}
                </div>
                @if($terms?->commercial_license_en)
                    <div class = "t-left" >
                        <strong>:Notice</strong> {!! $terms?->commercial_license_en !!}
                    </div>
                @endif
            </div>
        @endif

        <div class="card">
            <div class="header">
                <div>
                    <h2 class="title">@lang('dashboard.signature_for_approval')</h2>
                   
                                <div class="d-block">
                                    <div class="border rounded p-3 bg-light-subtle">
                                        <div class="row g-3 small">
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted">{{ __('dashboard.order_id') }}</div>
                                                <div class="fw-bold">{{ $order->id }}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted">{{ __('dashboard.customer_name') }}</div>
                                                <div class="fw-bold">{{ $order->customer->name }}</div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="fw-semibold text-muted">{{ __('dashboard.phone')}}</div>
                                                <div class="fw-bold">{{ $order->customer->phone }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
                <div class="row" style="margin:0">
                    <button id="undo" class="btn-ghost" type="button">@lang('dashboard.undo')</button>
                    <button id="clear" class="btn-danger" type="button">@lang('dashboard.clear')</button>
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
                    <div class="hint">@lang('dashboard.signature_hint')</div>
                </div>

                <form id="sig-form" method="POST" action="{{ route('signature.store', $order) }}">
                    @csrf
                    <input type="hidden" name="signature" id="signature-input">
                    <div class="row">
                        <button type="submit" class="btn-primary">@lang('dashboard.submit_signature')</button>
                        <button id="download" type="button" class="btn-ghost">@lang('dashboard.download_png')</button>
                    </div>
                </form>
            </div>

            <div class="footer">
                <span class="hint">@lang('dashboard.signature_confirmation')</span>
                <span class="hint">@lang('dashboard.technical_support')</span>
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
            if (signaturePad.isEmpty()) { alert('@lang('dashboard.no_signature_to_download')'); return; }
            const url = signaturePad.toDataURL('image/png');
            const a = document.createElement('a'); a.href = url; a.download = 'signature-order-{{ $order->id }}.png';
            document.body.appendChild(a); a.click(); a.remove();
        });

        form.addEventListener('submit', (e) => {
            if (signaturePad.isEmpty()) { e.preventDefault(); alert('@lang('dashboard.please_draw_signature')'); return; }
            input.value = signaturePad.toDataURL('image/png');
        });

        // Language switching function
        function changeLanguage(locale) {
            const url = new URL(window.location);
            url.searchParams.set('lang', locale);
            window.location.href = url.toString();
        }
    </script>
</body>

</html>
