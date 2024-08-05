<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="/admin/dashboard">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer">
                                <div class="logo"><img src="assets/static/images/logo.png" alt></div>
                            </div>
                            <div class="peer peer-greed">
                                <h5 class="lh-1 mB-0 logo-text"> لوحة التحكم الرئيسية  </h5>
                            </div>
                        </div>
                    </a></div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle">
                        <a href class="td-n">
                        <i class="ti-arrow-circle-left"></i></a></div>
                </div>
            </div>
        </div>
        <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item mT-30 actived">
                <a class="sidebar-link" href="{{route('dashboard.index')}}"><span class="icon-holder">
                    <i class="c-blue-500 ti-home"></i>
                </span><span class="title">داشبورد</span>
                </a>
             </li>

             <li class="nav-item mT-10 ">
                <a class="sidebar-link" href="{{route('dashboard.packages.index')}}"><span class="icon-holder">
                    <i class="c-indigo-500 ti-bar-chart"></i>
                </span><span class="title">{{__('packages')}}</span>
                </a>
             </li>
        </ul>
    </div>
</div>
