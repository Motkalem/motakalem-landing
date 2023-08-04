<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="برنامج متكلم تأتأه اكادمية " />
    <meta name="description" content="@yield('title','برنامج متكلم')" />
    <meta name="author" content="motkalem.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>@yield('title','برنامج متكلم')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" />

    <!-- font -->
     {{-- <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,500,500i,600,700,800,900|Poppins:200,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900">  --}}
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@600&display=swap" rel="stylesheet">

    <!-- Plugins -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/plugins-css.css')}}" />

    <!-- Typography -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/typography.css')}}" />

    <!-- Shortcodes -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/shortcodes/shortcodes.css')}}" />

    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom-style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/style-rtl.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom-style-rtl.css')}}" />


    <!-- Responsive -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/responsive.css')}}" />

    @yield('head')

  </head>

  <body class="rtl">

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
          <li>{{$error}}</li>
          @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif


  @if(session()->has('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>{{session('success')}}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif




   @yield('content')


  <!-- wpp-btn-mobile -->
  <div class="phone-call cbh-phone cbh-green cbh-show  cbh-static" id="clbh_phone_div" style=""><a id="WhatsApp-button" href="https://wa.me/966537340614" target="_blank" class="phoneJs" title="WhatsApp 360imagem"><div class="cbh-ph-circle"></div><div class="cbh-ph-circle-fill"></div><div class="cbh-ph-img-circle1"></div></a></div>
  <!-- wpp-btn-mobile -->

      <!--=================================
   footer -->

    </div>

    <div id="back-to-top"><a class="top arrow" href="#top"><i class="fa fa-angle-up"></i> <span>أعلي</span></a></div>

    <!--=================================
   jquery -->

    <!-- jquery -->
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

    <!-- plugins-jquery -->
    <script src="{{asset('js/plugins-jquery.js')}}"></script>


    <script>var plugin_path = 'js/';</script>



    <!-- mo custom -->

    {{-- <script src="{{asset('js/magnific-popup/jquery.magnific-popup.min.js')}}"></script> --}}

    <!-- custom -->
    <script src="{{asset('js/custom.js')}}"></script>

    <script src="{{asset('js/join.js')}}"></script>




  </body>

  </html>
