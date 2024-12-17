<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>متكلم - فاتورة الدفع</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .invoice-header h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }

        .invoice-header p {
            color: #777;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details th, .invoice-details td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .invoice-details th {
            background-color: #f9f9f9;
            color: #555;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <!-- Invoice Header -->
    <div class="invoice-header">
        <h2>شكراً لاستخدام خدمات متكلم</h2>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <table>
            <tr>
                <th style="text-align: start">اسم العميل</th>
                <td style="text-align: start">{{ $consultationPatient?->name }}</td>
            </tr>
            <tr>
                <th style="text-align: start">نوع الاستشارة</th>
                <td style="text-align: start">{{ $consultationPatient?->consultationType?->name }}</td>
            </tr>
            <tr>
                <th style="text-align: start">المبلغ المدفوع</th>
                <td style="text-align: start">{{ $consultationPatient?->consultationType->price }} @lang('SAR')</td>
            </tr>
            <tr>
                <th style="text-align: start">تاريخ الدفع</th>
                <td style="text-align: start">{{ now()->format('Y-m-d') }}</td>
            </tr>
        </table>
    </div>

    <!-- Total Section -->
    <div class="total-section">
        <strong>إجمالي المبلغ: {{ $consultationPatient?->consultationType->price }} @lang('SAR')</strong>
    </div>

    <!-- Back to Home -->
    <div class="text-center">
        <a href="https://motkalem.sa" class="button">العودة إلى الصفحة الرئيسية</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} متكلم - جميع الحقوق محفوظة</p>
    </div>
</div>
</body>
</html>
