@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="gap-20 row pos-r">
        <div class="col-md-12">
            <div class="p-20 bgc-white bd">
                <h6 class="c-grey-900">{{ isset($package) ? 'تعديل الباقة' : 'إنشاء باقة للمركز' }}</h6>
                <div class="mx-4 text-end">
                    <a class="px-4 btn btn-info" href="{{ route('dashboard.center.center-packages.index') }}">
                        رجوع
                    </a>
                </div>

                <div class="mT-30">
                    <form method="POST" action="{{ isset($package) ? route('dashboard.center.center-packages.update', $package->id) : route('dashboard.center.center-packages.store') }}">
                        @csrf
                        @if(isset($package))
                            @method('PUT')
                        @endif

                        <!-- Package Name -->
                        <div class="mb-3 row">
                            <label for="name" class="form-label col-sm-2 col-form-label">إسم الباقة</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $package->name ?? '') }}"
                                       placeholder="اسم الباقة">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-3 row">
                            <label for="total" class="form-label col-sm-2 col-form-label">إجمالي المبلغ</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('total') is-invalid @enderror"
                                       id="total" name="total"
                                       value="{{ old('total', $package->total ?? '') }}" placeholder="إجمالي المبلغ">

                                @error('total')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Number of Months -->
                        <div class="mb-3 row">
                            <label for="number_of_months" class="form-label col-sm-2 col-form-label">عدد الشهور</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('number_of_months') is-invalid @enderror"
                                       id="number_of_months" name="number_of_months"
                                       value="{{ old('number_of_months', $package->number_of_months ?? '') }}"
                                       placeholder="عدد الشهور">
                                @error('number_of_months')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Installments -->
                        <div class="mb-3 row py-2 px-2">
                            @php
                                $installments = [
                                    'first_inst'  => 'القسط الاول',
                                    'second_inst' => 'القسط الثاني',
                                    'third_inst'  => 'القسط الثالث',
                                    'fourth_inst' => 'القسط الرابع',
                                    'fifth_inst'  => 'القسط الخامس',
                                ];
                            @endphp

                            @foreach ($installments as $field => $label)
                                <label for="{{ $field }}" class="form-label col-sm-2 col-form-label">{{ $label }}</label>
                                <div class="col-sm-10 mb-2">
                                    <input type="number"
                                           class="form-control @error($field) is-invalid @enderror"
                                           id="{{ $field }}"
                                           name="{{ $field }}"
                                           value="{{ old($field, $package->$field ?? 0) }}"
                                           placeholder="{{ $label }}">
                                    @error($field)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <!-- Start Date -->
                        <div class="mb-3 row">
                            <label for="starts_date" class="form-label col-sm-2 col-form-label">تاريخ البدأ</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('starts_date') is-invalid @enderror"
                                       id="starts_date" name="starts_date"
                                       value="{{ old('starts_date', $package->starts_date ?? '') }}">
                                @error('starts_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="mb-3 row">
                            <label for="ends_date" class="form-label col-sm-2 col-form-label">تاريخ الإنتهاء</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control @error('ends_date') is-invalid @enderror"
                                       id="ends_date" name="ends_date"
                                       value="{{ old('ends_date', $package->ends_date ?? '') }}">
                                @error('ends_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="mb-3 row">
                            <label for="is_active" class="form-label col-sm-2 col-form-label">نشط</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror"
                                           type="checkbox" id="is_active" name="is_active"
                                        {{ old('is_active', $package->is_active ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">إضغط للتنشيط</label>
                                    @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mb-3 row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary btn-color">
                                    {{ isset($package) ? 'تحديث الباقة' : 'إنشاء باقة' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const totalInput = document.getElementById('total');
            const monthsInput = document.getElementById('number_of_months');
            const startDateInput = document.getElementById('starts_date');
            const endDateInput = document.getElementById('ends_date');

            const installmentFields = [
                'first_inst',
                'second_inst',
                'third_inst',
                'fourth_inst',
                'fifth_inst'
            ];

            function isEditMode() {
                return {{ isset($package) ? 'true' : 'false' }};
            }

            function distributeInstallments() {
                const total = parseFloat(totalInput.value);
                const numberOfMonths = parseInt(monthsInput.value);

                if (isNaN(total) || isNaN(numberOfMonths) || numberOfMonths < 1 || numberOfMonths > 5) return;

                const baseAmount = Math.floor((total / numberOfMonths) * 100) / 100;
                let remaining = Math.round((total - (baseAmount * numberOfMonths)) * 100) / 100;

                for (let i = 0; i < installmentFields.length; i++) {
                    const input = document.getElementById(installmentFields[i]);
                    if (!input) continue;

                    if (i < numberOfMonths) {
                        let value = baseAmount;
                        if (remaining > 0.009) {
                            value += 0.01;
                            remaining -= 0.01;
                        }
                        input.value = value.toFixed(2);
                    } else {
                        input.value = 0;
                    }
                }
            }

            function calculateEndDate() {
                const numberOfMonths = parseInt(monthsInput.value);
                const startDate = new Date(startDateInput.value);

                if (isNaN(startDate.getTime()) || isNaN(numberOfMonths) || numberOfMonths < 1) {
                    endDateInput.value = '';
                    return;
                }

                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + numberOfMonths);
                if (startDate.getDate() !== endDate.getDate()) {
                    endDate.setDate(0);
                }

                const yyyy = endDate.getFullYear();
                const mm = String(endDate.getMonth() + 1).padStart(2, '0');
                const dd = String(endDate.getDate()).padStart(2, '0');
                endDateInput.value = `${yyyy}-${mm}-${dd}`;
            }

            totalInput.addEventListener('input', distributeInstallments);
            monthsInput.addEventListener('input', () => {
                distributeInstallments();
                calculateEndDate();
            });
            startDateInput.addEventListener('change', calculateEndDate);
        });
    </script>
@endpush
