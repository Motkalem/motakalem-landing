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
    <title>    تم تسجيل اشتراك جديد   </title>

</head>

<body>
<div class="primary-container" style="direction: rtl;">
    <div style="width: 75%; margin: auto; border: .2px solid #06A996">
        <div style="background-color: #06A996; padding: .2rem; text-align: center;">

            <img style="width: 10rem; margin-top: .5rem; margin-bottom: 1rem;" src="https://dev.motkalem.com/images/email/logo.png"
                 alt="motkalem word beside the a yellow logo">
        </div>
        <div style="background-color: #D9D9D9;padding: 2rem;">
            <div style="text-align: center;">
                <img style="width: 4rem;" src="https://dev.motkalem.com/images/email/checkmark.png" alt="checkmark in green">
                <div>
                    <p style="font-size: 1.2rem; font-weight: 600;">  تم التسجيل بالبرنامج  </p>
                </div>
            </div>
            <hr />
            <table>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">اسم البرنامج: </td>
                    <td>برنامج متكلم التأهيلي</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">اسم المشترك: </td>
                    <td> {{$student->name}}  </td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">عمر المشترك: </td>
                    <td>{{$student->age}} </td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">رقم الجوال: </td>
                    <td>{{$student->phone}}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">المدينة: </td>
                    <td>{{$student->city}}</td>
                </tr>
            </table>
            <hr />
            <table>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">رقم الفاتورة: </td>
                    <td>{{$transaction->id}}</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">طريقة الدفع: </td>
                    <td>كارت فيزا</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">تكفلة البرنامج: </td>
                    <td>{{$transaction->amount}} ر.س</td>
                </tr>
                <tr>
                    <td style="display: inline-block; margin-left:.6rem; font-weight: 600;">المبلغ المدفوع: </td>
                    <td>{{$transaction->amount}} ر.س</td>
                </tr>
            </table>
        </div>
    </div>
    <div style="width: 75%; margin: auto; padding-top: .8rem ;">
        <div style="font-weight: 600; color: #5e5e5e;">
            <span>زر موقعنا: </span>
            <a style="font-weight: normal;" href="https://motkalem.com/" target="_blank">motkalem.com</a>
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
