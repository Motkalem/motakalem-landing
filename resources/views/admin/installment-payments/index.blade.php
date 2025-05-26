@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />

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
                    <input type="search" name="search" value="{{data_get($_GET,'search')}}" class="form-control" id="search" placeholder="بحث"/>
                    <button class="btn btn-primary btn-sm mx-2">بحث</button>
                </div>
            </form>
            <table class="table table-striped table-class">
                <thead>
                    <tr>
                        <th>اسم الطالب</th>
                        <th>اسم الباقة</th>
                        <th> الأقساط  </th>
                        <th> تاريخ اول قسط  </th>
                        <th class="text-center"> مكتملة الأقساط  </th>
                        <th style="width: 30%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="paymentsTableBody">
                    @foreach($installmentPayments as $installmentPayment)
                    <tr>
                        <td>{{ $installmentPayment->student?->name }}</td>
                        <td>{{ $installmentPayment->package?->name }}</td>
                        <td>
                            @if($installmentPayment->installments?->count())

                                <div class="progress-circle text-white"  style="--progress:
                            {{( $installmentPayment->installments()->where('is_paid', 1)->count()
                            / $installmentPayment->installments?->count()) * 100}};">

                                    {{ $installmentPayment->installments()->where('is_paid', 1)->count()
                                .'/'. $installmentPayment->installments?->count() }}
                                </div>

                            @else
                                <div class="progress-circle text-white"  style="--progress:
                                 {{(0  / $installmentPayment->package?->number_of_months) * 100}};">

                                    {{ 0  .'/'. $installmentPayment->package?->number_of_months }}
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
                            @if($installmentPayment?->student?->parentContract)
                                <a class="btn bbg-primary bg-primary btn-sm" target="_blank" href="{{ route('dashboard.download-contract',
                                            $installmentPayment?->student?->parentContract?->id) }}">
                                    تحميل العقد
                                    <i class="fa fa-download"></i>
                                </a>
                            @endif
                            <a href="#"
                               data-student-id="{{ $installmentPayment->student_id }}"
                               class="px-2 btn btn-warning bgc-yellow-800 btn-sm send-contract-btn">
                                إرسال العقد
                            </a>

                            <a href="{{ route('dashboard.installment-payments.show', $installmentPayment->id) }}" class="px-4 btn btn-info btn-sm">
                                 عرض
                            </a>

                           @if( $installmentPayment->installments()->where('is_paid', 1)->count() == 0)
                                <button class="px-4 btn btn-primary btn-sm  send-payment-link" data-id="{{ $installmentPayment->id }}"> إرسال رابط الدفع</button>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-4"> {{$installmentPayments->links()}} </div>
    </div>
</div>
<div class="modal fade" id="confirmSendContractModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تأكيد إرسال العقد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد أنك تريد إرسال العقد؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="confirmSendContract">نعم، أرسل العقد</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">تأكيد إرسال رابط الدفع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body">
                <p>هل أنت متأكد أنك تريد إرسال رابط الدفع؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="confirmSendPaymentLink">تأكيد</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        $(document).ready(function () {

            // Variable to store the payment link ID to send later
            var paymentId = null;

            // When clicking the send-payment-link button
            $('.send-payment-link').on('click', function () {
                // Get the ID from the button's data-id attribute
                paymentId = $(this).data('id');

                // Show the confirmation modal
                $('#confirmModal').modal('show');
            });

            // When the user clicks the confirm button in the modal
            $('#confirmSendPaymentLink').on('click', function () {
                if (paymentId !== null) {
                    const csrfToken = '{{ csrf_token() }}';
                    $.ajax({
                        url: '/dashboard/installment-payments/' + paymentId + '/send-payment-url', // your route URL
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for Laravel
                            id: paymentId
                        },
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (response) {
                            // Handle success (e.g., close modal and show a success message)
                            $('#confirmModal').modal('hide');
                            alert("{{__('Payment link sent successfully!')}}");
                            // You can perform any other action on success
                        },
                        error: function (xhr, status, error) {
                            // Handle error (e.g., show an error message)
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });




            let studentId;

            document.querySelectorAll('.send-contract-btn').forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    studentId = this.getAttribute('data-student-id');
                    const modal = new bootstrap.Modal(document.getElementById('confirmSendContractModal'));
                    modal.show();
                });
            });

            document.getElementById('confirmSendContract').addEventListener('click', function () {
                const url = '{{ route("dashboard.send-contract", ":id") }}'.replace(':id', studentId); // Replace :id with actual student ID
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
                        alert('تم إرسال العقد بنجاح!');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmSendContractModal'));
                        modal.hide();
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    })
                    .catch(error => {
                        // Handle errors
                        alert('حدث خطأ أثناء إرسال العقد: ' + error.message);
                    });
            });
        }, { once: true }); // This ensures the event fires only once.

    </script>
@endpush
@endsection
