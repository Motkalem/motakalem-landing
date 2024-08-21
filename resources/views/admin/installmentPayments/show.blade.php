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
            <label for="client_pay_order_id" class="form-label col-sm-2 col-form-label">الطالب</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">
                    <a href="{{ route('dashboard.students.show', $installmentPayment?->student?->id) }}">
                        {{ $installmentPayment?->student?->name }}
                    </a>
                </p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="client_pay_order_id" class="form-label col-sm-2 col-form-label">رقم التسجيل (HyperPay)</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">
                    {{ $installmentPayment?->registration_id }}
                </p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="amount" class="form-label col-sm-2 col-form-label">مقدار القسط</label>
            <div class="col-sm-10">
                <p class="form-control-plaintext">{{ $installmentPayment->package?->installment_value . ' ' . __('SAR') }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="amount" class="form-label col-sm-2 col-form-label">تاريخ الاشتراك</label>
            <div class="col-sm-10">
                <p class="text-bold">{{ $installmentPayment->created_at }}</p>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="amount" class="form-label col-sm-2 col-form-label"> اخر تحديث  </label>
            <div class="col-sm-10">
                <p class="text-bold">{{ $installmentPayment->updated_at }}</p>
            </div>
        </div>

        @if($installmentPayment->canceled == 0)
        <div class="text-end">
            <!-- Cancel Button -->
            <button class="px-4 btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelSubscriptionModal"
             data-url="{{route('dashboard.cancel-schedule', $installmentPayment->payment_id)}}">
                إلغاء الاشتراك
            </button>
        </div>
        @else
        <p class="px-4 btn text-danger" >
           تم إلغاء الإشتراك
       </button>
        @endif
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
