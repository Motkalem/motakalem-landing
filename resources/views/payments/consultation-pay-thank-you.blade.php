<!doctype html>
<html data-critters-container="" data-darkreader-mode="dynamic" data-darkreader-scheme="dimmed"
      data-darkreader-proxy-injected="true" direction="rtl" dir="rtl" class="arabic" style="direction: rtl;">
<head>
    <meta charset="utf-8">
    <title>متكلم - فاتورة الدفع</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="./assets/img/motkalem-logo 1.png">
    <!-- Font Awesome Icons -->
    @include('payments.assets.consaltation-pay')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <style>

       div,p,h1,h2,h3,h4,h5,h6,span,a,th,td,strong,sm {
            font-family: "Cairo", serif !important;
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
            font-size: 20px;
        }

        .invoice-header p {
            color: #777;
        }

        .invoice-details {
            margin-bottom:50px;


        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;

        }

        .invoice-details th, .invoice-details td {
            padding: 1px;
            padding-left: 5px;
            padding-right: 5px;
            text-align: left;
            border-top: 1px solid #ddd;
        }

        .invoice-details th {
            background-color: #f9f9f9;
            color: #555;
        }


    </style>

    <style>
        .nav-test[_ngcontent-ng-c2170032471] .nav-wrapper[_ngcontent-ng-c2170032471] {
            width: unset;
            height: 80px;
            background-color: #fff;
            position: absolute;
            z-index: 3;
            margin: auto;
            padding: 0 25px;
            border-bottom: unset;
            box-shadow: 0 2px 15px #0003;
        }

        .nav-test[_ngcontent-ng-c2170032471] .nav-wrapper[_ngcontent-ng-c2170032471] {
            width: unset !important;
            height: 80px;
            background-color: #fff;
            position: absolute;
            z-index: 3;
            margin: auto;
            padding: 0 25px;
            border-bottom: unset !important;
            box-shadow: unset !important;
        }

        @font-face {
            font-family: 'SarRegular';
            src: url('/fonts/font/sar-Regular.otf') format('opentype');
        }

        .riyal-symbol {
            width: 1.6rem;
            height: 2rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;

            font-family: 'SarRegular', sans-serif !important;
            font-size: 1.3rem;
            font-weight: 100 !important;
        }
    </style>
</head>
<script
        src="{{env('HYPERPAY_URL')}}/paymentWidgets.js?checkoutId={{$paymentId??data_get($_GET,'checkoutId')}}"></script>

