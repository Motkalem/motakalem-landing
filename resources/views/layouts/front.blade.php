<!DOCTYPE html>
<html lang="ar" class="rtl" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="برنامج متكلم تأتأه اكادمية " />
    <meta name="description" content="@yield('title', 'برنامج متكلم')" />
    <meta name="author" content="motkalem.sa" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>@yield('title', 'برنامج متكلم')</title>

    <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-PTCJSTM2');
        </script>
    <!-- End Google Tag Manager -->

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />

    <!-- font -->
    {{-- <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,500,500i,600,700,800,900|Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">  --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@600&display=swap" rel="stylesheet">


    <!-- Plugins -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins-css.css') }}" />

    <!-- Typography -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/typography.css') }}" />

    <!-- Shortcodes -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/shortcodes/shortcodes.css') }}" />

    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style-rtl.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-style-rtl.css') }}" />


    <!-- Responsive -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}" />

    @yield('head')

</head>

<body class="rtl">

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PTCJSTM2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <div class="wrapper">

        <!--=================================
 preloader -->

        {{-- <div id="pre-loader">
      <img src="images/pre-loader/loader-01.svg" alt="">
    </div>  --}}

        <!--=================================
 preloader -->


        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif






        <section id="homesection"></section>

        <!--================================= header -->

        <header id="header" class="header default">
            <div class="menu" id="onepagenav">
                <!-- menu start -->
                <nav id="menu" class="mega-menu">
                    <!-- menu list items container -->
                    <section class="menu-list-items">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 position-relative">
                                    <!-- menu logo -->
                                    <ul class="menu-logo">
                                        <li>
                                            <a href="{{ route('home') }}"><img loading="lazy" id="logo_img"
                                                    class="img-fluid" src="{{ asset('images/logo.png') }}"
                                                    alt=""> </a>
                                        </li>
                                    </ul>
                                    <!-- menu links -->
                                    <div class="menu-bar">
                                        <ul class="menu-links">
                                            @yield('menu')
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </nav>
                <!-- menu end -->
            </div>
        </header>

        <!--=================================
 header -->




        @yield('content')






        <!-- wpp-btn-mobile -->
        <div class="phone-call cbh-phone cbh-green cbh-show  cbh-static" id="clbh_phone_div" style=""><a
                id="WhatsApp-button" href="https://wa.me/966537340614" target="_blank" class="phoneJs"
                title="WhatsApp 360imagem">
                <div class="cbh-ph-circle"></div>
                <div class="cbh-ph-circle-fill"></div>
                <div class="cbh-ph-img-circle1"></div>
            </a></div>
        <!-- wpp-btn-mobile -->





        <!--=================================
 footer -->

        <footer class="footer footer-one-page page-section-pt black-bg">
            <div class="container">
                <div class="row text-center">
                    <div class="col-lg-4 col-md-4">
                        <div class="contact-add mb-30">
                            <div class="text-center">
                                <i class="ti-map-alt text-white"></i>
                                <h5 class="mt-15">العنوان</h5>
                                <p><a href="#">السعودية</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="contact-add mb-30">
                            <div class="text-center">
                                <i class="ti-mobile text-white"></i>
                                <h5 class="mt-15">الجوال</h5>
                                <p><a dir="ltr" href="tel:00966537340614">+966 53 734 0614</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="contact-add mb-30">
                            <div class="text-center">
                                <i class="ti-email text-white"></i>
                                <h5 class="mt-15">الايميل</h5>
                                <p><a href="mailto:info@motkalem.sa">info@motkalem.sa</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-widget mt-20">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <p class="mt-15"> جميع الحقوق محفوظه لدي
                                </span> <a href="{{ route('home') }}"> برنامج متكلم </a> </p>
                        </div>
                        <div class="col-lg-6 col-md-6 ">
                            <div class="footer-widget-social text-center text-md-end">
                                <ul>
                                    <li><a target="_blank"
                                            href="https://www.facebook.com/MotkalemSA?mibextid=LQQJ4d"><i
                                                class="fa fa-facebook"></i></a></li>
                                    <li><a target="_blank"
                                            href="https://twitter.com/motkalemsa/status/1687890975023091712?s=20"><i
                                                class="fa fa-twitter"></i></a></li>
                                    <li><a target="_blank"
                                            href="https://instagram.com/motkalemsa?igshid=OGQ5ZDc2ODk2ZA=="><i
                                                class="fa fa-instagram"></i></a></li>
                                    <li><a target="_blank" href="https://t.snapchat.com/K9XVxtSx"><i
                                                class="fa fa-snapchat-ghost"></i></a></li>
                                    <li>
                                        <a target="_blank"
                                            href="https://www.tiktok.com/@motkalemsa?_t=8fIOMqLsHH4&_r=1">
                                            <i>
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                    viewBox="0 0 448 512" fill="#ffffff80">
                                                    <path
                                                        d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z" />
                                                </svg>
                                            </i>

                                        </a>
                                    </li>





                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!--=================================
 footer -->

    </div>

    <div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>أعلي</span></a>
    </div>

    <!--=================================
 jquery -->

    <!-- jquery -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

    <!-- plugins-jquery -->
    <script src="{{ asset('js/plugins-jquery.js') }}"></script>


    <script>
        var plugin_path = 'js/';
    </script>



    <!-- mo custom -->

    {{-- <script src="{{asset('js/magnific-popup/jquery.magnific-popup.min.js')}}"></script> --}}

    <!-- custom -->
    <script src="{{ asset('js/custom.js') }}"></script>

    <script src="{{ asset('js/join.js') }}"></script>

</body>

</html>
