@extends('layouts.thankyoupage-layout')

@section('title', 'برنامج متكلم')

@section('head')
    <!-- Thankyou -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/thankyou-page.css') }}" />
    <style>
        .registeration_back {
            background-image: url(../images/thankyou/BG-vector.png);
        }
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11283525947"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-11283525947');
    </script>
@endsection

@section('content')

<div class="registeration_done">
            <a href="" class="logo">
                <img src="{{ asset('images/thankyou/white-logo.png') }}" alt="" />
            </a>
        </div>

    <div class="registeration_back">
    <div class="registeration_done">
            <a href="" class="logo">
                <!-- <img src="{{ asset('images/thankyou/white-logo.png') }}" alt="" /> -->
            </a>
        </div>

        <div class="registeration_done">
            <div class="inner_box">
                <img src="{{ asset('images/thankyou/icon-star.png') }}" alt="" />
                <h1>تم التسجيل <span>بـنـجـــاح</span></h1>
                <a href="{{ route('home') }}" class="visit">زيارة الموقع</a>
            </div>
        </div>
    </div>

@endsection
