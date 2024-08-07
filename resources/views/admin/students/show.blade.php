@extends('admin.layouts.master')

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">تفاصيل الطالب</h6>
    <div class="mx-4 text-end">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.students.index') }}">
            رجوع
        </a>
    </div>
    <div class="mT-30">
        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">الإسم</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->name }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">البريد الإلكتروني</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->email }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">نوع الدفع</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->payment_type }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">إجمالي المبلغ المدفوع</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->total_payment_amount }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">العمر</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->age }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">مدفوع</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->is_paid ? 'نعم' : 'لا' }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">الهاتف</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->phone }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">المدينة</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->city }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
