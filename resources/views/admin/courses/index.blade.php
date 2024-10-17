@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <div class="mx-4 text-end">
                <a class="px-4 btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
                    + إنشاء
                </a>
            </div>

            <div class="p-20 mt-4 bgc-white bd">
                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th style="width: 20%">اسم الدورة</th>
                        <th>تاريخ البدء</th>
                        <th>تاريخ الانتهاء</th>
                        <th>السعر</th>
                        <th>عدد الطلاب</th>
                        <th>الحالة</th>
                        <th style="width: 20%" class="text-center">{{ __('الإجراءات') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td><a href="{{route('dashboard.courses.show', $course->id)}}">{{ $course->name }} </a></td>
                            <td>{{ $course->starts_at  }}</td>
                            <td>{{ $course->ends_at  }}</td>
                            <td>{{ $course->price }} {{ __('SAR') }}</td>
                            <td> {{ $course->contracts?->count() }}</td>
                            <td>
                                @if($course->active)
                                    <span class="text-success">نشط</span>
                                @else
                                    <span class="text-danger">غير نشط</span>
                                @endif
                            </td>
                            <td class="text-center project-actions ">
                                <button class="px-4 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $course->id }}">
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

    <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCourseModalLabel">إنشاء دورة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createCourseForm" method="POST" action="{{ route('dashboard.courses.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الدورة</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"  >
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">تاريخ البدء</label>
                            <input type="date" class="form-control @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at"  >
                            @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ends_at" class="form-label">تاريخ الانتهاء (اختياري)</label>
                            <input type="date" class="form-control @error('ends_at') is-invalid @enderror" id="ends_at" name="ends_at">
                            @error('ends_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">السعر (بالريال السعودي)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01"  >
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror" id="active" name="active">
                            <label class="form-check-label" for="active">نشط</label>
                            @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" form="createCourseForm">حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد حذف هذه الدورة؟
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
           $(document).ready(function () {
               $('#createCourseForm').submit(function (e) {
                   e.preventDefault();

                   $.ajax({
                       url: $(this).attr('action'),
                       method: 'POST',
                       data: $(this).serialize(),
                       dataType: 'json',
                       success: function
                           (response) {
                           if (response.success)
                           {
                               location.reload();
                           } else {

                           }
                       },
                       error: function (error) {
                           if( error.responseJSON.errors)
                            showValidationErrors( error.responseJSON.errors);

                       }
                   });
               });

               function showValidationErrors(errors) {
                   // Clear any existing error messages
                   $('.invalid-feedback').remove();
                   $('.form-control').removeClass('is-invalid');

                    $.each(errors, function(field, message) {
                       var input = $('#' + field);
                       var errorContainer = input.parent();

                       errorContainer.append('<div class="invalid-feedback">' + message + '</div>');
                       input.addClass('is-invalid');
                   });
               }

               document.addEventListener('DOMContentLoaded', function () {
                   var deleteModal = document.getElementById('deleteModal');
                   deleteModal.addEventListener('show.bs.modal', function (event) {
                       var button = event.relatedTarget;
                       var courseId = button.getAttribute('data-id');
                       var form = document.getElementById('deleteForm');
                       form.action = "{{ route('dashboard.courses.destroy', ':id') }}".replace(':id', courseId);
                   });
               });
           });


       </script>
    @endpush
@endsection
