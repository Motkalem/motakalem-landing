<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">
    <title>{{ $notification->title }}</title>
</head>

<body>
<div class="primary-container" style="direction: rtl;">
    <div style="width: 75%; margin: auto; border: .2px solid #06A996">
        <div style="background-color: #06A996; padding: .2rem; text-align: center;">
            <img style="width: 10rem; margin-top: .5rem; margin-bottom: 1rem;" src="https://admin.motkalem.sa/images/email/logo.png"
                 alt="motkalem word beside the a yellow logo">
        </div>
        <div style="background-color: #D9D9D9;padding: 2rem;">
            <div style="text-align: center;">
                 <div>
                    <p style="font-size: 1.2rem; font-weight: 600;"> متكلم: إشعار بعملية دفع جديدة عبر هايبر باي </p>
                </div>
            </div>
            <hr />
            <table>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">الوصف  : </td>
                    <td>{{  $result  }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">قيمة المعاملة: </td>
                    <td>{{  data_get($notification->payload,'amount')??0  }} ر.س</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">طريقة الدفع: </td>
                    <td>التقسيط</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">  اسم الطالب: </td>
                    <td>{{ data_get($notification, 'installmentPayment.student.name')}}</td>
                </tr>

                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">البريد الإلكتروني للطالب: </td>
                    <td>{{ data_get($notification, 'installmentPayment.student.email') }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;"> رقم الهاتف  : </td>
                    <td>{{ data_get($notification, 'installmentPayment.student.phone') }}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">تاريخ العملية: </td>
                    <td>{{ data_get($notification->payload,'timestamp')   }}</td>
                </tr>

            </table>
        </div>
    </div>
    <div style="width: 75%; margin: auto; padding-top: .8rem ;">
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
    </div>
</div>
</body>

</html>
