@extends('layouts.front')

@section('title', 'أكادمية متكلم')


@section('menu')
    <li class="active"><a href="#homesection">الرئيسيه</a></li>
    <li><a href="#about-us">معلومات عنا</a></li>
    <li><a href="#features">مميزاتنا</a></li>
    <li><a href="#images">صور</a></li>
    {{-- <li><a href="#blog">مصادر مهمه</a></li> --}}
    <li><a href="#questions">الاسئلة المكرره</a></li>
    <li><a href="{{route('join')}}">الانضمام</a></li>
@endsection


@section('content')

    <!--=================================
 banner -->

 <section class="slider-parallax popup-video-banner bg-overlay-black-50 parallax"
 style="background: url('{{asset('images/bg/home_slider.gif')}}');">
 <div class="slider-content-middle">
   <div class="container">
     <div class="row">
       <div class="col-lg-12 col-md-12">
         <div class="slider-content text-start">
           <p class="text-white mt-20">رحلتك للتحكم بالتأتأه</p>
           <h1 class="text-white"><span class="theme-color"> برنامج متكلم</span>
           </h1>
           <div class="mt-20">
             <a class="popup-youtube" href="https://www.youtube.com/watch?v=Rt0VLUqVv2w"> <span
                 class="ti-control-play"></span> <strong>شاهد الفديو</strong> </a>
           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
 <a class="scroll-down move" title="Scroll Down" href="#about-us"><i></i></a>
</section>

<!--=================================
banner -->


<!--=================================
About-->

<section id="about-us" class="page-section-ptb">
 <div class="container">
   <div class="row">
     <div class="col-lg-6 sm-mb-40">
       <div class="section-title">
         <h6>برنامج التحكم بالتأتأة</h6>
         <h2 class="title-effect">نبذه عن برنامج متكلم</h2>
         <p>يساعدك البرنامج بالتحكم بالتأتأة وإظهار شخصية ناطقة مختلفة من خلال كوادر متحكمة بالتأتأة ومدربين مختصين وخلق بيئة ومجتمع متفاعل ومنتج وخاص بالمتأتأ</p>
       </div>
       <br>
       <h4>كيفية التحكم في التأتأة</h4>
       <div class="row mt-30">
         <div class="col-md-6">
           <ul class="list list-unstyled list-hand">
             <li> استخدام التكنيكات الخاصة بالتحكم بالتأتأة</li>
             <li> ممارسة العادات الجديدة </li>
           </ul>
         </div>
         <div class="col-md-6">
           <ul class="list list-unstyled list-hand">
             <li> الإلتزام بالتعليمات ومهام البرنامج </li>
             <li> تقوية الحالة الذهنية والعقلية </li>
           </ul>
         </div>
       </div>

     </div>
     <div class="col-lg-6 xs-mt-30 xs-mb-30">
       <div class="owl-carousel" data-nav-arrow="true" data-items="1" data-md-items="1" data-sm-items="1"
         data-xs-items="1" data-xx-items="1">
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/a.png')}}" alt="">
         </div>
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/b.png')}}" alt="">
         </div>
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/c.png')}}" alt="">
         </div>
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/d.png')}}" alt="">
         </div>
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/e.png')}}" alt="">
         </div>
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/f.png')}}" alt="">
         </div>
         <div class="item"><img class="img-fluid full-width" src="{{asset('images/about/g.png')}}" alt="">
         </div>
       </div>
     </div>
   </div>
   <div class="row">

     <div class="col-lg-6 col-md-6 col-sm-6 xs-mb-30">
       <div class="feature-text left-icon mt-60 xs-mt-0">
         <div class="feature-icon">
           <span class="ti-target theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5>مهمتنا</h5>
           <p>خلق بيئة خاصة بالمتأتئين لتقديم حلول مبتكرة وموثوقة تعزز الكفاءة والفعالية وتجعلنا في الوقت نفسه عنصرًا أساسيًّا في نجاح وسعادة المتأتئين وممارسة حياتهم اليومية بصورة أفضل</p>
         </div>
       </div>
     </div>

     <div class="col-lg-6 col-md-6 col-sm-6 xs-mb-30">
       <div class="feature-text m left-icon mt-60 xs-mt-0">
         <div class="feature-icon">
           <span class="ti-eye theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5>رؤيتنا</h5>
           <p>نطمح في برنامج متكلم ان نكون الوجهة الأولي والامنة للمتأتأ</p>
         </div>
       </div>
     </div>


   </div>
 </div>
