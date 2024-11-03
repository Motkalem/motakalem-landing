    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>متكلم - الدفع</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <script src="{{env('HYPERPAY_URL')}}/paymentWidgets.js?checkoutId={{$checkoutId}}"></script>
    <body>
        <h1 class="text-center" style="text-align: center">ستقوم بدفع مبلغ  {{ $amount}} @lang('SAR')</h1>
        <form action="/recurring/result/{{request()->paymentId}}" class="paymentWidgets" data-brands="MADA VISA MASTER"></form>
    </body>
    </html>
