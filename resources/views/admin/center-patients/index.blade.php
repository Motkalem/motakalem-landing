@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="p-20 bgc-white bd">
        <h6 class="c-grey-900">
            @lang('Center Patient')
            ({{ $patientsCount }})
        </h6>

        <div class="mx-4 text-end mb-3">
            <a class="px-4 btn btn-primary" href="{{ route('dashboard.center.center-patients.create') }}">
                إضافة بيانات مريض
            </a>
        </div>

        <div class="mT-30">
            <form action="{{ URL::current() }}">
                <div class="pb-4 w-25 d-flex align-items-center">
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="بحث" />
                    <button class="btn btn-primary btn-sm mx-2">بحث</button>
                </div>
            </form>

            <table class="table table-striped table-class">
                <thead>
                <tr>
                    <th>الاسم</th>
                    <th>المدينة</th>
                    <th>الباقة</th>
                    <th>رقم الهاتف</th>
                    <th> البريد الالكتروني </th>
                    <th>العمر</th>
                    <th>المصدر</th>
                    <th class="text-center">الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->city }}</td>
                        <td>{{ $patient->centerInstallmentPayment?->centerPackage?->name }}</td>
                        <td>{{ $patient->mobile_number }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->age }}</td>
                        <td>{{ $patient->source }}</td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-sm" href="{{ route('dashboard.center.center-patients.show', $patient->id) }}">
                                عرض
                            </a>
                            <a class="btn btn-info btn-sm" href="{{ route('dashboard.center.center-patients.edit', $patient->id) }}">
                                تعديل
                            </a>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-id="{{ $patient->id }}">
                                حذف
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="pagination mt-4">
                {{ $patients->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف هذا المريض؟
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteModal = document.getElementById('deleteModal');
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const patientId = button.getAttribute('data-id');
                    const form = document.getElementById('deleteForm');
                    form.action = "{{ route('dashboard.center.center-patients.destroy', ':id') }}".replace(':id', patientId);
                });
            });
        </script>
    @endpush
@endsection
