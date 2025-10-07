<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>تم تسجيل اشتراك جديد في المركز</title>

</head>

<body>
<div class="primary-container" style="direction: rtl;">
    <div style="width: 75%; margin: auto; border: .2px solid #144356">
        <div style="background-color: #144356; padding: .2rem; text-align: center;">
            <img style="width: 10rem; margin-top: .5rem; margin-bottom: 1rem;"
                 src="https://admin.motkalem.sa/images/email/logo.png"
                 alt="motkalem word beside the a yellow logo">
        </div>

        <div style="background-color: #D9D9D9;padding: 2rem;">
            <div style="text-align: center;">
                <img style="width: 4rem;" src="https://admin.motkalem.sa/images/email/checkmark.png" alt="checkmark in green">
                <div>
                    <p style="font-size: 1.2rem; font-weight: 600;">تم التسجيل في مركز متكلم</p>
                </div>
            </div>

            <hr />

            <!-- Patient Information -->
            <h3 style="color: #144356; margin-bottom: 1rem;">معلومات المريض:</h3>
            <table style="width: 100%; margin-bottom: 1.5rem;">
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">اسم المريض:</td>
                    <td>{{ $centerPatient->name }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">العمر:</td>
                    <td>{{ $centerPatient->age }} سنة</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">رقم الجوال:</td>
                    <td>{{ $centerPatient->mobile_number }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">البريد الإلكتروني:</td>
                    <td>{{ $centerPatient->email }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">المدينة:</td>
                    <td>{{ $centerPatient->city }}</td>
                </tr>
                @if($centerPatient->id_number)
                    <tr>
                        <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">رقم الهوية:</td>
                        <td>{{ $centerPatient->id_number }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">مصدر التسجيل:</td>
                    <td>
                        @if($centerPatient->source == 'dashboard')
                            لوحة التحكم
                        @elseif($centerPatient->source == 'web')
                            الموقع الإلكتروني
                        @elseif($centerPatient->source == 'mobile')
                            تطبيق الجوال
                        @else
                            {{ $centerPatient->source }}
                        @endif
                    </td>
                </tr>
            </table>

            <hr />

            <!-- Package Information -->
            <h3 style="color: #144356; margin-bottom: 1rem;">معلومات الباقة:</h3>
            <table style="width: 100%; margin-bottom: 1.5rem;">
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">اسم الباقة:</td>
                    <td>{{ $centerPatient->centerPayment->centerPackage->name ?? 'غير محدد' }}</td>
                </tr>
                @if($centerPatient->centerPackage?->description)
                    <tr>
                        <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">وصف الباقة:</td>
                        <td>{{ $centerPatient->centerPackage->description }}</td>
                    </tr>
                @endif
                @if($centerPatient->centerPackage?->duration)
                    <tr>
                        <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">مدة الباقة:</td>
                        <td>{{ $centerPatient->centerPackage->duration }}</td>
                    </tr>
                @endif
            </table>

            <hr />

            <!-- Payment Information -->
            <h3 style="color: #144356; margin-bottom: 1rem;">معلومات الدفع:</h3>
            <table style="width: 100%;">
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">رقم الدفع:</td>
                    <td>{{ $centerPayment->id }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">تكلفة الباقة:</td>
                    <td>{{ number_format($centerPayment->amount, 2) }} ر.س</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">طريقة الدفع:</td>
                    <td>دفعة واحدة</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">تاريخ التسجيل:</td>
                    <td>{{ $centerPatient->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @if($centerPayment->paid_at)
                    <tr>
                        <td style="display: inline-block; margin-left:.6rem; font-weight: 600; min-width: 120px;">تاريخ الدفع:</td>
                        <td>{{ $centerPayment->paid_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

    <div style="width: 75%; margin: auto; padding-top: .8rem;">
        <div style="font-weight: 600; color: #5e5e5e;">
            <span>زر موقعنا: </span>
            <a style="font-weight: normal;" href="https://motkalem.sa/" target="_blank">motkalem.sa</a>
        </div>
        <div style="color: #5e5e5e; font-weight: 600;">
            <div style="display: flex; justify-content: right; align-items: center; margin-left: 1rem;">
                <p style="margin: 0; margin-left: .8rem; font-size: .8rem;">رقم التسجيل الضريبي: </p>
                <span style="font-size: .8rem;">۳۱۲۰۱۱٤۹۰۱۰۰۰۳</span>
            </div>
            <div style="display: flex; justify-content: right; align-items: center; margin-left: 1rem;">
                <p style="margin: 0; margin-left: .8rem; font-size: .8rem;">رقم السجل التجاري: </p>
                <span style="font-size: .8rem;">٤۰۳۰٥۱۱٤۷۷</span>
            </div>
        </div>

        <!-- Contact Information -->
        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e0e0e0; color: #5e5e5e; font-size: .8rem;">
            <p style="margin: 5px 0;">للاستفسارات: info@motkalem.sa</p>
            <p style="margin: 5px 0;">هذا إشعار تلقائي، لا تقم بالرد على هذا البريد الإلكتروني.</p>
        </div>
    </div>
</div>
</body>

</html>