</section>

<!--=================================
About-->


<!--=================================
counter-->

<section class="page-section-ptb bg-overlay-black-90 parallax" style="background: black">
 <div class="container">
   <div class="row">
     <div class="col-lg-3 col-sm-6 sm-mb-30">
       <div class="counter text-white">
        <span class="mo-span-counter"><span class="timer" data-to="1" data-speed="3000">1</span> %</span>
         <label>نسبة المتأتئين في السعودية</label>
       </div>

     </div>
     <div class="col-lg-3 col-sm-6 sm-mb-30">
       <div class="counter text-white">
         <span class="mo-span-counter"><span class="timer" data-to="360" data-speed="3000">360</span> الف</span>
         <label>عدد المتأتئين في السعودية</label>
       </div>
     </div>
     <div class="col-lg-3 col-sm-6 sm-mb-30">
       <div class="counter text-white">
        <span class="mo-span-counter"><span class="timer" data-to="1" data-speed="3000">1</span> %</span>
         <label>نسبة المتأتئين في العالم</label>
       </div>
     </div>
     <div class="col-lg-3 col-sm-6">
       <div class="counter text-white">
        <span class="mo-span-counter"><span class="timer" data-to="80" data-speed="3000">80</span> مليون</span>
         <label>عدد المتأتئين في العالم</label>
       </div>
     </div>
   </div>
 </div>
</section>

<!--=================================
counter-->

<!--=================================
key features  -->

<section id="features" class="page-section-ptb jarallax" data-speed="0.6" data-img-src="{{asset('images/bg/feature.gif')}}">
 <div class="container">
   <div class="row">
     <div class="col-lg-8">
       <div class="section-title">
         <h6 class="text-white"> برنامج التحكم بالتأتأة </h6>
         <h2 class="title-effect text-white">مميزات برنامج متكلم</h2>

       </div>
     </div>
   </div>
   <div class="row">
     <div class="col-lg-4 col-sm-6">
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">محتوى محدث بشكل دوري</h5>
         </div>
       </div>
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">كوادر متحكمة بالتأتأة</h5>
         </div>
       </div>
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">ضمان استرداد الأموال</h5>
         </div>
       </div>
       <div class="feature-text left-icon xs-mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">بيئة خاصة بالمتأتأ</h5>
         </div>
       </div>
     </div>

     <div class="col-lg-4 col-sm-6">
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">بيئة مهيئة للتحكم بالتأتأة</h5>
         </div>
       </div>
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">توزيع كتيبات وتوجيهات</h5>
         </div>
       </div>
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">عضوية لمدة عام</h5>
         </div>
       </div>
       <div class="feature-text left-icon xs-mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">محتوى مجاني</h5>
         </div>
       </div>
     </div>



     <div class="col-lg-4 col-sm-6">
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">٦٠ ساعة تدريبية تغير تفاعلك مع المجتمع</h5>
         </div>
       </div>
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">الإنضمام لبرامج الدعم والتوجيه</h5>
         </div>
       </div>
       <div class="feature-text left-icon mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">الإحتفال بالطلاب المتحكمين بنهاية التدريب</h5>
         </div>
       </div>
       <div class="feature-text left-icon xs-mb-40">
         <div class="feature-icon">
           <span class="ti-thumb-up theme-color" aria-hidden="true"></span>
         </div>
         <div class="feature-info">
           <h5 class="text-back text-white">فرصة (حقيقية) وتأهيلية لتصبح من مدربي البرنامج</h5>
         </div>
       </div>
     </div>


   </div>
 </div>
</section>

<!--=================================
key features -->

<!--=================================
portfolio -->

