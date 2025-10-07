@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />


@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">


        <div class="col-md-12">
            <h3 class="text-bold">
                باقات المركز
                ({{ $packagesCount }})
            </h3>
            <div class="mx-4 text-end">

                <a class="px-4 btn btn-primary" href="{{ route('dashboard.center.center-packages.create') }}">
                    + إنشاء
                </a>
            </div>

            <div class="p-20 mt-4 bgc-white bd">
                <form action="{{URL::current()}}">
                    <div class="pb-4 w-25 d-flex align-items-center">
                        <input type="search" name="search" value="{{data_get($_GET,'search')}}"
                               class="form-control" id="search" placeholder="بحث"/>
                        <button class="btn btn-primary btn-sm mx-2">بحث</button>
                    </div>
                </form>
                <table class="table table-striped table-class">
                    <thead>
                    <tr>
                        <th class="text-center">اسم الباقة</th>
                        <th class="text-center">نوع الدفع</th>
                        <th class="text-center" > تاريخ البدأ  </th>
                        <th class="text-center" > تاريخ الإنتهاء  </th>
                        <th class="text-center">الحالة</th>
                        <th class="text-center">عدد الشهور</th>
                        <th class="text-center">القسط الاول</th>
                        <th class="text-center">الإجمالي</th>
                        <th class="text-center" style="width: 20%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td class="text-center"> {{ $package->name }}</td>
                            <td class="text-center">{{ $package->payment_type == 'one time' ? 'دفع مرة واحدة' : 'تقسيط' }}</td>
                            <td class="text-center"> {{ $package->starts_date }}</td>
                            <td class="text-center"> {{ $package->ends_date }}</td>
                            <td class="text-center">
                                @if($package->is_active)
                                    <span class="text-success text-bold">نشط</span>
                                @else
                                    <span class="text-danger text-bold">غير نشط</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span >{{  $package->number_of_months == null ? '---': $package->number_of_months }}</span>
                            </td>
                            <td class="text-center project_progress">
                            <span>
                            {{$package->first_inst == null ? '---' : $package->first_inst.' ' }}
                                @if($package->first_inst)
                                    <span class="riyal-symbol">R</span>
                                @endif
                            </span>
                            </td>

                            <td class="text-center project_progress ">
                            <span >
                            {{$package->total == null ? '---' : $package->total.' '}}
                                @if($package->total)
                                    <span class="riyal-symbol">R</span>
                                @endif
                            </span>
                            </td>
                            <td class="text-right project-actions">
                                <a class="px-4 btn btn-info btn-sm" href="{{ route('dashboard.center.center-packages.edit', $package->id) }}">
                                    تعديل
                                </a>
                                <button class="px-4 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="{{ $package->id }}" data-status="{{ $package->is_active ? 'deactivate' : 'activate' }}">
                                    تغيير الحالة
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">تغيير الحالة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد أنك تريد تغيير حالة هذه الباقة؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>

                    <form id="statusForm" method="POST" action="">
                        @csrf
                        @method('post')
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
                    var packageId = button.getAttribute('data-id');
                    var statusAction = button.getAttribute('data-status');
                    var form = document.getElementById('statusForm');
                    form.action = "{{ route('dashboard.center.center-packages.status', ':id') }}".replace(':id', packageId);
                });
            });
        </script>
    @endpush
@endsection
