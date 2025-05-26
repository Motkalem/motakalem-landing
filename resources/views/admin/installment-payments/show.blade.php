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
    <h6 class="c-grey-900 h3">تفاصيل الدفعة</h6>
    <div class="text-end mx-4 mb-3">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.installment-payments.index') }}">
            رجوع
        </a>
    </div>
    <div class="row">
        <!-- First Column -->
        <div class="col-12 col-md-5 p-4 bgc-white">

            <div class="mT-30 border-l-4">
                <div class="row mb-3">
                    <label for="installmentPayment_id" class="col-sm-4 col-form-label">رقم الدفعة</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $installmentPayment->id }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="client_pay_order_id" class="col-sm-4 col-form-label">الباقة</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">

                                {{ $installmentPayment?->package?->name }}

                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="client_pay_order_id" class="col-sm-4 col-form-label">الطالب</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="{{ route('dashboard.students.show', $installmentPayment?->student?->id) }}">
                                {{ $installmentPayment?->student?->name }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">الإجمالي</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            {{
                                ($installmentPayment->package?->first_inst +
                                 $installmentPayment->package?->second_inst +
                                 $installmentPayment->package?->third_inst +
                                 $installmentPayment->package?->fourth_inst +
                                 $installmentPayment->package?->fifth_inst) . ' '
                            }}
                            <span class="riyal-symbol">R</span>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">القسط الأول</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $installmentPayment->package?->first_inst . ' '   }}  <span class="riyal-symbol">R</span></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">تاريخ الاشتراك</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $installmentPayment->created_at }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">آخر تحديث</label>
                    <div class="col-sm-8">
                        <p class="text-bold">{{ $installmentPayment->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Column -->
        <div class="col-12 col-md-7 p-4 bgc-white">
            <h6 class="c-grey-900 h3">الأقساط</h6>
            <div class="mT-30">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center"> قام بالدفع  </th>
                            <th class="text-center">رقم القسط</th>
                            <th class="text-center">المبلغ</th>
                            <th class="text-center">تاريخ الاستحقاق</th>
                            <th class="text-center">تاريخ الدفع</th>
                            <th class="text-center">حالة الدفع</th>
                            <th class="text-center">الإجراء</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($installmentPayment->installments as $installment)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $installment->admin_ip??'---' }}</td>
                                <td class="text-center">{{ $installment->id }}</td>
                                <td class="text-center">{{ $installment->installment_amount . ' '  }}  <span class="riyal-symbol">R</span></td>
                                <td class="text-center">{{ $installment->installment_date }}</td>
                                <td class="text-center">{{ $installment->paid_at ?? '---' }}</td>
                                <td class="text-center">
                                    @if ($installment->is_paid)
                                        <span class="text-success">مدفوع</span>
                                    @else
                                        <span class="text-danger">غير مدفوع</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!$installment->is_paid)
                                        <button type="button" class="btn btn-primary bg-success text-white border-0 btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeductionModal"
                                                data-installment-id="{{ $installment->id }}">
                                            خصم القسط
                                        </button>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            تم الدفع
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmDeductionModal" tabindex="-1" aria-labelledby="confirmDeductionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeductionModalLabel">تأكيد خصم القسط</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        هل أنت متأكد أنك تريد خصم هذا القسط؟
                    </div>
                    <div class="modal-footer">
                        <form id="deductionForm" action="" method="POST">
                            @csrf
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-danger bg-danger text-white">تأكيد</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <div class=" gap-1 row" >

        <!-- Transaction Details Section -->

      <!-- Notification Log Section -->
        <div class="p-20 mt-4">
            <div class="mT-30">
                <h6 class="c-grey-900 h3">سجل الإشعارات</h6>
                <section class="mt-2 timeline_area section_padding_130">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="apland-timeline-area">
                                    @foreach($installmentPayment->hyperpayWebHooksNotifications->sortByDesc('created_at') as $notification)
                                    <div class="mt-3 bg-white border single-timeline-area border-1 rounded-2">
                                        <div class="timeline-date wow fadeInLeft" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInLeft;">
                                            <p>{{ $notification->created_at }} </p>
                                        </div>
                                        <div class="row">
                                            <div class="single-timeline-content d-flex " data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInLeft;">
                                                <div class="timeline-icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
                                                <div class="m-2 timeline-text">
                                                    <h2 class="px-10 h4 fw-500">{{ __($notification->title) }}</h2>
                                                    <h6> {{ __($notification->type) }} </h6>

                                                    <p>الوصف: {{ __(data_get($notification->payload, 'result.description')) }}</p>
                                                    <p>المبلغ: {{ data_get($notification->payload, 'amount') }} {{ __(data_get($notification->payload, 'currency')) }}</p>
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
{{--<div class="modal fade" id="cancelSubscriptionModal" tabindex="-1" aria-labelledby="cancelSubscriptionModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="cancelSubscriptionModalLabel">تأكيد إلغاء الاشتراك</h5>--}}
{{--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                هل أنت متأكد أنك تريد إلغاء الاشتراك؟--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>--}}
{{--                <a href="#" class="btn btn-danger btn-confirm-cancel">تأكيد الإلغاء</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection

@push('scripts')

    <script>
        // Script to handle modal action
        const confirmDeductionModal = document.getElementById('confirmDeductionModal');
        const deductionForm = document.getElementById('deductionForm');

        confirmDeductionModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const installmentId = button.getAttribute('data-installment-id');

            // Update the form action dynamically with the installment ID
            const routeUrl = `{{ route('dashboard.deductInstallment', ':installmentId') }}`.replace(':installmentId', installmentId);
            deductionForm.action = routeUrl;
        });
    </script>


{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            var cancelSubscriptionModal = document.getElementById('cancelSubscriptionModal');--}}
{{--            cancelSubscriptionModal.addEventListener('show.bs.modal', function(event) {--}}
{{--                var button = event.relatedTarget;--}}
{{--                var url = button.getAttribute('data-url');--}}
{{--                var confirmCancelButton = cancelSubscriptionModal.querySelector('.btn-confirm-cancel');--}}
{{--                confirmCancelButton.setAttribute('href', url);--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}
@endpush
