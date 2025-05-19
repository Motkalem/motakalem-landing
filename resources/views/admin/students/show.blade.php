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
                            {{ $student->package?->total   }} <span class="riyal-symbol">R</span>
                        </p>
                    </div>
                </div>
            @else
                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label"> قيمة القسط  </label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext ">
                            {{ $student->package?->installment_value   }} <span class="riyal-symbol">R</span>
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

        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
            @if($student->installmentPayment)
                <a class="btn btn-primary" href="{{ route('dashboard.installment-payments.show', $student->installmentPayment->id) }}">
                    بيانات الاشتراك
                    <i class="fa fa-info"></i>
                </a>
              @endif
                @if($student->parentContract)
                    <a class="btn btn-success" href="{{ route('dashboard.download-contract', $student->parentContract->id) }}">
                        تحميل العقد
                       <i class="fa fa-download"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="row">
            @if($student->payment)
                    <a class="btn btn-primary" href="{{ route('dashboard.payments.show', $student->payment->id) }}">
                        بيانات الدفع
                    </a>
            @endif

        </div>
    </div>
</div>
@endsection
