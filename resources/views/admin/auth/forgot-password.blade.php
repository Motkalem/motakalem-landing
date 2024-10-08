@extends('admin.auth.auth-layout')

@section('content')
    <div class="col-12 col-md-8 col-lg-6">
            <h3 class="text-center mb-4">إعادة تعيين كلمة المرور</h3>
            <div class="nav-steps-wrapper">
                    <div class="nav-item active">
                        <p class="nav-link active" href="#tab_1" data-toggle="tab"><strong> </strong> تأكيد البريد الإلكتروني</p></div>
            </div>
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('dashboard.password.email') }}">
                            @csrf

                            <div class="form-group">
                                <label class="control-label" for="email">البريد الالكتروني</label>

                                <div>
                                    <input type="email" class="form-control" name="email" id="email" value="">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div>
                                    <button type="submit" class="btn btn-block btn-primary">
                                        إرسال رابط إعادة تعيين كلمة المرور
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>


                </div>

            </div>

            <div class="text-center mt-4">
                <a href="{{route('dashboard.login')}}">تسجيل الدخول</a>

            </div>

     </div>
@endsection
