@php use Illuminate\Support\Facades\URL; @endphp
@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet"/>
@endpush


@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <h3 class="text-bold">
                المرضى
                ({{ $consultantPatientsCount }})
            </h3>
            <div class="mx-4 text-end">
                <a class="px-4 btn btn-primary" href="{{ route('dashboard.consultant-patients.create') }}">
                    + إنشاء
                </a>
            </div>

            <div class="p-20   mt-4 bgc-white bd">

                <form action="{{URL::current()}}">
                    <div class="pb-4 w-25 d-flex align-items-center">
                        <input type="search" name="search" value="{{data_get($_GET,'search')}}" class="form-control" id="search" placeholder="بحث"/>
                        <button class="btn btn-primary btn-sm mx-2">بحث</button>
                    </div>
                </form>


                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th class="text-start">التاريخ  </th>
                        <th class="text-start">الاسم  </th>
                        <th class="text-center">المصدر</th>
                        <th class="text-center">العمر</th>
                        <th class="text-center">الجنس</th>
                        <th class="text-center">هاتف</th>
                        <th class="text-center">المدينة</th>
                        <th class="text-center"> الإستشارة</th>
                        <th class="text-center"> حالة الدفع </th>
                        <th class="text-center"> رابط الدفع  </th>
                        <th class="text-center"> المعاملات</th>
                        <th class="text-center"> الفاتورة</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($consultantPatients as $consultantPatient)
                        <tr>
                            <td class="text-center">{{ $consultantPatient->created_at?->toDateTimeString() }}</td>
                            <td class="text-center">{{ $consultantPatient->name }}</td>
                            <td class="text-center">{{ __($consultantPatient->source) }}</td>
                            <td class="text-center">{{ $consultantPatient->age??'---' }}</td>
                            <td class="text-center">
                                @if($consultantPatient->gender)

                                    @if($consultantPatient->gender == 'male')
                                        ذكر

                                    @else
                                        أنثى
                                    @endif
                                @else
                                   ---
                                @endif
                            </td>
                            <td class="text-center">{{ $consultantPatient->mobile }}</td>
                            <td class="text-center">{{ $consultantPatient->city??'---' }}</td>
                            <td class="text-center">
                                @if($consultantPatient->consultationType->name)

                                    <a href="{{route('dashboard.consultant-types.edit', $consultantPatient->consultationType?->id)}}">

                                        {{ $consultantPatient->consultationType->name   }}
                                        ({{ $consultantPatient->consultationType->price  }}  <span class="riyal-symbol">R</span>)
                                    </a>

                                @else
                                    غير محدد
                                @endif
                            </td>
                            <td class="text-center">
                                @if($consultantPatient->is_paid)
                                    <span class="text-success" title="">
                                       تم الدفع !
                                    </span>
                                @else
                                    <span class="text-success" title=" لم يتم الدفع !">
                                        <i class="fa fa-close text-danger"> </i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">

                                    <button
                                        class="px-2 btn btn-success bg-black btn-sm text-white send-payment-link"
                                        data-href="{{route('dashboard.send-sms-payment-link', $consultantPatient->id)}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmationModal">
                                        ارسل
                                    </button>

                            </td>
                            <td class="text-center">
                                <button class="px-2 btn btn-success bg-success btn-sm text-white show-transactions"
                                        data-transactions='@json($consultantPatient->transaction_data)'
                                        data-bs-toggle="modal"
                                        data-bs-target="#transactionsModal">
                                    عرض
                                </button>
                            </td>


                            <td class="text-center">
                                @if($consultantPatient->is_paid)
                                    <button
                                        class="px-2 btn btn-success bg-black btn-sm text-white send-invoice-link"
                                        data-href="{{route('dashboard.re-send-sms-invoice-link', $consultantPatient->id)}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#invoiceConfirmationModal">
                                        رابط
                                    </button>

                                @else
                                    <button
                                        disabled
                                        class="px-2 btn btn-success bg-black btn-sm text-white"
                                        data-bs-toggle="modal">
                                        رابط
                                    </button>
                                @endif

                            </td>
                            <td class="text-right project-actions">
                                @if($consultantPatient->is_paid)
                                    <a class="px-2 btn btn-info btn-sm opacity-50"
                                       href="#?" style="cursor: not-allowed">
                                        تعديل
                                    </a>
                                    <a href="#?"  class="px-2 btn btn-danger btn-sm opacity-50"  style="cursor: not-allowed">
                                        حذف
                                    </a>
                                @else
                                    <a class="px-2 btn btn-info btn-sm"
                                       href="{{ route('dashboard.consultant-patients.edit', $consultantPatient->id) }}">
                                        تعديل
                                    </a>
                                    <button class="px-2 btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#statusModal" data-id="{{ $consultantPatient->id }}">
                                        حذف
                                    </button>

                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{$consultantPatients->links()}}
            </div>
        </div>
    </div>


    <!-- Transactions Modal -->
    <div class="modal fade" id="transactionsModal" tabindex="-1" aria-labelledby="transactionsModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionsModalLabel">المعاملات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="transactionsList" class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">تأكيد إرسال رابط الدفع</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد إرسال رابط الدفع لهذا المريض؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <a id="confirmSendPaymentLink" href="#" class="btn bg-black btn-black"> تأكيد </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="invoiceConfirmationModal" tabindex="-1" aria-labelledby="invoiceConfirmationModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceConfirmationModalLabel">تأكيد إرسال رابط الفاتورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد إرسال رابط الفاتورة لهذا المريض؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <a id="invoiceConfirmSendPaymentLink" href="#" class="btn bg-black btn-black">تأكيد</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">حذف مريض</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف هذا المريض؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">تأكيد</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Delete confirmation
                var statusModal = document.getElementById('statusModal');
                statusModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var consultantPatientId = button.getAttribute('data-id');
                    var form = document.getElementById('deleteForm');
                    form.action = "{{ route('dashboard.consultant-patients.destroy', ':id') }}".replace(':id', consultantPatientId);
                });

                // Payment link confirmation
                var confirmationModal = document.getElementById('confirmationModal');
                confirmationModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var paymentLink = button.getAttribute('data-href');
                    var confirmButton = document.getElementById('confirmSendPaymentLink');
                    confirmButton.href = paymentLink;
                });

                // Invoice link confirmation
                var invoiceConfirmationModal = document.getElementById('invoiceConfirmationModal');
                invoiceConfirmationModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var paymentLink = button.getAttribute('data-href');
                    var confirmButton = document.getElementById('invoiceConfirmSendPaymentLink');
                    confirmButton.href = paymentLink;
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                const transactionsModal = document.getElementById('transactionsModal');
                const transactionsList = document.getElementById('transactionsList');

                transactionsModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const transactions = JSON.parse(button.getAttribute('data-transactions'));
                    transactionsList.innerHTML = '';

                    for (const [title, transaction] of Object.entries(transactions)) {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');
                        listItem.innerHTML = `
                    <strong>${title}</strong><br>
                    <strong>المعرف:</strong> ${transaction.ndc || 'N/A'}<br>
                    <strong>المبلغ:</strong> ${transaction.amount || 'N/A'} ${transaction.currency || ''}<br>
                    <strong>النتيجة:</strong> ${transaction.result?.description || 'N/A'}<br>
                    <strong>التاريخ:</strong> ${transaction.timestamp || 'N/A'}
                `;
                        transactionsList.appendChild(listItem);
                    }
                });
            });

        </script>
    @endpush
@endsection
