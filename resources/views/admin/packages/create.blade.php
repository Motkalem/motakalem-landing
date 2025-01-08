@extends('admin.layouts.master')

@push('styles')
<link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
    <div class="col-md-12">
        <div class="p-20 bgc-white bd">
            <h6 class="c-grey-900">
                @isset($package)
                    تحديث الباقة
                @else
                    إنشاء باقة
                @endisset
            </h6>
            <div class="mx-4 text-end">
                <a class="px-4 btn btn-info" href="{{ route('dashboard.packages.index') }}">
                    رجوع
                </a>
            </div>
            <div class="mT-30">

                <form method="POST" action="{{ isset($package) ? route('dashboard.packages.update', $package->id) : route('dashboard.packages.store') }}">
                    @csrf
                    @isset($package)
                        @method('PUT')
                    @endisset
                <!-- Payment Type Radio Buttons -->
                <div class="mb-3 row">
                    <label class="form-label col-sm-2 col-form-label">نوع الدفع</label>
                    <div class="cursor-pointer col-sm-10 d-flex align-items-center">
                        <div class="cursor-pointer form-check me-4 ">
                            <input class="form-check-input" required type="radio" name="payment_type" id="one_time" value="one time"
                                {{ old('payment_type', $package->payment_type ?? '') == 'one time' ? 'checked' : '' }}>
                            <label class="form-check-label" for="one_time">دفع مرة واحدة</label>
                        </div>
                        <div class=" form-check">
                            <input class="form-check-input" required type="radio" name="payment_type" id="installments" value="installments"
                                {{ old('payment_type', $package->payment_type ?? '') == 'installments' ? 'checked' : '' }}>
                            <label class="form-check-label" for="installments">تقسيط</label>
                        </div>
                    </div>
                </div>
                    <!-- Package Name Field -->
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


                    <!-- Total Payment Amount -->
                    <div class="mb-3 row" id="total_container" style="display: none;">
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

                    <!-- Installment Value -->
                    <div class="mb-3 row py-2 px-2" id="installment_value_container" style="display: none;">
                        <!-- Number of Months -->
                        <div class="mb-3 row" id="number_of_months_container" style="display: none;">
                            <label for="number_of_months" class="form-label col-sm-2 col-form-label">عدد الشهور</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('number_of_months') is-invalid @enderror" id="number_of_months"
                                       name="number_of_months" value="{{ old('number_of_months', $package->number_of_months ?? '') }}"
                                       placeholder="عدد الشهور">
                                @error('number_of_months')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <label for="first_inst" class="form-label col-sm-2 col-form-label">  القسط الاول</label>
                        <div class="col-sm-10 mb-2">
                            <input type="number" class="form-control @error('first_inst') is-invalid @enderror"
                            id="first_inst" name="first_inst"
                             value="{{ old('first_inst', $package->first_inst ?? 0) }}" placeholder=" القسط الاول  ">
                             @error('first_inst')

                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="second_inst" class="form-label col-sm-2 col-form-label ">  القسط الثاني</label>
                        <div class="col-sm-10  mb-2">
                            <input type="number" class="form-control @error('second_inst') is-invalid @enderror"
                            id="second_inst" name="second_inst"
                             value="{{ old('second_inst', $package->second_inst ?? 0) }}" placeholder=" القسط الثاني  ">
                             @error('second_inst')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="third_inst" class="form-label col-sm-2 col-form-label ">  القسط الثالث</label>
                        <div class="col-sm-10  mb-2">
                            <input type="number" class="form-control @error('third_inst') is-invalid @enderror"
                            id="third_inst" name="third_inst"
                             value="{{ old('third_inst', $package->third_inst ?? 0) }}" placeholder=" القسط الثالث  ">
                             @error('third_inst')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="fourth_inst" class="form-label col-sm-2 col-form-label ">  القسط الرابع</label>
                        <div class="col-sm-10  mb-2">
                            <input type="number" class="form-control @error('fourth_inst') is-invalid @enderror"
                                   id="fourth_inst" name="fourth_inst"
                                   value="{{ old('fourth_inst', $package->fourth_inst ?? 0) }}" placeholder=" القسط الرابع">
                            @error('fourth_inst')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <label for="fifth_inst" class="form-label col-sm-2 col-form-label ">  القسط الخامس</label>
                        <div class="col-sm-10  mb-2">
                            <input type="number" class="form-control @error('fifth_inst') is-invalid @enderror"
                                   id="fifth_inst" name="fifth_inst"
                                   value="{{ old('fifth_inst', $package->fifth_inst ?? 0) }}" placeholder=" القسط الخامس">
                            @error('fifth_inst')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>





                    <!-- Start Date -->
                    <div class="mb-3 row"   >
                        <label for="starts_at" class="form-label col-sm-2 col-form-label">تاريخ البدأ</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control @error('starts_date') is-invalid @enderror" id="starts_date"
                                   name="starts_date" value="{{ old('starts_date', $package->starts_date ?? '') }}"
                                   placeholder=" تاريخ البدأ">

                            @error('starts_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="mb-3 row">
                        <label for="starts_at" class="form-label col-sm-2 col-form-label">تاريخ الإنتهاء</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control @error('ends_date') is-invalid @enderror" id="ends_date"
                                   name="ends_date"  value="{{ old('ends_date', $package->ends_date ?? '') }}"
                                   placeholder=" تاريخ الإنتهاء">
                            @error('ends_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="is_active" class="form-label col-sm-2 col-form-label">نشط</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $package->is_active ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">إضغط للتنشيط</label>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary btn-color">
                                @isset($package)
                                    تحديث بيانات الباقه
                                @else
                                    إنشاء باقة
                                @endisset
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
   document.addEventListener('DOMContentLoaded', function() {
    // Select the radio buttons
    const oneTimePaymentRadio = document.getElementById('one_time');
    const installmentsPaymentRadio = document.getElementById('installments');

    // Function to toggle visibility of fields
    function togglePaymentFields() {
        // Find the selected payment type radio button
        const selectedPaymentType = document.querySelector('input[name="payment_type"]:checked');

        // Check if a radio button is selected
        if (selectedPaymentType) {
            const paymentType = selectedPaymentType.value;

            const totalPaymentContainer = document.getElementById('total_container');

            const installmentValueContainer = document.getElementById('installment_value_container');

            const numberOfMonthsContainer = document.getElementById('number_of_months_container');

            if (paymentType === 'one time') {

                totalPaymentContainer.style.display = 'flex';
                installmentValueContainer.style.display = 'none';
                numberOfMonthsContainer.style.display = 'none';

            } else if (paymentType === 'installments') {
                totalPaymentContainer.style.display = 'none';
                installmentValueContainer.style.display = 'flex';
                numberOfMonthsContainer.style.display = 'flex';
            }
        }
    }

    // Check if the radio buttons exist
    if (oneTimePaymentRadio && installmentsPaymentRadio) {
        // Attach change event listeners to both radio buttons
        oneTimePaymentRadio.addEventListener('change', togglePaymentFields);
        installmentsPaymentRadio.addEventListener('change', togglePaymentFields);
    }

    // Initialize the form based on the default selected payment type
    togglePaymentFields();
});

</script>
@endpush
