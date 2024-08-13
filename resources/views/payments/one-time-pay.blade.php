<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>متكلم - الدفع</title>
  <link rel="stylesheet" href="styles.css">
</head>
<script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$_GET['checkoutId']}}"></script>

<body>
  <h1 class="text-center" style="text-align: center">ستقوم بدفع مبلغ {{$payment->package?->total . __('SAR')}}</h1>
  <form action="{{'/checkout/result/'.$_GET['paymentId'].'/'.$_GET['studentId'].'/'}}"
  class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>

  <script src="scripts.js"></script>
</body>

</html>
