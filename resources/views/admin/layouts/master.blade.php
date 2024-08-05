@include('admin.layouts.head')
<body class="app">

    <div>

        @include('admin.layouts.sidebar')

        <div class="page-container">

            @include('admin.layouts.navbar')
            <main class="main-content bgc-grey-100">
                <div id="mainContent">

                        @yield('content')
                </div>
            </main>
            @include('admin.layouts.footer')
        </div>
    </div>
    <x-notify::notify />
    <script src="{{asset('admin/scripts/rocket-loader.min.js')}}" data-cf-settings="7f394f208341b58690e017dd-|49" defer></script>

    @notifyJs

    @stack('scripts')


</body>
 </html>
