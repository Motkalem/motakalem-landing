@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">
        قائمة الطلاب
        ({{$studentsCount}})
    </h6>
    <div class="mx-4 text-end">
        <a class="px-4 btn btn-primary" href="{{ route('dashboard.students.create') }}">
            إضافة طالب جديد
        </a>
    </div>
    <div class="mT-30">
        <form action="{{URL::current()}}">
            <div class="pb-4 w-25 d-flex align-items-center">
                <input type="search" name="search" value="{{data_get($_GET,'search')}}" class="form-control" id="search" placeholder="بحث"/>
                <button class="btn btn-primary btn-sm mx-2">بحث</button>
            </div>
        </form>
        <table class="table table-striped table-class">
            <thead>
                <tr>
                    <th>الإسم</th>
                    <th>تاريخ التسجيل</th>
                    <th>الرقم التعريفي</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمر</th>
                    <th>الباقة</th>
                    <th>الهاتف</th>
                    <th>المدينة</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    @php
                        $canDelete = true;

                        // Check installment payments (never paid)
                        foreach ($student->installmentPayments as $payment) {
                            if ($payment->installments->where('is_paid', 1)->count() > 0) {
                                $canDelete = false;
                                break;
                            }
                        }

                        // Check one-time payments (never paid)
                        foreach ($student->payments as $payment) {
                            if ($payment->is_finished == 1) {
                                $canDelete = false;
                                break;
                            }
                        }
                    @endphp
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->created_at?->toDateString() }}</td>
                    <td class="text-center">{{ $student->parentContract?->id_number??'-' }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->age }}</td>
                    <td>{{ $student->parentContract?->package?->name ??'لايوجد' }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>{{ $student->city }}</td>
                    <td class="text-center">
                        @if($student->parentContract)
                            <a class="btn btn-success bg-success btn-sm"
                             target="_blank" href="{{ route('dashboard.download-contract',
                                $student->parentContract?->id) }}">
                                تحميل العقد
                                <i class="fa fa-download"></i>
                            </a>
                        @endif
                        <a class="btn btn-primary btn-sm" href="{{ route('dashboard.students.show', $student->id) }}">
                            <i class="fas fa-eye me-1"></i> عرض
                        </a>

                        <a class="btn btn-info btn-sm" href="{{ route('dashboard.students.edit', $student->id) }}">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>

                            @if($canDelete)
                                <button class="btn btn-danger btn-sm delete-student" data-id="{{ $student->id }}">
                                    حذف
                                </button>
                            @else
                                <button class="btn btn-danger btn-sm" disabled>
                                    حذف
                                </button>
                            @endif

                        @if( $student->parentContract?->package?->payment_type == "tabby" )
                            <button
                                @if($student->is_paid) disabled title="تم الدفع" @endif
                                    type="button"
                                    class="btn btn-sm px-4 text-white {{ $student->is_paid ? 'disabled cursor-not-allowed bg-secondary' : 'bg-success' }}"
                                    style="background-color: #06A996; font-weight: 500; font-size: 0.95rem;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#payConfirmModal"
                                    data-id="{{ $student->id }}"
                                    data-name="{{ $student->name }}">
                                    <i class="fas fa-credit-card me-1"></i> دفع
                            </button>

                        @endif



                        {{-- <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $student->id }}">
                            حذف
                        </button> --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $students->links() }}
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد أنك تريد حذف هذا الطالب؟
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Manual Payment Confirmation Modal -->
<div class="modal fade" id="payConfirmModal" tabindex="-1" aria-labelledby="payConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="manualPayForm" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payConfirmModalLabel">تأكيد الدفع اليدوي</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    هل تريد تأكيد الدفع اليدوي للطالب <strong id="studentName"></strong>؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit"  style="background-color: #06A996; font-weight: 500; font-size: 0.95rem;" class="btn btn-success text-white">تأكيد الدفع</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد حذف الطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد أنك تريد حذف هذا الطالب وجميع بياناته؟</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteStudent">تأكيد الحذف</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    document.addEventListener('DOMContentLoaded', function () {
        var payModal = document.getElementById('payConfirmModal');
        payModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var studentId = button.getAttribute('data-id');
            var studentName = button.getAttribute('data-name');

            // Set student name in modal
            document.getElementById('studentName').textContent = studentName;

            // Update form action
            let form = document.getElementById('manualPayForm');
            form.action = "{{ route('dashboard.students.manual-pay', ':id') }}".replace(':id', studentId);
        });
    });
</script>

<script>
    let deleteStudentId = null;

    // When clicking the delete button, show modal
    $('.delete-student').on('click', function () {
        deleteStudentId = $(this).data('id');
        $('#deleteStudentModal').modal('show');
    });

    // When confirming deletion
    $('#confirmDeleteStudent').on('click', function () {
        if (!deleteStudentId) return;

        $.ajax({
            url: '/dashboard/students/' + deleteStudentId,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                $('#deleteStudentModal').modal('hide');
                alert(res.message);
                // Optionally remove the row without reloading:
                $('button[data-id="'+deleteStudentId+'"]').closest('tr').remove();
            },
            error: function (xhr) {
                $('#deleteStudentModal').modal('hide');
                let msg = xhr.responseJSON?.message || 'حدث خطأ';
                alert(msg);
                console.error(xhr);
            }
        });
    });
</script>


@endpush
@endsection
