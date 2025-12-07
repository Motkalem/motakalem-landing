<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left">
            <li><a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);"><i class="ti-menu"></i></a></li>

            @yield('search')
        </ul>
        <ul class="nav-right">
            {{-- <li class="notifications dropdown"><span class="counter bgc-red">۳</span> <a href class="dropdown-toggle no-after" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti-bell"></i></a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li class="pX-20 pY-15 bdB"><i class="ti-bell pL-10"></i> <span class="fsz-sm fw-600 c-grey-900">اعلان‌ها</span></li>
                    <li>
                        <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm">

                            <li><a href class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                    <div class="peer mR-15"><img class="w-3r bdrs-50p" src="../../randomuser.me/api/portraits/men/3.jpg" alt></div>
                                    <div class="peer peer-greed"><span><span class="fw-500">شخص ناشناس دیگر</span> <span class="c-grey-600">بر روی <span class="text-dark">ویدیو</span> شما نظر قرار داد.</span></span>
                                        <p class="m-0"><small class="fsz-xs">۱۰ دقیقه قبل</small></p>
                                    </div>
                                </a></li>
                        </ul>
                    </li>
                    <li class="pX-20 pY-15 ta-c bdT"><span><a href class="c-grey-600 cH-blue fsz-sm td-n">نمایش تمام اعلان‌ها <i class="ti-angle-right fsz-xs mL-10"></i></a></span></li>
                </ul>
            </li> --}}
            {{-- <li class="notifications dropdown"><span class="counter bgc-blue">۳</span> <a href class="dropdown-toggle no-after" data-bs-toggle="dropdown"><i class="ti-email"></i></a> --}}
                <ul class="dropdown-menu">
                    <li class="pX-20 pY-15 bdB"><i class="ti-email pR-10"></i> <span class="fsz-sm fw-600 c-grey-900">ایمیل‌ها</span></li>
                    <li>
                        <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm">


                            <li><a href class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                                    <div class="peer mR-15"><img class="w-3r bdrs-50p" src="../../randomuser.me/api/portraits/men/3.jpg" alt></div>
                                    <div class="peer peer-greed">
                                        {{-- <div>
                                            <div class="peers jc-sb fxw-nw mB-5">
                                                <div class="peer">
                                                    <p class="fw-500 mB-0">شخص ناشناس دیگر</p>
                                                </div>
                                                <div class="peer"><small class="fsz-xs">۲۵ دقیقه قبل</small></div>
                                            </div><span class="c-grey-600 fsz-sm">می خواهید تولید کننده داده سفارشی شده خود را برای برنامه خود ایجاد کنید...</span>
                                        </div> --}}
                                    </div>
                                </a></li>
                        </ul>
                    </li>
                    <li class="pX-20 pY-15 ta-c bdT"><span><a href="email.html" class="c-grey-600 cH-blue fsz-sm td-n">نمایش تمام ایمیل‌ها <i class="fs-xs ti-angle-right mL-10"></i></a></span></li>
                </ul>
            </li>
            <li class="dropdown"><a href class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-bs-toggle="dropdown">
                    <div class="peer mR-10">
                        <img class="w-2r bdrs-50p" src="../../randomuser.me/api/portraits/men/10.jpg" alt>
                    </div>
                    <div class="peer"><span class="fsz-sm c-grey-900"> {{auth('dashboard')->user()->name }}   </span></div>
                </a>
                <ul class="dropdown-menu fsz-sm">

                    <li>
                        <a href="{{route('dashboard.profile.edit')}}" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                            <i class="ti-user mR-10"></i> <span>الملف الشخضي</span>
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>

                    <li>
                        <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti-power-off mR-10"></i> <span>تسجيل الخروج</span>
                        </a>
                    </li>


                </ul>
            </li>
        </ul>
    </div>
</div>