<section id="images" class="portfolio white-bg page-section-ptb">
 <div class="container">
   <div class="row">
     <div class="col-md-12">
       <div class="section-title text-center">
         <h6> برنامج متكلم</h6>
         <h2 class="title-effect">متأتئين أثروا في العالم</h2>
       </div>
     </div>
   </div>

   <div class="isotope popup-gallery columns-3">
     <div class="grid-item">
       <div class="portfolio-item-2">
         <img src="{{asset('images/portfolio/small/Boyle.png')}}" alt="">
         <div class="portfolio-hover">
           <div class="hover-icon">
             <a class="portfolio-img" href="{{asset('images/portfolio/small/Boyle.png')}}"><i class="fa fa-arrows-alt"></i></a>
           </div>
           <div class="hover-name">
            <a target="_blank" href="https://ar.wikipedia.org/wiki/%D8%B1%D9%88%D8%A8%D8%B1%D8%AA_%D8%A8%D9%88%D9%8A%D9%84">روبرت وليام بويل</a>
          </div>
         </div>
       </div>
     </div>
     <div class="grid-item">
       <div class="portfolio-item-2">
         <img src="{{asset('images/portfolio/small/George-6th.png')}}" alt="">
         <div class="portfolio-hover">
           <div class="hover-icon">
             <a class="portfolio-img" href="{{asset('images/portfolio/small/George-6th.png')}}"><i class="fa fa-arrows-alt"></i></a>
           </div>
           <div class="hover-name">
            <a target="_blank" href="https://ar.wikipedia.org/wiki/%D8%AC%D9%88%D8%B1%D8%AC_%D8%A7%D9%84%D8%B3%D8%A7%D8%AF%D8%B3_%D9%85%D9%84%D9%83_%D8%A7%D9%84%D9%85%D9%85%D9%84%D9%83%D8%A9_%D8%A7%D9%84%D9%85%D8%AA%D8%AD%D8%AF%D8%A9">جورج السادس</a>
          </div>

         </div>
       </div>
     </div>
     <div class="grid-item">
       <div class="portfolio-item-2">
         <img src="{{asset('images/portfolio/small/Kahtani.png')}}" alt="">
         <div class="portfolio-hover">
           <div class="hover-icon">
             <a class="portfolio-img" href="{{asset('images/portfolio/small/Kahtani.png')}}"><i class="fa fa-arrows-alt"></i></a>
           </div>
           <div class="hover-name">
            <a target="_blank" href="https://www.youtube.com/watch?v=1xbrNQoB5LY">محمد عبدالله القحطاني </a>
          </div>
         </div>
       </div>
     </div>
     <div class="grid-item">
       <div class="portfolio-item-2">
         <img src="{{asset('images/portfolio/small/Marlyn.png')}}" alt="">
         <div class="portfolio-hover">
           <div class="hover-icon">
             <a class="portfolio-img" href="{{asset('images/portfolio/small/Marlyn.png')}}"><i class="fa fa-arrows-alt"></i></a>
           </div>
           <div class="hover-name">
            <a target="_blank" href="https://ar.wikipedia.org/wiki/%D9%85%D8%A7%D8%B1%D9%84%D9%8A%D9%86_%D9%85%D9%88%D9%86%D8%B1%D9%88">مارلين مونرو </a>
          </div>
         </div>
       </div>
     </div>
     <div class="grid-item">
       <div class="portfolio-item-2">
         <img src="{{asset('images/portfolio/small/Newton.png')}}" alt="">
         <div class="portfolio-hover">
           <div class="hover-icon">
             <a class="portfolio-img" href="{{asset('images/portfolio/small/Newton.png')}}"><i class="fa fa-arrows-alt"></i></a>
           </div>
           <div class="hover-name">
            <a target="_blank" href="https://ar.wikipedia.org/wiki/%D8%A5%D8%B3%D8%AD%D8%A7%D9%82_%D9%86%D9%8A%D9%88%D8%AA%D9%86">إسحاق نيوتن</a>
          </div>
         </div>
       </div>
     </div>
     <div class="grid-item">
       <div class="portfolio-item-2">
         <img src="{{asset('images/portfolio/small/Steevie-harvey.png')}}" alt="">
         <div class="portfolio-hover">
           <div class="hover-icon">
             <a class="portfolio-img" href="{{asset('images/portfolio/small/Steevie-harvey.png')}}"><i class="fa fa-arrows-alt"></i></a>
           </div>
           <div class="hover-name">
            <a target="_blank" href="https://ar.wikipedia.org/wiki/%D8%B3%D8%AA%D9%8A%D9%81_%D9%87%D8%A7%D8%B1%D9%81%D9%8A">ستيف هارفي</a>
          </div>
         </div>
       </div>
     </div>

   </div>
 </div>
