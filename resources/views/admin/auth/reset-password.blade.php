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
                        <form method="POST" action="{{ route('dashboard.password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <h2>تعيين كلمة المرور</h2>

                            <div class="form-group">
                                <label for="email">البريد الإلكتروني:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password">كلمة المرور  :</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">  تأكيد كلمة المرور:</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">حفظ</button>
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
