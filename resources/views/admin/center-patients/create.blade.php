@extends('admin.layouts.master')

@section('content')
    <div class="p-20 bgc-white bd">
        <h6 class="c-grey-900">إضافة مريض جديد</h6>
        <div class="mx-4 text-end">
            <a class="px-4 btn btn-info" href="{{ route('dashboard.center.center-patients.index') }}">
                رجوع
            </a>
        </div>
        <div class="mT-30">
            <form method="POST" action="{{ route('dashboard.center.center-patients.store') }}">
                @csrf

                <div class="mb-3 row">
                    <label for="name" class="form-label col-sm-2 col-form-label">الإسم</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" placeholder="الإسم">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="mobile_number" class="form-label col-sm-2 col-form-label">رقم الجوال</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('mobile_number') is-invalid @enderror"
                               id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="رقم الجوال">
                        @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="form-label col-sm-2 col-form-label">البريد الإلكتروني</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" placeholder="example@mail.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="id_number" class="form-label col-sm-2 col-form-label">رقم الهوية</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                               id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="رقم الهوية">
                        @error('id_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="id_end_date" class="form-label col-sm-2 col-form-label">تاريخ انتهاء الهوية</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('id_end_date') is-invalid @enderror"
                               id="id_end_date" name="id_end_date" value="{{ old('id_end_date') }}">
                        @error('id_end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="age" class="form-label col-sm-2 col-form-label">العمر</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control @error('age') is-invalid @enderror"
                               id="age" name="age" value="{{ old('age') }}" placeholder="العمر">
                        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="message" class="form-label col-sm-2 col-form-label">ملاحظات</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('message') is-invalid @enderror"
                                  id="message" name="message" placeholder="ملاحظات">{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="center_package_id" class="form-label col-sm-2 col-form-label">الباقة</label>
                    <div class="col-sm-10">
                        <select id="center_package_id" name="center_package_id" class="form-control @error('center_package_id') is-invalid @enderror">
                            <option value="">-- اختر الباقة --</option>
                            @foreach($centerPackages as $package)
                                <option value="{{ $package->id }}" {{ old('center_package_id') == $package->id ? 'selected' : '' }}>
                                    {{ $package->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('center_package_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>


                <div class="mb-3 row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">حفظ المريض</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
