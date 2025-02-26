@extends('admin.layouts.master')

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">تحديث بيانات الطالب</h6>
    <div class="mx-4 text-end">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.students.index') }}">
            رجوع
        </a>
    </div>

    <div class="mT-30">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.profile.update') }}">
            @csrf
            @method('POST')
            <div class="mb-3 row">
                <label for="name" class="form-label col-sm-2 col-form-label">الإسم</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth('dashboard')->user()?->name) }}" placeholder="الإسم" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="form-label col-sm-2 col-form-label">البريد الإلكتروني</label>
                <div class="col-sm-10">
                    <input type="email" disabled class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth('dashboard')->user()?->email) }}" placeholder="البريد الإلكتروني" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Password Field -->
            <div class="mb-3 row">
                <label for="password" class="form-label col-sm-2 col-form-label">كلمة المرور</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="كلمة المرور">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="password_confirmation" class="form-label col-sm-2 col-form-label">تأكيد كلمة المرور</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="تأكيد كلمة المرور">
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">
                        تحديث بيانات الطالب
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection
