@extends('admin.layouts.master')

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">إضافة طالب جديد</h6>
    <div class="mx-4 text-end">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.students.index') }}">
            رجوع
        </a>
    </div>
    <div class="mT-30">
        <form method="POST" action="{{ route('dashboard.students.store') }}">
            @csrf
            <div class="mb-3 row">
                <label for="name" class="form-label col-sm-2 col-form-label">الإسم</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="الإسم" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="email" class="form-label col-sm-2 col-form-label">البريد الإلكتروني</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="البريد الإلكتروني" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- <div class="mb-3 row">
                <label for="payment_type" class="form-label col-sm-2 col-form-label">نوع الدفع</label>
                <div class="col-sm-10">
                    <select class="form-select @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type" required>
                        <option value="">اختر نوع الدفع</option>
                        <option value="{{\App\Models\Student::ONE_TIME}}" {{ old('payment_type') == \App\Models\Student::ONE_TIME ? 'selected' : '' }}>دفعة واحدة</option>
                        <option value="{{\App\Models\Student::INSTALLMENTS}}" {{ old('payment_type') == \App\Models\Student::INSTALLMENTS ? 'selected' : '' }}>  اقساط</option>
                        <!-- Add more options as needed -->
                    </select>
                    @error('payment_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}

            {{-- <div class="mb-3 row">
                <label for="total_payment_amount" class="form-label col-sm-2 col-form-label">إجمالي المبلغ المدفوع</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('total_payment_amount') is-invalid @enderror" id="total_payment_amount" name="total_payment_amount" placeholder="إجمالي المبلغ المدفوع" step="0.01" value="{{ old('total_payment_amount') }}">
                    @error('total_payment_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}

            <div class="mb-3 row">
                <label for="age" class="form-label col-sm-2 col-form-label">العمر</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" placeholder="العمر" value="{{ old('age') }}">
                    @error('age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="phone" class="form-label col-sm-2 col-form-label">الهاتف</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="الهاتف" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="city" class="form-label col-sm-2 col-form-label">المدينة</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" placeholder="المدينة" value="{{ old('city') }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">
                        حفظ الطالب
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
