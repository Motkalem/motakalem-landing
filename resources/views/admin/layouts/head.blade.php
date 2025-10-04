<!doctype html>
<html lang="fa" dir="rtl">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>  متكلم - {{$title?? '' }}</title>
    <script src="{{asset('admin/scripts/main-script.js')}}"></script>
    <style>
        #laravel-notify .notify {
            justify-content: start;
            z-index: 9999;
        }

    </style>
    <script defer="defer" src="{{asset('admin/main.js')}}" type="7f394f208341b58690e017dd-text/javascript"></script>
    <link href="{{asset('admin/style.css')}}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <style>
        p,div,td,span,h1,h2,h3,h4,h5,h6,button,label {
            font-family: "Cairo", sans-serif !important;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings:
                "slnt"0;
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

    @notifyCss
    @stack('styles')
</head>
