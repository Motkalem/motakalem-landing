@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet"/>

    <style>
        .progress-circle {
            width: 50px;
            height: 50px;
            background: conic-gradient(
                #4caf50 calc(var(--progress) * 1%),
                #eaeaea calc(var(--progress) * 1%)
            );
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <div class="p-20 mt-4 bgc-white bd">
                <form action="{{URL::current()}}">
                    <div class="pb-4 w-25 d-flex align-items-center">
                        <input type="search" name="search" value="{{data_get($_GET,'search')}}" class="form-control"
                               id="search" placeholder="بحث"/>
                        <button class="btn btn-primary btn-sm mx-2">بحث</button>
                    </div>
                </form>
                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th>اسم الطالب</th>
                        <th>اسم الباقة</th>
                        <th> الأقساط</th>
                        <th> تاريخ اول قسط</th>
                        <th class="text-center"> مكتملة الأقساط</th>
                        <th style="width: 30%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>

                    <tbody id="paymentsTableBody">
                    @foreach($installmentPayments as $installmentPayment)
                        <tr>
                            <td>{{ $installmentPayment->patient?->name }}</td>
                            <td>{{ $installmentPayment->centerPackage?->name }}</td>
                            <td>
                                @if($installmentPayment->centerInstallments?->count())

                                    <div class="progress-circle text-white" style="--progress:
                {{( $installmentPayment->centerInstallments()->where('is_paid', 1)->count()
                / $installmentPayment->centerInstallments?->count()) * 100}};">

                                        {{ $installmentPayment->centerInstallments()->where('is_paid', 1)->count()
                                    .'/'. $installmentPayment->centerInstallments?->count() }}
                                    </div>

                                @else
                                    <div class="progress-circle text-white" style="--progress:
                              {{(0  / ($installmentPayment->centerPackage?->number_of_months)??1) * 100}};">

                                        {{ 0  .'/'. $installmentPayment->centerPackage?->number_of_months }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ $installmentPayment->first_installment_date }}</td>

                            <td class="text-center">
                                @if( $installmentPayment->is_completed)
                                    <span class="fw-bold text-success">
                                  مكتملة
                               </span>
                                @else
                                    <span class="fw-bold text-danger">
                                غير مكتملة
                               </span>
                                @endif
                            </td>
                            <td class="text-center project-actions">
                                <a href="#"
                                   data-patient-id="{{ $installmentPayment->id }}"
                                   class="px-2 btn btn-warning bgc-yellow-800 btn-sm send-contract-btn">
                                    إرسال رابط الدفع
                                </a>

                                <a href="{{ route('dashboard.center.center-payments.show', $installmentPayment->id) }}"
                                   class="px-4 btn btn-info btn-sm">
                                    عرض
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="py-4"> {{$installmentPayments->links()}} </div>
        </div>
    </div>

    <div class="modal fade" id="confirmSendPayUrlModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تأكيد إرسال العقد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد إرسال رابط الدفع؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="confirmSendContract">نعم، أرسل  </button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                let paymentId;

                document.querySelectorAll('.send-contract-btn').forEach(function (button) {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        paymentId = this.getAttribute('data-patient-id');
                        const modal = new bootstrap.Modal(document.getElementById('confirmSendPayUrlModal'));
                        modal.show();
                    });
                });

                document.getElementById('confirmSendContract').addEventListener('click', function () {
                    const url = '{{ route("dashboard.center.send-pay-url", ":id") }}'.replace(':id', paymentId); // Replace :id with actual patient ID
                    const csrfToken = '{{ csrf_token() }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Network response was not ok');
                            }
                        })
                        .then(data => {
                            alert('تم إرسال رابط الدفع بنجاح!');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmSendPayUrlModal'));
                            modal.hide();
                            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        })
                        .catch(error => {
                            // Handle errors
                            alert('حدث خطأ أثناء إرسال رابط الدفع: ' + error.message);
                        });
                });
            }, {once: true}); // This ensures the event fires only once.

        </script>
    @endpush
@endsection
