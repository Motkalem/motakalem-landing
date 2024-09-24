    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>متكلم - الدفع</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    {{var_dump($_GET)}}
    {{-- <script src="https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId={{$_GET['checkoutId']}}"></script> --}}
    <body>
        <h1 class="text-center" style="text-align: center">ستقوم بدفع مبلغ 5</h1>
        <form action="/recurring/result" class="paymentWidgets" data-brands="MADA VISA MASTER"></form>
    </body>
    </html>
