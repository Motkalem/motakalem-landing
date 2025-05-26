@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet"/>
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">

        <div class="col-md-12">
            <div class="mx-4 text-end">
                <a href="{{ route('dashboard.payments.create') }}" class="px-4 btn btn-primary">
                    + إنشاء
                </a>
            </div>

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
                        <th>  الهاتف</th>
                        <th>اسم الباقة</th>
                        <th>نوع الدفع</th>
{{--                        <th>رابط الدفع</th>--}}
                        <th>هل اكتمل</th>
                        <th> حالة اخر معاملة</th>
                        <th style="width: 30%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody id="paymentsTableBody">
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{ $payment->student?->name }}</td>
                            <td><a href="tel:{{$payment->student?->phone}}">{{ $payment->student?->phone }}</a></td>
                            <td>{{ $payment->package?->name }}</td>
                            <td>{{ $payment->package?->payment_type == 'one time' ? 'مرة واحدة' : 'اقساط' }}</td>
{{--                            <td>--}}
{{--                                <button class="btn btn-link" onclick="copyToClipboard('{{ $payment->payment_url }}')"--}}
{{--                                        title="{{$payment->payment_url}}"> نسخ الرابط--}}
{{--                                </button>--}}
{{--                            </td>--}}
                            <td>
                                @if($payment->is_finished)
                                    <span class="text-success text-bold">نعم</span>
                                @else
                                    <span class="text-danger text-bold">لا</span>
                                @endif
                            </td>

                            <td>
                                @if($payment->transactions()->latest()->first()?->success == 'true')

                                    <span class="text-success text-bold">نجاح</span>
                                @else
                                    <span class="text-danger text-bold">فشل</span>
                                @endif
                            </td>
                            <td class="text-center project-actions">
                                @if($payment?->student?->parentContract)
                                    <a class="btn bbg-primary bg-primary btn-sm" target="_blank" href="{{ route('dashboard.download-contract',
                                            $payment?->student?->parentContract?->id) }}">
                                        تحميل العقد
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif
                                <a href="#"
                                   data-student-id="{{ $payment->student_id }}"
                                   class="px-2 btn btn-warning bgc-yellow-800 btn-sm send-contract-btn">
                                    إرسال العقد
                                </a>

                                <a href="{{ route('dashboard.payments.show', $payment->id) }}"
                                   class="px-2 btn bg-green btn-sm">
                                    عرض
                                </a>

{{--                                <a href="{{ route('dashboard.payments.edit', $payment->id) }}"--}}
{{--                                   class="px-2 btn btn-info btn-sm">--}}
{{--                                    تعديل--}}
{{--                                </a>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
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
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            function copyToClipboard(url) {
                navigator.clipboard.writeText(url).then(function () {
                    alert('Link copied to clipboard');
                }, function (err) {
                    alert('Failed to copy: ', err);
                });
            }
            document.addEventListener('DOMContentLoaded', function () {
                var deleteModal = document.getElementById('deleteModal');
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var paymentId = button.getAttribute('data-id');
                    var form = document.getElementById('deleteForm');
                    form.action = "{{ route('dashboard.payments.destroy', ':id') }}".replace(':id', paymentId);
                });
            });

            document.addEventListener('DOMContentLoaded', function () {

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
                }, { once: true }); // This ensures the event fires only once.
            });

        </script>
    @endpush
@endsection