</section>

<!--=================================
portfolio -->













<section class="page-section-ptb  parallax" style="background-image:url('{{asset('images/bg/feature.gif')}}');">
<div class="container">
<div class="row justify-content-center">
 <div class="col-md-8">
   <div class="owl-carousel" data-nav-dots="true" data-items="1" data-md-items="1" data-sm-items="1">
     <div class="item">
       <div class="testimonial dark">
         <div class="testimonial-avatar"> <img alt="" src="{{asset('images/team/avatar.png')}}"> </div>
         <div class="testimonial-info text-white"> من افضل الاماكن اللي ممكن تتدرب فيها</div>
         <div class="author-info"> <strong><span>mohamed samir</span></strong> </div>
       </div>
     </div>
     <div class="item">
       <div class="testimonial dark">
           <div class="testimonial-avatar"> <img alt="" src="{{asset('images/team/avatar.png')}}"> </div>
           <div class="testimonial-info text-white"> مكان اكثر من رائع ومدربين علي اعلا مستوي</div>
           <div class="author-info"> <strong><span>mohamed samir</span></strong> </div>
       </div>
     </div>
     <div class="item">
       <div class="testimonial dark">
         <div class="testimonial-avatar"> <img alt="" src="{{asset('images/team/avatar.png')}}"> </div>
         <div class="testimonial-info text-white"> بجد مكان تحفه وهيفيدك كتير</div>
         <div class="author-info"> <strong><span>mohamed samir</span></strong> </div>
       </div>
     </div>
   </div>
 </div>
</div>
</div>
</section>

















<!--=================================Our Blog -->
{{--
<section id="blog" class="our-blog gray-bg page-section-ptb">
 <div class="container">
   <div class="row">
     <div class="col-lg-12 col-md-12">
       <div class="section-title text-center">
         <h6> أكادمية متكلم</h6>
         <h2 class="title-effect">مصادر مهمه</h2>
       </div>
     </div>
   </div>
   <div class="row">
     <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
       <div class="blog-box blog-2 h-100 white-bg">
         <img class="img-fluid" src="images/about/avatar.png" alt="">
         <div class="blog-info">
           <h4> <a href="#"> ما المقصود بالتأتأة</a></h4>
           <p>التأتأة هي اضطرابات تمس الوظائف اللسانية وتمس الجانب الأدائي اللفظي للإنسان  ...</p>
           <span><i class="fa fa-calendar-check-o"></i> 20 مايو 2023 </span>
           <a class="button icon-color" href="#">قراءة المزيد<i class="fa fa-angle-right"></i></a>
         </div>
       </div>
     </div>

     <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
       <div class="blog-box blog-2 h-100 white-bg">
         <img class="img-fluid" src="images/about/01.jpg" alt="">
         <div class="blog-info">
           <h4> <a href="#"> ما معني التحكم بالتأتأة</a></h4>
           <p>يقصد بها مدى إمكانية وقدرة وثقة المتأتئ على التحكم بالاضطرابات المختلفة التي أصابت  ...</p>
           <span><i class="fa fa-calendar-check-o"></i> 20 مايو 2023 </span>
           <a class="button icon-color" href="#">قراءة المزيد<i class="fa fa-angle-right"></i></a>
         </div>
       </div>
     </div>

     <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
       <div class="blog-box blog-2 h-100 white-bg">
         <img class="img-fluid" src="images/about/01.jpg" alt="">
         <div class="blog-info">
           <h4> <a href="#"> افضل مكان للتحكم في التأتأه </a></h4>
           <p>اذا كنت من داخل المملكة العربية السعودية فافضل مكان لتعلم التأتأه هي أكادمية متكلم   ...</p>
           <span><i class="fa fa-calendar-check-o"></i> 20 مايو 2023 </span>
           <a class="button icon-color" href="#">قراءة المزيد<i class="fa fa-angle-right"></i></a>
         </div>
       </div>
     </div>


   </div>
 </div>
</section> --}}

