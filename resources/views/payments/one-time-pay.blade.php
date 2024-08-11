<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HTML5 Boilerplate</title>
  <link rel="stylesheet" href="styles.css">
</head>
<script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{data_get($responseData, 'id')}}"></script>

<body>
  <h1>Pay</h1>
  <form action="/checkout/result" class="paymentWidgets" data-brands="VISA MASTER AMEX"></form>

  <script src="scripts.js"></script>
</body>

</html>
