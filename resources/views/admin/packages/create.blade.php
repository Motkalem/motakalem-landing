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

                    <div class="mb-3 row">
                        <label for="installment_value" class="form-label col-sm-2 col-form-label">قيمة القسط</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control @error('installment_value') is-invalid @enderror"
                            id="installment_value" name="installment_value"
                             value="{{ old('installment_value', $package->installment_value ?? '') }}" placeholder="قيمة القسط">

                             @error('installment_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
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

                    <div class="mb-3 row">
                        <label for="is_active" class="form-label col-sm-2 col-form-label">نشط   </label>
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