<body class="mat-typography arabic" cz-shortcut-listen="true">
<!-- Google Tag Manager (noscript) -->
<div class="invoice-container">

    <app-navbar _ngcontent-ng-c277388621="" _nghost-ng-c2170032471="">
        <div _ngcontent-ng-c2170032471="" class="nav-test">
            <nav _ngcontent-ng-c2170032471="" id="nav"
                 class="nav-wrapper d-flex flex-row justify-content-around align-items-center flex-nowrap">
                <div _ngcontent-ng-c2170032471="" class="logo-container">
                    <a _ngcontent-ng-c2170032471="" href="https://motkalem.sa"><img
                                _ngcontent-ng-c2170032471="" src="{{asset('images/logo.png')}}" alt=""
                                class="new-logo-colored"></a>
                </div>
                <nav _ngcontent-ng-c2170032471="">
                    <input _ngcontent-ng-c2170032471="" type="checkbox" id="menuToggle" class="hidden">
                    <div _ngcontent-ng-c2170032471="" class="nav-container">
                        <ul _ngcontent-ng-c2170032471="" class=" ">

                            <li _ngcontent-ng-c2170032471=""
                                class="nav-tab text-end d-flex justify-content-center align-items-center p-0"><a
                                        _ngcontent-ng-c2170032471=""
                                        href="https://www.instagram.com/motkalemsa/?igshid=OGQ5ZDc2ODk2ZA%3D%3D"
                                        target="_blank" class="mx-3 mx-md-4">
                                    <svg _ngcontent-ng-c2170032471="" width="29" height="29" viewBox="0 0 29 29"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path _ngcontent-ng-c2170032471=""
                                              d="M15 6.96686C18.9375 6.96686 22.1875 10.2169 22.1875 14.1544C22.1875 18.1544 18.9375 21.3419 15 21.3419C11 21.3419 7.8125 18.1544 7.8125 14.1544C7.8125 10.2169 11 6.96686 15 6.96686ZM15 18.8419C17.5625 18.8419 19.625 16.7794 19.625 14.1544C19.625 11.5919 17.5625 9.52936 15 9.52936C12.375 9.52936 10.3125 11.5919 10.3125 14.1544C10.3125 16.7794 12.4375 18.8419 15 18.8419ZM24.125 6.71686C24.125 7.65436 23.375 8.40436 22.4375 8.40436C21.5 8.40436 20.75 7.65436 20.75 6.71686C20.75 5.77936 21.5 5.02936 22.4375 5.02936C23.375 5.02936 24.125 5.77936 24.125 6.71686ZM28.875 8.40436C29 10.7169 29 17.6544 28.875 19.9669C28.75 22.2169 28.25 24.1544 26.625 25.8419C25 27.4669 23 27.9669 20.75 28.0919C18.4375 28.2169 11.5 28.2169 9.1875 28.0919C6.9375 27.9669 5 27.4669 3.3125 25.8419C1.6875 24.1544 1.1875 22.2169 1.0625 19.9669C0.9375 17.6544 0.9375 10.7169 1.0625 8.40436C1.1875 6.15436 1.6875 4.15436 3.3125 2.52936C5 0.904358 6.9375 0.404358 9.1875 0.279358C11.5 0.154358 18.4375 0.154358 20.75 0.279358C23 0.404358 25 0.904358 26.625 2.52936C28.25 4.15436 28.75 6.15436 28.875 8.40436ZM25.875 22.4044C26.625 20.5919 26.4375 16.2169 26.4375 14.1544C26.4375 12.1544 26.625 7.77936 25.875 5.90436C25.375 4.71686 24.4375 3.71686 23.25 3.27936C21.375 2.52936 17 2.71686 15 2.71686C12.9375 2.71686 8.5625 2.52936 6.75 3.27936C5.5 3.77936 4.5625 4.71686 4.0625 5.90436C3.3125 7.77936 3.5 12.1544 3.5 14.1544C3.5 16.2169 3.3125 20.5919 4.0625 22.4044C4.5625 23.6544 5.5 24.5919 6.75 25.0919C8.5625 25.8419 12.9375 25.6544 15 25.6544C17 25.6544 21.375 25.8419 23.25 25.0919C24.4375 24.5919 25.4375 23.6544 25.875 22.4044Z"
                                              fill="#144356" data-darkreader-inline-fill=""
                                              style="--darkreader-inline-fill: #34474f;"></path>
                                    </svg>
                                </a><a _ngcontent-ng-c2170032471=""
                                       href="https://twitter.com/motkalemsa/status/1687890975023091712?s=20"
                                       target="_blank" class="mx-3 mx-md-4">
                                    <svg _ngcontent-ng-c2170032471="" xmlns="http://www.w3.org/2000/svg" height="29"
                                         width="29" viewBox="0 0 512 512">
                                        <path _ngcontent-ng-c2170032471=""
                                              d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"
                                              fill="#144356" data-darkreader-inline-fill=""
                                              style="--darkreader-inline-fill: #34474f;"></path>
                                    </svg>
                                </a><a _ngcontent-ng-c2170032471=""
                                       href="https://www.facebook.com/MotkalemSASS?mibextid=LQQJ4d" target="_blank"
                                       class="mx-3 mx-md-4">
                                    <svg _ngcontent-ng-c2170032471="" width="29" height="29" viewBox="0 0 18 33"
                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path _ngcontent-ng-c2170032471=""
                                              d="M16.4375 18.1544H11.75V32.1544H5.5V18.1544H0.375V12.4044H5.5V7.96686C5.5 2.96686 8.5 0.154358 13.0625 0.154358C15.25 0.154358 17.5625 0.591858 17.5625 0.591858V5.52936H15C12.5 5.52936 11.75 7.02936 11.75 8.65436V12.4044H17.3125L16.4375 18.1544Z"
                                              fill="#144356" data-darkreader-inline-fill=""
                                              style="--darkreader-inline-fill: #34474f;"></path>
                                    </svg>
                                </a></li>
                        </ul>
                    </div>
                </nav>
            </nav>
        </div>
    </app-navbar>

    <div _ngcontent-ng-c277388621="" class="routing">

        <div style="height: 400px;padding:30px; margin-bottom:30px ">
            <!-- Invoice Header -->
            <div class="invoice-header">
                <h2> الفاتورة </h2>
                <sm>شكراً لاستخدامكم خدمات متكلم</sm>
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details">
                <table >
                    <tr>
                        <th style="text-align: start; font-weight: normal"> رقم الفاتورة  </th>
                        <td style="text-align: start">{{ $consultationPatient?->id }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: start; font-weight: normal">اسم العميل</th>
                        <td style="text-align: start">{{ $consultationPatient?->name }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: start; font-weight: normal"> المدينة  </th>
                        <td style="text-align: start">{{ $consultationPatient?->city }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: start; font-weight: normal"> الهاتف  </th>
                        <td style="text-align: end;direction: ltr; ">{{ $consultationPatient?->mobile }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: start;font-weight: normal">نوع الخدمة</th>
                        <td style="text-align: start">{{ $consultationPatient?->consultationType?->name }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: start;font-weight: normal">
                                الضريبة 15%
                            </th>
                        <td style="text-align: start">{{  $consultationPatient?->consultationType->price * .15  }}
                            <span class="riyal-symbol">R</span></td>
                    </tr>
                    <tr>
                        <th style="text-align: start;font-weight: normal">

                            سعر الخدمة
                            </th>

                        <td style="text-align: start">
                            {{  $consultationPatient?->consultationType->price - ($consultationPatient?->consultationType->price * .15) }}

                            <span class="riyal-symbol">R</span>

                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: start;font-weight: normal">وقت وتاريخ الدفع</th>
                        <td style="text-align: start">
                            {{ \Carbon\Carbon::parse($consultationPatient->updated_at)->translatedFormat('d M Y, h:i A') }}
                        </td>
                    </tr>
                    <tr >
                        <th style="text-align: start;font-weight: bold"> الإجمالي </th>
                        <td style="text-align: start; ">{{  $consultationPatient?->consultationType->price }}  <span class="riyal-symbol">R</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: start;font-weight: normal;">

                        </td>
                    </tr>
                </table>
            </div>

        </div>
            <section _ngcontent-ng-c3011216936="" class="first_footer py-3" style="margin-top: 62px">
                <div _ngcontent-ng-c3011216936="" class="container-fluid align-self-center">
                    <div _ngcontent-ng-c3011216936="" class="row justify-content-center p-1 mb-0">

                        <div _ngcontent-ng-c3011216936=""
                             class="col-md-4 text-center align-self-center mt-1 border-left">

                            <p _ngcontent-ng-c3011216936="" class="text-white"> رقم السجل التجاري </p>
                            <p _ngcontent-ng-c3011216936="" class="text-white"> 4030511477 </p>
                        </div>
                        <div _ngcontent-ng-c3011216936=""
                             class="col-md-4 text-center align-self-center mt-1 border-left">

                            <p _ngcontent-ng-c3011216936="" class="text-white"> رقم التسجيل الضريبي </p>
                            <p _ngcontent-ng-c3011216936="" class="text-white"> 312011490100003 </p>
                        </div>

                    </div>
                </div>
            </section>
            <footer _ngcontent-ng-c3011216936="">
                <div _ngcontent-ng-c3011216936="" class="container">
                    <div _ngcontent-ng-c3011216936="" class="row justify-content-center align-items-center">
                        <div _ngcontent-ng-c3011216936="" class="col-md-2 text-center"><a _ngcontent-ng-c3011216936=""
                                                                                          href="/home"><img
                                        _ngcontent-ng-c3011216936="" src="../../../../assets/img/motkalem-new-logo.png"
                                        alt=""
                                        class="w-75"></a></div>
                        <div _ngcontent-ng-c3011216936="" class="col-md-12 text-center">
                            <div _ngcontent-ng-c3011216936="" class="d-flex justify-content-around align-items-center">
                                <a
                                        _ngcontent-ng-c3011216936="" href="https://motkalem.sa">الرئيسية</a>
                            </div>
                            <div _ngcontent-ng-c3011216936="" class="d-flex flex-column mt-2"><a
                                        _ngcontent-ng-c3011216936="" href="mailto:“info@motkalem.com”"
                                        class="text-decoration-underline">info@motkalem.sa</a></div>
                            <sm class="text-white text-sm-center">&copy; {{ '2025' }} متكلم - جميع الحقوق محفوظة</sm>
                        </div>

                    </div>
                </div>
            </footer>
    </div>


</div>
</body>
</html>



