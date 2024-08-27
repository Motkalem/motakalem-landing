@extends('admin.layouts.master')

@push('styles')
   <style>

.timeline_area {
    position: relative;
    z-index: 1;
}
.single-timeline-area {
    position: relative;
    z-index: 1;
    padding-left: 180px;
}
@media only screen and (max-width: 575px) {
    .single-timeline-area {
        padding-left: 100px;
    }
}
.single-timeline-area .timeline-date {
    position: absolute;
    width: 180px;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -ms-grid-row-align: center;
    align-items: center;
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    padding-right: 60px;
}
@media only screen and (max-width: 575px) {
    .single-timeline-area .timeline-date {
        width: 100px;
    }
}
.single-timeline-area .timeline-date::after {
    position: absolute;
    width: 3px;
    height: 100%;
    content: "";
    background-color: #ebebeb;
    top: 0;
    right: 30px;
    z-index: 1;
}
.single-timeline-area .timeline-date::before {
    position: absolute;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background-color: #f1c40f;
    content: "";
    top: 50%;
    right: 26px;
    z-index: 5;
    margin-top: -5.5px;
}
.single-timeline-area .timeline-date p {
    margin-bottom: 0;
    color: #020710;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 500;
}
.single-timeline-area .single-timeline-content {
    position: relative;
    z-index: 1;
    padding: 30px 30px 25px;
    border-radius: 6px;
    margin-bottom: 15px;
    margin-top: 15px;
}
@media only screen and (max-width: 575px) {
    .single-timeline-area .single-timeline-content {
        padding: 20px;
    }
}
.single-timeline-area .single-timeline-content .timeline-icon {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
    width: 30px;
    height: 30px;
    background-color: #f1c40f;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 30px;
    flex: 0 0 30px;
    text-align: center;
    max-width: 30px;
    border-radius: 50%;
    margin-right: 15px;
}
.single-timeline-area .single-timeline-content .timeline-icon i {
    color: #ffffff;
    line-height: 30px;
}
.single-timeline-area .single-timeline-content .timeline-text h6 {
    -webkit-transition-duration: 500ms;
    transition-duration: 500ms;
}
.single-timeline-area .single-timeline-content .timeline-text p {
    font-size: 13px;
    margin-bottom: 0;
}
.single-timeline-area .single-timeline-content:hover .timeline-icon,
.single-timeline-area .single-timeline-content:focus .timeline-icon {
    background-color: #020710;
}
.single-timeline-area .single-timeline-content:hover .timeline-text h6,
.single-timeline-area .single-timeline-content:focus .timeline-text h6 {
    color: #3f43fd;
}
    </style>


@endpush

@section('content')


<div class="container-fluid">
    <div class="gap-2 row" >
        <!-- Transaction Details Section -->
        <div class="p-20 bgc-white bd">
            <h6 class="c-grey-900 h3">تفاصيل الدفعة</h6>
            <div class="mx-4 text-end">
                <a class="px-4 btn btn-info" href="{{ route('dashboard.payments.index') }}">
                    رجوع
                </a>
            </div>
            <div class="mT-30">
                <div class="mb-3 row">
                    <label for="installmentPayment_id" class="form-label col-sm-4 col-form-label">رقم الدفعة</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $payment->id }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="client_pay_order_id" class="form-label col-sm-4 col-form-label">الطالب</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="{{ route('dashboard.students.show', $payment?->student?->id) }}">
                                {{ $payment?->student?->name }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="client_pay_order_id" class="form-label col-sm-4 col-form-label">الباقه</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="{{ route('dashboard.packages.edit', $payment?->package?->id) }}">
                                {{ $payment?->package?->name }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="amount" class="form-label col-sm-4 col-form-label">المبلغ الكلي  </label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $payment->package?->total . ' ' . __('SAR') }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="amount" class="form-label col-sm-4 col-form-label">تاريخ الدفع</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $payment->created_at }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="amount" class="form-label col-sm-4 col-form-label">اخر تحديث</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $payment->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>

      <!-- Notification Log Section -->
        <div class="p-20 bgc-white bd">
            <div class="mT-30">
                <h6 class="c-grey-900 h3">سجل الإشعارات</h6>
                <section class="mt-2 timeline_area section_padding_130">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="apland-timeline-area">
                                    @foreach($payment->transactions->sortByDesc('created_at') as $transaction)
                                    <div class="single-timeline-area @if($loop->first) shadow @endif">
                                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                            <p>{{ $transaction->created_at }} </p>
                                        </div>
                                        <div class="row">
                                            <div class="single-timeline-content d-flex " data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInLeft;">
                                                <div class="timeline-icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                                                <div class="m-2 timeline-text">
                                                    <h2 class="px-10 h4 fw-500">{{ $transaction->title }}</h2>
                                                    <h6> {{ $transaction->type }} </h6>

                                                    <p>الوصف: {{ data_get($transaction->data, 'result.description') }}</p>
                                                    <p>المبلغ: {{ data_get($transaction->data, 'amount') }} {{ data_get($transaction->data, 'currency') }}</p>
                                                    <p>البريد الإلكتروني: {{ data_get($transaction->data, 'customer.email') }}</p>
                                                    <p>اسم حامل البطاقة: {{ data_get($transaction->data, 'card.holder') }}</p>
                                                    <p>نوع البطاقة: {{ data_get($transaction->data, 'card.type') }}</p>
                                                    <p>تاريخ انتهاء البطاقة: {{ data_get($transaction->data, 'card.expiryMonth') }}/{{ data_get($transaction->data, 'card.expiryYear') }}</p>
                                                    <p>آخر 4 أرقام من البطاقة: {{ data_get($transaction->data, 'card.last4Digits') }}</p>
                                                    <p>علامة الدفع: {{ data_get($transaction->data, 'paymentBrand') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </div>
</div>



@endsection

