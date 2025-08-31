<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #333;
        }
        .content {
            padding: 20px;
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>إعادة تعيين كلمة المرور , {{env('APP_NAME')}}</h1>
        </div>
        <div class="content">
            <p>انقر على الزر أدناه لإعادة تعيين كلمة المرور الخاصة بك.</p>
            <a href="{{ $actionUrl }}" class="button">إعادة تعيين كلمة المرور</a>
        </div>
        <div class="footer">
            <p>إذا لم تكن تتوقع استعادة كلمة المرور، لا تقم بأي إجراء آخر.</p>
        </div>
    </div>
</body>
</html>
