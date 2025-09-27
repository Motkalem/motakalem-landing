@extends('admin.layouts.master')

@push('styles')
   <style>
/* Timeline styles remain unchanged */
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
    <h6 class="c-grey-900 h3">تفاصيل الدفعة</h6>
    <div class="text-end mx-4 mb-3">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.center.center-onetime-payments.index') }}">
            رجوع
        </a>
    </div>
    <div class="row">
        <!-- First Column -->
        <div class="col-12  p-4 bgc-white">
            <div class="mT-30">
                <div class="row mb-3">
                    <label for="centerPayment_id" class="col-sm-4 col-form-label">رقم الدفعة</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $centerPayment->id }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="center_package_id" class="col-sm-4 col-form-label">الباقة</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            {{ $centerPayment?->centerPackage?->name }}
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="patient_id" class="col-sm-4 col-form-label">الطالب</label>
                   
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">المبلغ</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            {{ $centerPayment->amount . ' ' }}
                            <span class="riyal-symbol">R</span>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="created_at" class="col-sm-4 col-form-label">تاريخ الدفع</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $centerPayment->created_at }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="updated_at" class="col-sm-4 col-form-label">آخر تحديث</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $centerPayment->updated_at }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="is_finished" class="col-sm-4 col-form-label">حالة الدفع</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            @if ($centerPayment->is_finished)
                                <span class="text-success">مدفوع</span>
                            @else
                                <span class="text-danger">غير مدفوع</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="paid_type" class="col-sm-4 col-form-label">نوع الدفع</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            @if ($centerPayment->paid_type == 'recurring' && $centerPayment->is_paid)
                                <span class="text-primary">دفع تلقائي</span>
                            @elseif($centerPayment->paid_type == 'payment link' && $centerPayment->is_paid)
                                <span class="text-default">لينك دفع</span>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="admin_ip" class="col-sm-4 col-form-label">قام بالدفع</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $centerPayment->admin_ip ?? '---' }}</p>
                    </div>
                </div>
               
            </div>
        </div>

        
    </div>

    <div class=" gap-1 row" >
        <!-- Notification Log Section -->
        <div class="p-20 mt-4">
            <div class="mT-30">
                <h6 class="c-grey-900 h3">سجل الإشعارات</h6>
                <section class="mt-2 timeline_area section_padding_130">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="apland-timeline-area">
 
                                    @foreach($centerPayment->centerPaymentTransactions?->sortByDesc('created_at')??[] as $trans)
                                        @php
                                            $payload = $trans->data; // Already an array
                                        @endphp

                                        <div class="mt-3 bg-white border single-timeline-area border-1 rounded-2">
                                            <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                                <p>{{ $trans->created_at }}</p>
                                            </div>

                                            <div class="row">
                                                <div class="single-timeline-content d-flex" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInLeft;">
                                                    <div class="timeline-icon"><i class="fa fa-bell" aria-hidden="true"></i></div>

                                                    <div class="m-2 timeline-text">
                                                        <h2 class="px-10 h4 fw-500">{{ __($trans->title) }}</h2>
                                                        <h6>{{ __($trans->type ?? '-') }}</h6>

                                                        <p>الوصف: {{ __($payload['result']['description'] ?? '-') }}</p>
                                                        <p>المبلغ: {{ $payload['amount'] ?? '-' }} {{ $payload['currency'] ?? '' }}</p>
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

 
