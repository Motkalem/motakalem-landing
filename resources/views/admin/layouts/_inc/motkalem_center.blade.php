<li class="w-100 "> <hr class="w-100"/> </li>
<li class="nav-item px-3 text-uppercase fw-bold text-muted mt-3 mb-2"> مركز متكلم </li>
<li class="nav-item mT-10 {{
             Request::routeIs('dashboard.center.center-packages.index')
             ||
             Request::routeIs('dashboard.center.center-packages.create')
             ||
             Request::routeIs('dashboard.center.center-packages.edit')
             ? 'bg-light m-3' : '' }}">
    <a class="sidebar-link" href="{{ route('dashboard.center.center-packages.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-package"></i>
                    </span>
        <span class="title">{{ __('Center packages') }}</span>
    </a>
</li>

<li class="nav-item mT-10
            {{ Request::routeIs('dashboard.center-patients.index')
            ||
             Request::routeIs('dashboard.center-patients.show')
            ||
             Request::routeIs('dashboard.center-patients.create')
            ||
             Request::routeIs('dashboard.center-patients.edit')

             ? 'bg-light m-3' : '' }}">
    <a class="sidebar-link" href="{{ route('dashboard.center.center-patients.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-user"></i>
                    </span>
        <span class="title">{{ __('Patients') }}</span>
    </a>
</li>

<li class="nav-item mT-10
            {{ Request::routeIs('dashboard.center.center-payments.index')
            ||
             Request::routeIs('dashboard.center.center-payments.show')

             ? 'bg-light m-3' : '' }}">
    <a class="sidebar-link" href="{{ route('dashboard.center.center-payments.index') }}">
                    <span class="icon-holder">
                        <i class="c-indigo-500 ti-credit-card"></i>
                    </span>
        <span class="title">{{ __('center payments') }}</span>
    </a>
</li>
