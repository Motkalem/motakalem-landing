<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>إشعار تسجيل جديد عبر تاببي - برنامج متكلم</title>
</head>

<body style="direction: rtl; font-family: 'Poppins', sans-serif;">
<div style="width: 75%; margin: auto; border: 1px solid #06A996; border-radius: 10px; overflow: hidden;text-align:rtl">
    <div style="background-color: #06A996; text-align: center; padding: 1rem;">
        <img src="https://admin.motkalem.sa/images/email/logo.png" alt="شعار متكلم" style="width: 150px;">
    </div>
    <div style="background-color: #F7F7F7; padding: 2rem;">
        <h2 style="text-align: center; color: #333; font-size: 1.5rem; margin-bottom: 1rem;">
            طالب جديد قام بالتسجيل في برنامج متكلم
        </h2>

        <p style="text-align: center; color: #444; font-size: 1rem; margin-bottom: 1rem;">
            الاسم: <strong>{{ $student->name }}</strong><br>
            البريد الإلكتروني: <strong>{{ $student->email }}</strong><br>
           
        </p>

        <p class="text-align:center">
            الهاتف:
            <strong dir="ltr" style="display: inline-block; text-align: left;margin:auto">
                {{ $student->phone }}
            </strong>
        </p>
        

        <p style="text-align: center; color: #666; margin-bottom: 2rem;">
            وقد اختار الدفع عبر <strong>تاببي</strong>. 
        </p>
    </div>

    <div style="background-color: #EFEFEF; padding: 1rem; text-align: center; font-size: 0.9rem; color: #666;">
        <p>  <a href="https://motkalem.sa/" target="_blank" style="color: #06A996; text-decoration: none;">motkalem.sa</a></p>
        <p>رقم التسجيل الضريبي: ۳۱۲۰۱۱٤۹۰۱۰۰۰۳</p>
        <p>رقم السجل التجاري: ٤۰۳۰٥۱۱٤۷۷</p>
    </div>
</div>
</body>

</html>
