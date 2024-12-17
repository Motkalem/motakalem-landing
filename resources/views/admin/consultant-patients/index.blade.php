@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
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

            <div class="p-20 mt-4 bgc-white bd">
                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th>اسم المريض</th>
                        <th>العمر</th>
                        <th>الجنس</th>
                        <th>الهاتف</th>
                        <th>المدينة</th>
                        <th>نوع الاستشارة</th>
                        <th class="text-center"> المعاملات   </th>
                        <th class="text-center"> الدفع  </th>
                        <th style="width: 20%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($consultantPatients as $consultantPatient)
                        <tr>
                            <td>{{ $consultantPatient->name }}</td>
                            <td>{{ $consultantPatient->age }}</td>
                            <td>{{ $consultantPatient->gender === 'male' ? 'ذكر' : 'أنثى' }}</td>
                            <td>{{ $consultantPatient->mobile }}</td>
                            <td>{{ $consultantPatient->city }}</td>
                            <td>
                                @if($consultantPatient->consultationType->name)

                                   <a href="{{route('dashboard.consultant-types.edit', $consultantPatient->consultationType?->id)}}">

                                    {{ $consultantPatient->consultationType->name   }} ({{ $consultantPatient->consultationType->price  }} @lang('SAR'))
                                   </a>

                                @else
                                     غير محدد
                                @endif

                            </td>
                            <td class="text-center">
                                <button
                                    disabled
                                    class="px-4 btn btn-success bg-success btn-sm show-transactions"
                                    data-href="{{route('dashboard.send-sms-payment-link', $consultantPatient->id)}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#">
                                     عرض
                                </button>
                            </td>

                             <td class="text-center">
                                <button
                                    class="px-4 btn btn-warning bg-warning btn-sm send-payment-link"
                                    data-href="{{route('dashboard.send-sms-payment-link', $consultantPatient->id)}}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmationModal">
                                    إرسال رابط الدفع
                                </button>
                            </td>
                            <td class="text-right project-actions">
                                <a class="px-4 btn btn-info btn-sm" href="{{ route('dashboard.consultant-patients.edit', $consultantPatient->id) }}">
                                    تعديل
                                </a>
                                <button class="px-4 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="{{ $consultantPatient->id }}">
                                    حذف
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
                    <a id="confirmSendPaymentLink" href="#" class="btn btn-warning">تأكيد</a>
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
            });
        </script>
    @endpush
@endsection
