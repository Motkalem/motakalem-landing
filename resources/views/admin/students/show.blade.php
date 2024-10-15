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

        @if( $student->package)
            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">اسم الباقة</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">
                        {{ $student->package?->name  }}
                    </p>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">نوع الباقة</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext ">
                        {{ $student->package?->payment_type == 'one time' ? 'دفع لمرة واحدة' : 'اقساط'  }}
                    </p>
                </div>
            </div>
            @if($student->package?->payment_type == 'one time')
                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label"> الإجمالي  </label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext ">
                            {{ $student->package?->total   }} {{__('SAR')}}
                        </p>
                    </div>
                </div>
            @else
                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label"> قيمة القسط  </label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext ">
                            {{ $student->package?->installment_value   }} {{__('SAR')}}
                        </p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">   عدد الاقساط  </label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext ">
                            {{ $student->package?->number_of_months   }}  شهر
                        </p>
                    </div>
                </div>
            @endif
        @else

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">    الباقة  </label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext ">
                        {{  'لايوجد باقة مرتبطة' }}
                    </p>
                </div>
            </div>


        @endif
        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">البريد الإلكتروني</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->email }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">نوع الدفع</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->payment_type == 'one time' ? 'دفعة واحدة' : 'اقساط' }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="form-label col-sm-2 col-form-label">إجمالي المبلغ المدفوع</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $student->total_payment_amount . ' ' . __('SAR') }}</p>
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

        @if($student->installmentPayment)
        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <a class="btn btn-primary" href="{{ route('dashboard.installment-payments.show', $student->installmentPayment->id) }}">
                    بيانات الاشتراك
                </a>
            </div>
        </div>
        @elseif($student->payment)
        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <a class="btn btn-primary" href="{{ route('dashboard.payments.show', $student->payment->id) }}">
                    بيانات الدفع
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
