{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('join') }}"><i class="nav-icon la la-puzzle-piece"></i> {{\App\CPU\Mhelper::t('Joins')}}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('contact') }}"><i class="nav-icon la la-phone-alt"></i> {{\App\CPU\Mhelper::t('Contacts')}}</a></li>


<li class="nav-item"><a class="nav-link" href="{{backpack_url('user')}}"><i class="nav-icon la la-user-astronaut"></i> {{\App\CPU\Mhelper::t('Users')}}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('client-pay-order') }}"><i class="nav-icon la la-user-alt"></i> {{\App\CPU\Mhelper::t('students')}} </a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('transaction') }}"><i class="nav-icon la la-dollar"></i>{{\App\CPU\Mhelper::t('Transactions')}} </a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('dashboard.index') }}"><i class="nav-icon la la-dollar"></i>
    {{ __('Dashboard')}}
</a></li>
