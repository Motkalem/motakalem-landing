@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <div class="p-20 bgc-white bd">
                <h6 class="c-grey-900">
                    @isset($consultantType)
                        تحديث نوع المستشار
                    @else
                        إنشاء نوع مستشار
                    @endisset
                </h6>
                <div class="mx-4 text-end">
                    <a class="px-4 btn btn-info" href="{{ route('dashboard.consultant-types.index') }}">
                        رجوع
                    </a>
                </div>
                <div class="mT-30">
                    <form method="POST" action="{{ isset($consultantType) ? route('dashboard.consultant-types.update', $consultantType->id) : route('dashboard.consultant-types.store') }}">
                        @csrf
                        @isset($consultantType)
                            @method('PUT')
                        @endisset

                        <!-- Consultant Type Name Field -->
                        <div class="mb-3 row">
                            <label for="name" class="form-label col-sm-2 col-form-label">اسم نوع الإستشارة</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $consultantType->name ?? '') }}" placeholder="اسم نوع الإستشارة">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3 row">
                            <label for="description" class="form-label col-sm-2 col-form-label">الوصف</label>
                            <div class="col-sm-10">
                                <input type="number"
                                       value="{{ old('price', $consultantType->price ?? '') }}"
                                       class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" placeholder="السعر" />
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3 row">
                            <label for="description" class="form-label col-sm-2 col-form-label"> الرسالة </label>
                            <div class="col-sm-10">
        <textarea rows="8"
                  class="form-control @error('message') is-invalid @enderror"
                  name="message" placeholder="اكتب رسالتك هنا...">@unless(isset($consultantType))‏عزيزي {patient_name}،
            لحجز موعدك في مركز متكلم، يرجى تأكيد الحجز لباقة {package_name} بسعر {package_price} ريال من خلال الرابط التالي:
            🔗 {payment_link}
            للاستفسار، تواصل معنا.
            تحياتنا، فريق متكلم@else{{$consultantType->message}}@endunless</textarea>

                                <small class="form-text text-muted">
                                    يمكن استخدام المتغيرات التالية تلقائيًا في الرسالة، ويجب عدم حذف أي منها لضمان عمل الرسالة بشكل صحيح:<br>
                                    <strong>{patient_name}</strong> اسم المراجع، <strong>{package_name}</strong> اسم الباقة،
                                    <strong>{package_price}</strong> سعر الباقة، <strong>{payment_link}</strong> رابط الدفع.
                                </small>

                                @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>




                        <!-- Active Status -->
                        <div class="mb-3 row">
                            <label for="is_active" class="form-label col-sm-2 col-form-label">نشط</label>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $consultantType->is_active ?? false) ? 'checked' : '' }}>
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
                                    @isset($consultantType)
                                        تحديث نوع الإستشارة
                                    @else
                                        إنشاء نوع الإستشارة
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
