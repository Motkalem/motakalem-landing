@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">إنشاء دفعة جديدة</h6>
    <div class="mx-4 text-end">
        <a class="px-4 btn btn-info" href="{{ route('dashboard.payments.index') }}">رجوع</a>
    </div>
    <div class="mT-30">
        <form method="POST" action="{{ route('dashboard.payments.store') }}">
            @csrf

            <div class="mb-3 row">
                <label for="student_id" class="form-label col-sm-2 col-form-label">اسم الطالب</label>
                <div class="col-sm-10">
                    <select class="form-select" id="student_id" name="student_id" required>
                        <option value="" disabled selected>اختر طالباً</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="package_id" class="form-label col-sm-2 col-form-label">اسم الباقة</label>
                <div class="col-sm-10">
                    <select class="form-select" id="package_id" name="package_id" required>
                        <option value="" disabled selected>اختر باقة</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="payment_type" class="form-label col-sm-2 col-form-label">نوع الدفع</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="payment_type" name="payment_type" placeholder="نوع الدفع" value="{{ old('payment_type') }}">
                    @error('payment_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="is_finished" class="form-label col-sm-2 col-form-label">هل اكتمل</label>
                <div class="col-sm-10">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_finished" name="is_finished" {{ old('is_finished') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_finished">إضغط للتنشيط</label>
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary btn-color">حفظ الدفعة</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
