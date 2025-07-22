@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: auto;">
        <div class="col-md-12">
            <div class="p-20 bgc-white bd">
                <h6 class="c-grey-900">
                    @isset($consultantPatient)
                        تحديث بيانات المريض
                    @else
                        إنشاء بيانات مريض
                    @endisset
                </h6>
                <div class="mx-4 text-end">
                    <a class="px-4 btn btn-info" href="{{ route('dashboard.consultant-patients.index') }}">
                        رجوع
                    </a>
                </div>
                <div class="mT-30">
                    <form method="POST" action="{{ isset($consultantPatient) ? route('dashboard.consultant-patients.update', $consultantPatient->id) : route('dashboard.consultant-patients.store') }}">
                        @csrf
                        @isset($consultantPatient)
                            @method('PUT')
                        @endisset

                        <!-- Consultation Type Field -->
                        <div class="mb-3 row">
                            <label for="consultation_type_id" class="form-label col-sm-2 col-form-label">نوع الخدمة</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('consultation_type_id') is-invalid @enderror" id="consultation_type_id" name="consultation_type_id">
                                    <option value="" disabled selected>اختر نوع الخدمة</option>
                                    @foreach($consultationTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('consultation_type_id', $consultantPatient->consultation_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }} ( {{$type->price .'' }} {{__('SAR')}} )
                                        </option>
                                    @endforeach
                                </select>
                                @error('consultation_type_id')

                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-3 row">
                            <label for="name" class="form-label col-sm-2 col-form-label">اسم المريض</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $consultantPatient->name ?? '') }}" placeholder="اسم المريض">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Age Field -->
                        <div class="mb-3 row">
                            <label for="age" class="form-label col-sm-2 col-form-label">العمر</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age', $consultantPatient->age ?? '') }}" placeholder="العمر">
                                @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Gender Field -->
                        <div class="mb-3 row">
                            <label for="gender" class="form-label col-sm-2 col-form-label">الجنس</label>
                            <div class="col-sm-10">
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="" disabled selected>اختر الجنس</option>
                                    <option value="male" {{ old('gender', $consultantPatient->gender ?? '') == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('gender', $consultantPatient->gender ?? '') == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Mobile Field -->
                        <div class="mb-3 row">
                            <label for="mobile" class="form-label col-sm-2 col-form-label">رقم الجوال</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $consultantPatient->mobile ?? '') }}" placeholder="رقم الجوال">
                                @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- City Field -->
                        <div class="mb-3 row">
                            <label for="city" class="form-label col-sm-2 col-form-label">المدينة</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $consultantPatient->city ?? '') }}" placeholder="المدينة">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="mb-3 row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary btn-color">
                                    @isset($consultantPatient)
                                        تحديث بيانات المريض
                                    @else
                                        إنشاء بيانات المريض
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
