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
                            <a class="btn btn-success bg-success btn-sm" target="_blank" href="{{ route('dashboard.download-contract',
                                $student->parentContract?->id) }}">
                                تحميل العقد
                                <i class="fa fa-download"></i>
                            </a>
                        @endif
                        <a class="btn btn-primary btn-sm" href="{{ route('dashboard.students.show', $student->id) }}">
                            عرض
                        </a>
                        <a class="btn btn-info btn-sm" href="{{ route('dashboard.students.edit', $student->id) }}">
                            تعديل
                        </a>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var studentId = button.getAttribute('data-id');
            var form = document.getElementById('deleteForm');
            form.action = "{{ route('dashboard.students.destroy', ':id') }}".replace(':id', studentId);
        });
    });
</script>
@endpush
@endsection
