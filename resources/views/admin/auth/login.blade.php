@extends('admin.auth.auth-layout')

@section('content')
    <div class="col-12 col-md-8 col-lg-4">
        <h3 class="text-center mb-4">تسجيل الدخول</h3>
        <div class="card">
            <div class="card-body">
                <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('dashboard.login.submit') }}">
                    @csrf

                    <div class="form-group">
                        <label class="control-label" for="email">Email</label>
                        <div>
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}" id="email">
                            @error('email')

                                 <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="password">كلمة المرور</label>
                        <div>
                            <input type="password" class="form-control" name="password" id="password">
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> تذكرني
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <button type="submit" class="btn btn-block btn-primary">
                                تسجيل الدخول
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <div class="text-center"><a href="{{route('dashboard.password.request')}}">هل نسيت كلمة المرور ؟</a></div>
    </div>
@endsection
