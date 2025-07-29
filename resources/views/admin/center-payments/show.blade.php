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
        <a class="px-4 btn btn-info" href="{{ route('dashboard.center.center-patients.index') }}">
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

                                {{ $installmentPayment?->centerPackage?->name }}

                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="client_pay_order_id" class="col-sm-4 col-form-label">الطالب</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            <a href="{{ route('dashboard.center.center-patients.show', $installmentPayment?->patient?->id) }}">
                                {{ $installmentPayment?->patient?->name }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">الإجمالي</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">
                            {{
                                ($installmentPayment->centerPackage?->first_inst +
                                 $installmentPayment->centerPackage?->second_inst +
                                 $installmentPayment->centerPackage?->third_inst +
                                 $installmentPayment->centerPackage?->fourth_inst +
                                 $installmentPayment->centerPackage?->fifth_inst) . ' '
                            }}
                            <span class="riyal-symbol">R</span>
                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-4 col-form-label">القسط الأول</label>
                    <div class="col-sm-8">
                        <p class="form-control-plaintext">{{ $installmentPayment->centerPackage?->first_inst . ' '   }}  <span class="riyal-symbol">R</span></p>
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
                            <th class="text-center"> نوع الدفع</th>
                            <th class="text-center"> رابط الدفع</th>
                            <th class="text-center">الإجراء</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($installmentPayment->centerInstallments as $installment)
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
                                    @if ($installment->paid_type == 'recurring' && $installment->is_paid)
                                        <span class="text-primary">تقسط تلقائي</span>
                                    @elseif($installment->paid_type == 'payment link'  && $installment->is_paid)
                                        <span class="text-default"> لينك دفع  </span>
                                      @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (!$installment->is_paid)
                                        <button type="button"
                                        class="btn btn-info btn-sm sendPaymentLinkBtn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#sendPaymentLinkModal"
                                        data-installment-id="{{ $installment->id }}">
                                            إرسال رابط الدفع
                                        </button>
                                    @endif

                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-1">
                                        @if (!$installment->is_paid)
                                            <button type="button" class="btn btn-primary bg-success text-white border-0 btn-sm mb-1"
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Send Payment Link Modal -->
        <div class="modal fade" id="sendPaymentLinkModal" tabindex="-1" aria-labelledby="sendPaymentLinkModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="sendPaymentLinkForm" action="" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sendPaymentLinkModalLabel">إرسال رابط الدفع للقسط</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <div class="modal-body">
                            <p>هل أنت متأكد أنك تريد إرسال رابط الدفع لهذا القسط؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-md btn-outline-danger" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg"></i> إلغاء
                            </button>
                            <button type="button" class="btn btn-md btn-outline-secondary" id="copyPaymentLinkBtn">
                                <i class="fa fa-copy"></i> نسخ الرابط
                            </button>
                            <small id="copySuccessMsg" class="text-success d-none ms-2">تم نسخ الرابط!</small>
                            <button type="submit" class="btn btn-md btn-outline-success">
                                <i class="bi bi-send"></i> تأكيد
                            </button>
                            <input type="text" id="paymentLinkInput" value="" readonly style="position:absolute; left:-9999px;">
                        </div>
                </form>
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
                                    @foreach($installmentPayment->centerTransaction?->sortByDesc('created_at') as $trans)
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

@push('scripts')

    <script>
        // Script to handle modal action
        const confirmDeductionModal = document.getElementById('confirmDeductionModal');
        const deductionForm = document.getElementById('deductionForm');

        confirmDeductionModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const installmentId = button.getAttribute('data-installment-id');

            // Update the form action dynamically with the installment ID
            const routeUrl = `{{ route('dashboard.center.deductInstallment', ':installmentId') }}`.replace(':installmentId', installmentId);
            deductionForm.action = routeUrl;
        });

        document.addEventListener('DOMContentLoaded', function () {
                // Handle send payment link button click
                document.querySelectorAll('.sendPaymentLinkBtn').forEach(function(btn) {
                    btn.addEventListener('click', function () {
                        var centerInstallmentId = this.getAttribute('data-installment-id');
                        var form = document.getElementById('sendPaymentLinkForm');
                        // Set the form action dynamically
                        form.action = "{{ route('dashboard.center.center-send-pay-url', 'INSTALLMENT_ID') }}".replace('INSTALLMENT_ID', centerInstallmentId);
                    });
                });
            });
    
    
           
      document.addEventListener('DOMContentLoaded', function () {
                                    var copyBtn = document.getElementById('copyPaymentLinkBtn');
                                    var paymentLinkInput = document.getElementById('paymentLinkInput');
                                    var sendPaymentLinkModal = document.getElementById('sendPaymentLinkModal');

                                    // Listen for modal show to set the link
                                    sendPaymentLinkModal.addEventListener('show.bs.modal', function (event) {
                                        var button = event.relatedTarget;
                                        var installmentId = button ? button.getAttribute('data-installment-id') : null;
                                        if (installmentId) {
                                            var url = window.location.origin + '/pay-center-installment/checkout/' + installmentId;
                                            paymentLinkInput.value = url;
                                        }
                                    });

                                    copyBtn.addEventListener('click', function () {
                                        paymentLinkInput.select();
                                        paymentLinkInput.setSelectionRange(0, 99999); // For mobile devices
                                        document.execCommand('copy');
                                        copyBtn.innerText = 'تم النسخ!';
                                        setTimeout(function () {
                                            copyBtn.innerText = 'نسخ رابط الدفع';
                                        }, 1500);
                                    });
                                });
            
    </script>
 
@endpush
