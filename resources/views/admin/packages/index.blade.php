@extends('admin.layouts.master')

@push('styles')
<link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
    <div class="col-md-12">
        <div class="mx-4 text-end">
            <a class="px-4 btn btn-primary" href="{{ route('dashboard.packages.create') }}">
                + إنشاء
            </a>
        </div>

        <div class="p-20 mt-4 bgc-white bd">
            <table class="table table-striped table-class">
                <thead>
                    <tr>
                        <th style="width: 20%">اسم الباقة</th>
                        <th style="width: 30%">الحالة</th>
                        <th>عدد الشهور</th>
                        <th>القسط الشهري</th>
                        <th style="width: 20%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                    <tr>
                        <td>{{ $package->name }}</td>
                        <td>
                            @if($package->is_active)
                            <span class="text-success text-bold">نشط</span>
                            @else
                            <span class="text-danger text-bold">غير نشط</span>
                            @endif
                        </td>
                        <td><span class="text-success">{{ $package->number_of_months }}</span></td>
                        <td class="project_progress"><span class="text-success">{{ $package->installment_value }}</span></td>
                        <td class="text-right project-actions">
                            <a class="px-4 btn btn-info btn-sm" href="{{ route('dashboard.packages.edit', $package->id) }}">
                                تعديل
                            </a>
                            <button class="px-4 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $package->id }}">
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
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد أنك تريد حذف هذه الباقة؟
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
            var packageId = button.getAttribute('data-id');
            var form = document.getElementById('deleteForm');
            form.action = "{{ route('dashboard.packages.destroy', ':id') }}".replace(':id', packageId);
        });
    });
</script>
@endpush
@endsection
