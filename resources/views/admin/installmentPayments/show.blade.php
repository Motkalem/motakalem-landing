@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">تفاصيل المعاملة</h6>
    <div class="mx-4 text-end">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.installment-payments.index') }}">
            رجوع
        </a>
    </div>
    <div class="mT-30">
        <div class="mb-3 row">
            <label for="installmentPayment_id" class="form-label col-sm-2 col-form-label">رقم المعاملة</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $installmentPayment->id }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="client_pay_order_id" class="form-label col-sm-2 col-form-label">رقم الدفعة</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $installmentPayment->payment_id }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="success" class="form-label col-sm-2 col-form-label">النجاح</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext {{ $installmentPayment->success =='true' ? 'text-success' : 'text-danger' }}">{{ $installmentPayment->success =='true' ? 'نجاح' : 'فشل' }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="amount" class="form-label col-sm-2 col-form-label">المبلغ</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $installmentPayment->amount .' '.__('SAR') }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="amount" class="form-label col-sm-2 col-form-label">ملاحظات</label>
            <div class="col-sm-10">
                <h2 class="text-bold lead">{{data_get(data_get( $installmentPayment->data, 'result'), 'description')  }}

                </p>
            </div>
        </div>

    </div>
</div>
@endsection
