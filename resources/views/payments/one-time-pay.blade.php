    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>متكلم - الدفع</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <script src="{{env('HYPERPAY_URL')}}/paymentWidgets.js?checkoutId={{$paymentId??data_get($_GET,'checkoutId')}}"></script>
    <body>

    {{env('HYPERPAY_URL')."/paymentWidgets.js?checkoutId=".$paymentId??data_get($_GET,'checkoutId') }}
        <h1 class="text-center" style="text-align: center">ستقوم بدفع مبلغ {{$payment?->package?->total . __('SAR')}}</h1>
        <form action="{{'/checkout/result/'.$_GET['pid'].'/'.$_GET['sid'].'/'}}" class="paymentWidgets" data-brands="MADA VISA MASTER"></form>
    </body>
    </html>
