<!doctype html>
<html data-critters-container="" data-darkreader-mode="dynamic" data-darkreader-scheme="dimmed"
      data-darkreader-proxy-injected="true" direction="rtl" dir="rtl" class="arabic" style="direction: rtl;">
<head>
    <meta charset="utf-8">
    <title>متكلم - دفع الإشتراك</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="Content-Security-Policy"
          content="
                       style-src 'self' {{env('SNB_HYPERPAY_WIDGET_URL')}} 'unsafe-inline' ;
                       frame-src 'self' {{env('SNB_HYPERPAY_WIDGET_URL')}};
                       script-src 'self' {{env('SNB_HYPERPAY_WIDGET_URL')}} 'nonce-{{$nonce}}' ;
                       connect-src 'self' {{env('SNB_HYPERPAY_WIDGET_URL')}};
                       img-src 'self' {{env('SNB_HYPERPAY_WIDGET_URL')}};
                       ">
    <script nonce="{{$nonce}}">
        var wpwlOptions = {
            style:"card",
        }
    </script>

    <link rel="icon" type="image/x-icon" href="./assets/img/motkalem-logo 1.png">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous" nonce="{{$nonce}}"></script>

    @include('payments.assets.consaltation-pay')
    <style>

        .payment-options-container {

            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .payment-options {

            flex-direction: row;
            gap: 20px;
        }

        .payment-option {
            display: flex;
            justify-content: center;
            align-items: center;
            padding:5px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 8px;
            padding-left: 30px;
            padding-right: 30px;
        }

        .payment-option img, .payment-option img {
            width: 60px;
            height: auto;
        }

        .payment-option img, .payment-option svg {
            width:50px;
            height: auto;

        }

        .payment-option:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
            font-size: 2.3rem;
            font-weight: 100 !important;
        }
    </style>
</head>


<script
    src="{{env('SNB_HYPERPAY_URL')}}/paymentWidgets.js?checkoutId={{$paymentId??data_get($_GET,'checkoutId')}}"
    integrity="{{$integrity}}"
    crossorigin="anonymous">
</script>

<body class="mat-typography arabic" cz-shortcut-listen="true" style="height: 100vh">

<app-navbar _ngcontent-ng-c277388621="" _nghost-ng-c2170032471="">

    <div _ngcontent-ng-c2170032471="" class="nav-test">
        <nav _ngcontent-ng-c2170032471="" id="nav"
             class="nav-wrapper sticky-top d-flex flex-row justify-content-around align-items-center flex-nowrap">
            <div _ngcontent-ng-c2170032471="" class="logo-container">
                <a _ngcontent-ng-c2170032471="" href="https://motkalem.sa"><img
                        _ngcontent-ng-c2170032471="" src="{{asset('images/logo.png')}}" alt=""
                        class="new-logo-colored"></a>
            </div>
            <nav _ngcontent-ng-c2170032471="">
                <input _ngcontent-ng-c2170032471="" type="checkbox" id="menuToggle" class="hidden">
                <div _ngcontent-ng-c2170032471="" class="nav-container">
                    <ul _ngcontent-ng-c2170032471="" class="nav-tabs">

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

