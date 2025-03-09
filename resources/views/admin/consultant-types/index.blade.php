@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <h3 class="text-bold">
                أنواع الإستشارات
                ({{ $consultantTypesCount }})
            </h3>
            <div class="mx-4 text-end">
                <a class="px-4 btn btn-primary" href="{{ route('dashboard.consultant-types.create') }}">
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
                        <th>اسم الاستشارة</th>
                        <th>السعر</th>
                        <th>الحالة </th>
                        <th style="width: 20%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($consultantTypes as $consultantType)
                        <tr>
                            <td>{{ $consultantType->name }}</td>
                            <td>{{ $consultantType->price }}  <span class="riyal-symbol">R</span></td>
                            <td>
                                @if($consultantType->is_active)

                                    <span class="text-success"> نشط </span>
                                 @else

                                    <span class="text-danger"> عير نشط </span>
                                @endif
                            </td>
                            <td class="text-right project-actions">
                                <a class="px-4 btn btn-info btn-sm" href="{{ route('dashboard.consultant-types.edit', $consultantType->id) }}">
                                    تعديل
                                </a>
                                <button class="px-4 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="{{ $consultantType->id }}">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">حذف نوع الاستشارة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف نوع الاستشارة هذا؟
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
                var statusModal = document.getElementById('statusModal');
                statusModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var consultantTypeId = button.getAttribute('data-id');
                    var form = document.getElementById('deleteForm');
                    form.action = "{{ route('dashboard.consultant-types.destroy', ':id') }}".replace(':id', consultantTypeId);
                });
            });
        </script>
    @endpush
@endsection