<!--=================================
Our Blog -->

<!--=================================
contact  -->





<section id="questions" class="white-bg contact-3 clearfix o-hidden">
 <!-- =============================== -->
 <div class="container-fluid">
   <div class="row">
     <div class="col-lg-6">
       <div class="contact-3-info page-section-ptb">
         <div class="clearfix">
           <div class="section-title mb-4">
             <h6> أكادمية متكلم </h6>
             <h2 class="title-effect">الاسئلة المكررة</h2>
           </div>

           <div class="accordion gray plus-icon round mb-30">
             <div class="acd-group">
                 <a href="#" class="acd-heading">ما المقصود بالتأتأة</a>
                 <div class="acd-des">التأتأة هي اضطرابات في سلاسة الكلام والحجاب الحاجز للتنفس تتسم بالاطالة والتكرار والحبسات اللاإرادية اثناء إخراج الاصوات والكلمات وغالبا تصحب معها حركات جسدية لاإرادية </div>
             </div>
             <div class="acd-group">
                 <a href="#" class="acd-heading">ما معني التحكم بالتأتأة</a>
                 <div class="acd-des">يقصد بها مدى إمكانية وقدرة وثقة المتأتئ على التحكم بالاضطرابات المختلفة التي أصابت  سلاسة تعابيره اللفظية وسلاسة تحكمه بتنفسه وسلاسة إيقاع الكلام التي نشأت من أحد الأسباب المختلفة سواء الوراثية أو اللغوية أو النفسية</div>
             </div>
             {{-- <div class="acd-group">
                 <a href="#" class="acd-heading">افضل مكان للتحكم في التأتأه</a>
                 <div class="acd-des">اذا كنت من داخل المملكة العربية السعودية فافضل مكان لتعلم التأتأه هي أكادمية متكلم </div>
             </div> --}}
             <div class="acd-group">
                 <a href="#" class="acd-heading">هل اذا حضر المتأتأ الكورس ولم يستفد هل سيستعيد اموالة</a>
                 <div class="acd-des">نحن على ثقة تامة من أنك ستحصل على نتائج ترضيك وإذا لم تحصل على نتائج فسنرد إليك أموالك بالكامل شرط أن تستخدم كلّ ما نقدمه لك لتتحكم في تأتأتك وتقدّم دليل أنك نفذت كلّ الطرق الموضحة خطوة بخطوة.</div>
             </div>
         </div>




         </div>
       </div>
     </div>
     <div class="col-lg-6">
       <iframe class="w-100 h-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1167944.143543863!2d39.68682086628492!3d20.906873766149378!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15c3d01fb1137e59%3A0xe059579737b118db!2z2KzYr9ipINin2YTYs9i52YjYr9mK2Kk!5e0!3m2!1sar!2seg!4v1684559928151!5m2!1sar!2seg"  style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
     </div>
   </div>
 </div>
</section>








<!--=================================
contact -->


<!--=================================
action box- -->

<section id="join"  class="action-box theme-bg full-width">
 <div class="container">
   <div class="row">
     <div class="col-lg-12 col-md-12 position-relative">
       <div class="action-box-text">
         <h3><strong> الانضمام </strong>  للبرنامج التدريبي</h3>

       </div>
       <div class="action-box-button">
         <a class="button button-border white" href="{{route('join')}}">
           <span>اضغط هنا</span>
           <i class="ti-face-smile"></i>
         </a>
       </div>
     </div>
   </div>
 </div>
</section>

<!--=================================
action box- -->


















@endsection