<div class="routing">

    <div style="height: 650px;padding-top:50px;  margin-bottom:0px; direction: rtl ">
        <h1 class="text-center" style="text-align: center">ستقوم بدفع مبلغ {{$payment?->package?->total }}
            <span class="riyal-symbol">R</span> </h1>

        @if(data_get($_GET,'brand'))

            @if(in_array(data_get($_GET,'brand'), ['visa', 'master','mada','tabby'] ))


                <form action="{{'/checkout/result/'.$_GET['pid'].'/'.$_GET['sid'].'/'}}"
                      class="paymentWidgets" data-brands="{{strtoupper( data_get($_GET,'brand'))}}"></form>

                <div style="text-align: center;margin-top: 40px;color: #ffc107;">
                    <a href="javascript:void(0);"
                       class="payment-method-title"
                       style="text-align: center;  color: #ffc107; "
                       onclick="removeBrandParam()">
                        تغيير وسيلة الدفع ؟
                    </a>
                </div>
            @else
                <form action="{{'/checkout/result/'.$_GET['pid'].'/'.$_GET['sid'].'/'}}" class="paymentWidgets"
                      data-brands="VISA"></form>

                <div style="text-align: center;margin-top: 40px; color: #ffc107;">
                    <a href="javascript:void(0);"
                       class="payment-method-title"
                       style="text-align: center;  color: #ffc107; "
                       onclick="removeBrandParam()">
                        تغيير وسيلة الدفع ؟
                    </a>
                </div>
            @endif
        @else
            <div class="payment-method-title" style="text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 20px;">
                اختر طريقة الدفع
            </div>
            <div class="payment-options-container">
                <div class="payment-options">




                    <div>
                        <a href="{{ url()->current() }}?{{ http_build_query(array_merge($_GET, ['brand' => 'mada'])) }}"
                           class="payment-option" style="padding-top: 25px; padding-bottom:25px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 151.61 50.54">
                                <path d="M0,29.15H64.14V50.54H0Z" fill="#82bc00"/>
                                <path d="M0,0H64.14V21.38H0Z" fill="#00a1df"/>
                                <path d="M128.27,46.17l-.28.06a9.71,9.71,0,0,1-2.08.26c-1.67,0-3.65-.85-3.65-4.9,0-2.08.36-4.83,3.46-4.83h0a8.9,8.9,0,0,1,2.29.42l.24.08ZM128.79,26l-.52.09V33.6l-.44-.13-.13,0a10.59,10.59,0,0,0-2.84-.5c-6.25,0-7.57,4.73-7.57,8.69,0,5.43,3,8.55,8.36,8.55a17.17,17.17,0,0,0,5.6-.79c1.54-.49,2.1-1.2,2.1-2.7V25.26c-1.5.29-3,.53-4.55.78m18,20.27-.26.07-1,.25a9.44,9.44,0,0,1-2.3.37c-1.47,0-2.35-.73-2.35-2,0-.8.36-2.14,2.76-2.14h3.1Zm-2.18-13.47A21.14,21.14,0,0,0,138.3,34l-1.6.49.53,3.6,1.56-.51a18.33,18.33,0,0,1,5.19-.87c.7,0,2.8,0,2.8,2.27v1h-2.92c-5.3,0-7.77,1.69-7.77,5.32,0,3.11,2.26,4.95,6.08,4.95a18.81,18.81,0,0,0,4.23-.57h.15l.47.08c1.49.27,3,.53,4.53.84V38.64c0-3.85-2.32-5.8-6.89-5.8M109.85,46.31l-.26.07-1,.25a9.28,9.28,0,0,1-2.29.37c-1.47,0-2.35-.73-2.35-2,0-.8.36-2.14,2.76-2.14h3.1Zm-2.18-13.47A21.2,21.2,0,0,0,101.31,34l-1.6.49.54,3.6,1.55-.51a18.33,18.33,0,0,1,5.2-.87c.68,0,2.78,0,2.78,2.27v1h-2.91c-5.3,0-7.78,1.69-7.78,5.32,0,3.11,2.27,4.95,6.08,4.95a19,19,0,0,0,4.24-.57h.15l.47.08c1.49.27,3,.53,4.53.84V38.64c0-3.86-2.32-5.8-6.89-5.8m-17.89,0A12.77,12.77,0,0,0,84.62,34l-.19.09L84.27,34a7.82,7.82,0,0,0-4.64-1.12,18.71,18.71,0,0,0-5.46.81c-1.62.5-2.25,1.27-2.25,2.73V50H77V37.46l.24-.08a6.81,6.81,0,0,1,2.23-.4c1.47,0,2.2.77,2.2,2.3V50h5V39.06A2.89,2.89,0,0,0,86.49,38l-.16-.33.34-.15A6,6,0,0,1,89.14,37a2,2,0,0,1,2.22,1.79,2.18,2.18,0,0,1,0,.51V50h5V38.77c0-4-2.14-5.89-6.56-5.89m53.33-18.94a16.38,16.38,0,0,1-3-.27l-.28,0V6.28a3.35,3.35,0,0,0-.14-1l-.16-.31.32-.15a1.17,1.17,0,0,0,.24-.09l.06,0,.33-.11.15,0a10.41,10.41,0,0,1,2.61-.3h0c3.1,0,3.46,2.75,3.46,4.84,0,4-2,4.89-3.65,4.89M143.1,0H143c-2.91,0-5.91.79-7,2.36a4.61,4.61,0,0,0-.91,2.84h0v7.55a2,2,0,0,1-.16.94l-.16.34h-9.2V8.78h0c-.11-5.54-3.4-8.58-8.14-8.58h-4.63l-.54,3.65h4.61c2.42,0,3.69,2.07,3.69,5.23v5.28l-.33-.16a2.88,2.88,0,0,0-1.09-.15h-8c-.15,1-.34,2.31-.55,3.63h24.47c.82-.15,1.81-.3,2.64-.45a13.91,13.91,0,0,0,5.14.92c5.32,0,8.75-3.55,8.75-9S148.28.12,143.1,0M100.19,19.89h.22c5.31,0,7.78-1.75,7.78-6.08,0-3.11-2.27-5.59-6.08-5.59h-4.9a2.09,2.09,0,0,1-2.33-1.83,2.67,2.67,0,0,1,0-.4c0-1,.36-2.15,2.75-2.15h10.7c.22-1.4.35-2.26.55-3.64H97.76C92.58.2,90,2.36,90,6s2.28,5.44,6.08,5.44H101a2.3,2.3,0,0,1,2.34,2.27v.1c0,.81-.36,2.47-2.75,2.47h-.83l-15.67,0H81.19c-2.41,0-4.11-1.38-4.11-4.54V9.51c0-3.32,1.31-5.38,4.11-5.38h4.69c.2-1.41.33-2.29.53-3.65H80.06c-4.75,0-8,3.19-8.14,8.73h0v2.48c.12,5.54,3.39,8.18,8.14,8.18H84.7l8.49,0Z"/>
                            </svg>
                        </a>
                    </div>

                    <div>
                        <a href="{{ url()->current() }}?{{ http_build_query(array_merge($_GET, ['brand' => 'visa'])) }}"
                           class="payment-option">
                            <img src="{{asset('images/brands/visa.png')}}" alt="Visa" />
                        </a>
                    </div>

                    <div>
                        <a href="{{ url()->current() }}?{{ http_build_query(array_merge($_GET, ['brand' => 'master'])) }}"
                           class="payment-option">
                            <img src="{{asset('images/brands/master.png')}}" alt="MasterCard" />
                        </a>
                    </div>

                </div>
            </div>
            <style>
                .payment-options a:hover {
                    transform: scale(1.0512);
                    transition: all 0.3s ease;
                }
            </style>
        @endif

    </div>


    @include('payments._inc.footer')

    <script>
        function removeBrandParam() {
            // Get the current URL
            let url = new URL(window.location.href);
            url.searchParams.delete('brand');
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
