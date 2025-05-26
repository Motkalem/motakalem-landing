@extends('admin.layouts.master')

@section('content')
    <div class="p-20 bgc-white bd">
        <h6 class="c-grey-900">تفاصيل المريض</h6>
        <div class="mx-4 text-end">
            <a class="px-4 btn btn-info" href="{{ route('dashboard.center.center-patients.index') }}">
                رجوع
            </a>
        </div>
        <div class="mT-30">
            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">الاسم</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->name }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">رقم الهاتف</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->mobile_number }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label"> البريد الالكتروني  </label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->email }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">   رقم الهوية  </label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->id_number }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">   تاريخ إنتهاء الهوية  </label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->id_end_date }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">العمر</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->age }}</p>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="form-label col-sm-2 col-form-label">المصدر</label>
                <div class="col-sm-10">
                    <p class="form-control-plaintext">{{ $patient->source }}</p>
                </div>
            </div>


            @php
                $transactionData = json_decode($patient->transaction_data, true);
                $firstKey = is_array($transactionData) ? array_key_first($transactionData) : null;
                $transaction = $firstKey ? $transactionData[$firstKey] : null;
            @endphp

            @if ($transaction)
                <hr>
                <h6 class="c-grey-900">معلومات الدفع</h6>

                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">الحالة</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext text-danger">{{ $transaction['title'] ?? 'غير متوفر' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">حامل البطاقة</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ $transaction['card']['holder'] ?? 'غير متوفر' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">آخر 4 أرقام</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ $transaction['card']['last4Digits'] ?? 'غير متوفر' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">الوصف</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ $transaction['result']['description'] ?? 'غير متوفر' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">المبلغ</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ $transaction['amount'] ?? 'غير متوفر' }} {{ $transaction['currency'] ?? '' }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">المعرف</label>
                    <div class="col-sm-10">
                        <p class="form-control-plaintext">{{ $transaction['id'] ?? 'غير متوفر' }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
