@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
        <div class="col-md-12">
            <div class="p-20 bgc-white bd">
                <h6 class="c-grey-900">
                    @isset($course)
                        تحديث الدورة
                    @else
                        إنشاء الدورة
                    @endisset
                </h6>
                <div class="mx-4 text-end">
                    <a class="px-4 btn btn-info" href="{{ route('dashboard.courses.index') }}">
                        رجوع
                    </a>
                </div>
                <div class="mT-30">

                    <form id="createCourseForm" method="POST"
                          action="{{ isset($course) ? route('dashboard.courses.update', $course->id) : route('dashboard.courses.store') }}">
                        @csrf
                        @isset($course)
                            @method('PUT')
                        @endisset

                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الدورة</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $course->name ?? '') }}">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">تاريخ البدء</label>
                            <input type="date" class="form-control @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at" value="{{ old('starts_at', isset($course) ? $course->starts_at->toDateString() : '') }}">
                            @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ends_at" class="form-label">تاريخ الانتهاء (اختياري)</label>
                            <input type="date" class="form-control @error('ends_at') is-invalid @enderror" id="ends_at" name="ends_at" value="{{ old('ends_at', isset($course) && $course->ends_at ? $course->ends_at->toDateString() : '') }}">
                            @error('ends_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror" id="active" name="active" {{ old('active', isset($course) ? $course->active : '') ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">نشط</label>
                            @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                @isset($course)
                                    تحديث الدورة
                                @else
                                    إنشاء الدورة
                                @endisset
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
