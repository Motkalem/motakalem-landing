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
        <div class="p-20 col-md-5 bgc-white bd">
            <h6 class="c-grey-900">تفاصيل المعاملة</h6>
            <div class="mx-4 text-end">
                <a class="px-4 btn btn-info" href="{{ route('dashboard.installment-payments.index') }}">
                    رجوع
                </a>
            </div>
            <div class="mT-30">
                <div class="mb-3 row">
                    <label for="installmentPayment_id" class="form-label col-sm-4 col-form-label">رقم المعاملة</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $installmentPayment->id }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="client_pay_order_id" class="form-label col-sm-4 col-form-label">الطالب</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="{{ route('dashboard.students.show', $installmentPayment?->student?->id) }}">
                                {{ $installmentPayment?->student?->name }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="client_pay_order_id" class="form-label col-sm-4 col-form-label">رقم التسجيل (HyperPay)</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            {{ $installmentPayment?->registration_id }}
                        </p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="amount" class="form-label col-sm-4 col-form-label">مقدار القسط</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $installmentPayment->package?->installment_value . ' ' . __('SAR') }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="amount" class="form-label col-sm-4 col-form-label">تاريخ الاشتراك</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $installmentPayment->created_at }}</p>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="amount" class="form-label col-sm-4 col-form-label">اخر تحديث</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $installmentPayment->updated_at }}</p>
                    </div>
                </div>

                @if($installmentPayment->canceled == 0)
                <div class="text-end">
                    <button class="px-4 btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelSubscriptionModal"
                     data-url="{{route('dashboard.cancel-schedule', $installmentPayment->payment_id)}}">
                        إلغاء الاشتراك
                    </button>
                </div>
                @else
                <p class="px-4 btn text-danger">
                   تم إلغاء الإشتراك
                </p>
                @endif
            </div>
        </div>


      <!-- Notification Log Section -->
        <div class="p-20 col-md-5 bgc-white bd">
            <div class="mT-30">
                <h6 class="c-grey-900">سجل الإشعارات</h6>
                <section class="mt-2 timeline_area section_padding_130">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="apland-timeline-area">
                                    @foreach($installmentPayment->hyperpayWebHooksNotifications->sortByDesc('created_at') as $notification)
                                    <div class="single-timeline-area @if($loop->first) shadow @endif">
                                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                            <p>{{ $notification->created_at }} </p>
                                        </div>
                                        <div class="row">
                                            <div class="single-timeline-content d-flex " data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInLeft;">
                                                <div class="timeline-icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                                                <div class="m-2 timeline-text">
                                                    <h6>{{ $notification->action }}</h6>
                                                    <h6> {{ $notification->type }} </h6>
                                                    <p>الوصف: {{ data_get($notification->payload, 'result.description') }}</p>
                                                    <p>الوصف الموسع: {{ data_get($notification->payload, 'resultDetails.ExtendedDescription') }}</p>
                                                    <p>المبلغ: {{ data_get($notification->payload, 'amount') }} {{ data_get($notification->payload, 'currency') }}</p>
                                                    <p>البريد الإلكتروني: {{ data_get($notification->payload, 'customer.email') }}</p>
                                                    <p>وضع التعليمات الدائمة: {{ data_get($notification->payload, 'standingInstruction.mode') }}</p>
                                                    <p>نوع التعليمات الدائمة: {{ data_get($notification->payload, 'standingInstruction.type') }}</p>
                                                    <p>اسم حامل البطاقة: {{ data_get($notification->payload, 'card.holder') }}</p>
                                                    <p>نوع البطاقة: {{ data_get($notification->payload, 'card.type') }}</p>
                                                    <p>رقم الهاتف للبنك: {{ data_get($notification->payload, 'card.issuer.phone') }}</p>
                                                    <p>تاريخ انتهاء البطاقة: {{ data_get($notification->payload, 'card.expiryMonth') }}/{{ data_get($notification->payload, 'card.expiryYear') }}</p>
                                                    <p>آخر 4 أرقام من البطاقة: {{ data_get($notification->payload, 'card.last4Digits') }}</p>
                                                    <p>IP العميل: {{ data_get($notification->payload, 'customer.ip') }}</p>
                                                    <p>اسم العميل: {{ data_get($notification->payload, 'customer.givenName') }}</p>
                                                    <p>الرمز القصير: {{ data_get($notification->payload, 'shortId') }}</p>
                                                    <p>الوقت: {{ data_get($notification->payload, 'timestamp') }}</p>
                                                    <p>اسم القناة: {{ data_get($notification->payload, 'channelName') }}</p>
                                                    <p>نوع الدفع: {{ data_get($notification->payload, 'paymentType') }}</p>
                                                    <p>علامة الدفع: {{ data_get($notification->payload, 'paymentBrand') }}</p>
                                                    <p>طريقة الدفع: {{ data_get($notification->payload, 'paymentMethod') }}</p>
                                                    <p>كود النتيجة: {{ data_get($notification->payload, 'result.code') }}</p>
                                                    <p class="text-bold">الاستجابة من المستحوذ: {{ data_get($notification->payload, 'resultDetails.AcquirerResponse') == 'APPROVED' ? 'مقبول' : data_get($notification->payload, 'resultDetails.AcquirerResponse') }}</p>
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


<!-- Cancel Subscription Modal -->
<div class="modal fade" id="cancelSubscriptionModal" tabindex="-1" aria-labelledby="cancelSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelSubscriptionModalLabel">تأكيد إلغاء الاشتراك</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد أنك تريد إلغاء الاشتراك؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="#" class="btn btn-danger btn-confirm-cancel">تأكيد الإلغاء</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cancelSubscriptionModal = document.getElementById('cancelSubscriptionModal');
            cancelSubscriptionModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var url = button.getAttribute('data-url');
                var confirmCancelButton = cancelSubscriptionModal.querySelector('.btn-confirm-cancel');
                confirmCancelButton.setAttribute('href', url);
            });
        });
    </script>
@endpush
