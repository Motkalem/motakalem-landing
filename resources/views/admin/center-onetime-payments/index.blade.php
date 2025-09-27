@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet"/>
    <style>
        .payment-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative;">
        <div class="col-md-12">
            <div class="p-20 mt-4 bgc-white bd">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <div class="text-muted">
                        إجمالي المدفوعات: {{ $centerPayments->total() }}
                    </div>
                </div>

                <form action="{{URL::current()}}" method="GET">
                    <div class="pb-4 w-25 d-flex align-items-center">
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                               id="search" placeholder="بحث بالاسم، الجوال، أو الإيميل"/>
                        <button class="btn btn-primary btn-sm mx-2" type="submit">بحث</button>
                        @if(request('search'))
                            <a href="{{ URL::current() }}" class="btn btn-secondary btn-sm">مسح</a>
                        @endif
                    </div>
                </form>

                @if($centerPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-class">
                            <thead>
                            <tr>
                                <th>اسم المريض</th>
                                <th>اسم الباقة</th>
                                <th>المبلغ</th>
                                <th>تاريخ الإنشاء</th>
                                <th>حالة الدفع</th>
                                <th style="width: 35%" class="text-center">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody id="paymentsTableBody">
                            @foreach($centerPayments as $centerPayment)
                                <tr>
                                    <td>
                                        <strong>{{ $centerPayment->centerPatient?->name }}</strong>
                                        @if($centerPayment->centerPatient?->mobile_number)
                                            <br><small class="text-muted">{{ $centerPayment->centerPatient->mobile_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $centerPayment->centerPackage?->name ?? 'غير محدد' }}</td>
                                    <td>
                                        <strong>{{ number_format($centerPayment->amount, 2) }}</strong> {{ __('SAR') }}
                                    </td>
                                    <td>{{ $centerPayment->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($centerPayment->is_finished)
                                            <span class="status-paid">مدفوع</span>
                                        @else
                                            <span class="status-unpaid">غير مدفوع</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="payment-actions">
                                            @if(!$centerPayment->is_finished)
                                                <button type="button"
                                                        data-payment-id="{{ $centerPayment->id }}"
                                                        data-patient-name="{{ $centerPayment->centerPatient?->name }}"
                                                        data-patient-email="{{ $centerPayment->centerPatient?->email }}"
                                                        class="btn btn-warning btn-sm send-pay-link-btn">
                                                    <i class="fas fa-paper-plane me-1"></i>
                                                    إرسال رابط الدفع
                                                </button>
                                            @endif

                                            <a href="{{ route('dashboard.center.center-onetime-payments.show', $centerPayment->id) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye me-1"></i>
                                                عرض
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد مدفوعات متاحة</p>
                        @if(request('search'))
                            <a href="{{ URL::current() }}" class="btn btn-primary btn-sm">عرض جميع المدفوعات</a>
                        @endif
                    </div>
                @endif
            </div>

            @if($centerPayments->hasPages())
                <div class="py-4">
                    {{ $centerPayments->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Send Payment URL Modal -->
    <div class="modal fade" id="confirmSendPayUrlModal" tabindex="-1" role="dialog"
         aria-labelledby="sendPayUrlModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendPayUrlModalLabel">
                        <i class="fas fa-paper-plane me-2"></i>
                        تأكيد إرسال رابط الدفع
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-question-circle fa-3x text-warning mb-3"></i>
                    </div>
                    <p class="text-center">هل أنت متأكد أنك تريد إرسال رابط الدفع إلى:</p>
                    <div class="alert alert-info">
                        <strong>المريض:</strong> <span id="modalPatientName"></span><br>
                        <strong>البريد الإلكتروني:</strong> <span id="modalPatientEmail"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        إلغاء
                    </button>
                    <button type="button" class="btn btn-warning" id="confirmSendPayLink">
                        <i class="fas fa-paper-plane me-1"></i>
                        نعم، أرسل الرابط
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let paymentId;
                let confirmButton;

                document.querySelectorAll('.send-pay-link-btn').forEach(function (button) {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();

                        paymentId = this.getAttribute('data-payment-id');
                        const patientName = this.getAttribute('data-patient-name');
                        const patientEmail = this.getAttribute('data-patient-email');

                        // Check if email exists
                        if (!patientEmail) {
                            alert('البريد الإلكتروني للمريض غير متوفر');
                            return;
                        }

                        // Update modal content
                        document.getElementById('modalPatientName').textContent = patientName;
                        document.getElementById('modalPatientEmail').textContent = patientEmail;

                        const modal = new bootstrap.Modal(document.getElementById('confirmSendPayUrlModal'));
                        modal.show();
                    });
                });

                document.getElementById('confirmSendPayLink').addEventListener('click', function () {
                    confirmButton = this;
                    confirmButton.disabled = true;
                    confirmButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الإرسال...';

                    const url = '{{ route("dashboard.center.center-onetime-payments.send-payment-url", ":id") }}'.replace(':id', paymentId);
                    const csrfToken = '{{ csrf_token() }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => response.json())
                        .then(data => {
                             
                            if (data.success) {
                                // Show success message
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                                alertDiv.innerHTML = `
                                <i class="fas fa-check-circle me-2"></i>
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                                document.querySelector('.col-md-12').insertBefore(alertDiv, document.querySelector('.p-20'));

                                // Auto-remove alert after 5 seconds
                                setTimeout(() => {
                                    if (alertDiv.parentNode) {
                                        alertDiv.remove();
                                    }
                                }, 5000);
                            } else {
                                alert('خطأ: ' + data.message);
                            }
                        })
                        .catch(error => {
                             console.error('Error:', error);
                        })
                        .finally(() => {
                            confirmButton.disabled = false;
                            confirmButton.innerHTML = '<i class="fas fa-paper-plane me-1"></i> نعم، أرسل الرابط';

                            // Handle Bootstrap 5 modal closure properly
                            const modalElement = document.getElementById('confirmSendPayUrlModal');
                            const modal = bootstrap.Modal.getInstance(modalElement);

                            if (modal) {
                                modal.hide();
                            }

                            setTimeout(() => {
                                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                                    backdrop.remove();
                                });

                                const openModals = document.querySelectorAll('.modal.show');
                                if (openModals.length === 0) {
                                    document.body.classList.remove('modal-open');
                                    document.body.style.overflow = '';
                                    document.body.style.paddingRight = '';
                                }
                            }, 300);
                        });

                });
            });
        </script>
    @endpush
@endsection
