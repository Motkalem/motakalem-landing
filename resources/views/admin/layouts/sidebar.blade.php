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
            <li class="nav-item mT-30 {{ Route::currentRouteName() == 'dashboard.index' ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.index') }}">
                    <span class="icon-holder">
                        <i class="c-blue-500 ti-home"></i>
                    </span>
                    <span class="title">داشبورد</span>
                </a>
            </li>
            <li class="nav-item mT-10
            {{ Request::routeIs('dashboard.students.index')
            ||
             Request::routeIs('dashboard.students.show')
            ||
             Request::routeIs('dashboard.students.create')
            ||
             Request::routeIs('dashboard.students.edit')

             ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.students.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-user"></i>
                    </span>
                    <span class="title">{{ __('Students') }}</span>
                </a>
            </li>
            <li class="nav-item mT-10 {{
             Request::routeIs('dashboard.packages.index')
             ||
             Request::routeIs('dashboard.packages.create')
             ||
             Request::routeIs('dashboard.packages.edit')
             ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.packages.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-package"></i>
                    </span>
                    <span class="title">{{ __('packages') }}</span>
                </a>
            </li>

            <li class="nav-item mT-10
            {{ Request::routeIs('dashboard.payments.index')
            ||
             Request::routeIs('dashboard.payments.create')
             ||
             Request::routeIs('dashboard.payments.show')
             ||
            Request::routeIs('dashboard.payments.edit')

             ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.payments.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-money"></i>
                    </span>
                    <span class="title">{{ __('One time Payments') }}</span>
                </a>
            </li>

            {{-- <li class="nav-item mT-10
            {{ Request::routeIs('dashboard.transactions.index')
            ||
             Request::routeIs('dashboard.transactions.show')

             ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.transactions.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-credit-card"></i>
                    </span>
                    <span class="title">{{ __('Transactions') }}</span>
                </a>
            </li> --}}

            <li class="nav-item mT-10
            {{ Request::routeIs('dashboard.installment-payments.index')
            ||
             Request::routeIs('dashboard.installment-payments.show')

             ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.installment-payments.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-credit-card"></i>
                    </span>
                    <span class="title">{{ __('installments payments') }}</span>
                </a>
            </li>

            <li class="nav-item mT-10
            {{ Request::routeIs('dashboard.courses.index') ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.courses.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-credit-card"></i>
                    </span>
                    <span class="title"> الدورات </span>
                </a>
            </li>

            <li class="nav-item mT-10
            {{ Request::routeIs('dashboard.contact-messages.index') ? 'bg-light m-3' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard.contact-messages.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-credit-card"></i>
                    </span>
                    <span class="title">رسائل إتصل بنا</span>
                </a>
            </li>
        </ul>
    </div>
</div>
