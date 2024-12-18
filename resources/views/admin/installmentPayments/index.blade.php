@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="gap-20 row pos-r" style="position: relative; height: 1095px;">
    <div class="col-md-12">
        <div class="p-20 mt-4 bgc-white bd">
            <form action="{{URL::current()}}">
                <div class="pb-4 w-25 d-flex align-items-center">
                    <input type="search" name="search" value="{{data_get($_GET,'search')}}" class="form-control" id="search" placeholder="بحث"/>
                    <button class="btn btn-primary btn-sm mx-2">بحث</button>
                </div>
            </form>
            <table class="table table-striped table-class">
                <thead>
                    <tr>
                        <th>اسم الطالب</th>
                        <th>اسم الباقة</th>
                        <th class="text-center"> مكتملة الأقساط  </th>
                        <th style="width: 30%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="paymentsTableBody">
                    @foreach($installmentPayments as $installmentPayment)
                    <tr>
                        <td>{{ $installmentPayment->student?->name }}</td>
                        <td>{{ $installmentPayment->package?->name }}</td>
                        <td class="text-center">
                            @if( $installmentPayment->is_completed)
                                <span class="fw-bold text-success">
                                  مكتملة
                               </span>
                            @else
                                <span class="fw-bold text-danger">
                                غير مكتملة
                               </span>
                            @endif
                        </td>
                        <td class="text-center project-actions">
                            <a href="{{ route('dashboard.installment-payments.show', $installmentPayment->id) }}" class="px-4 btn btn-info btn-sm">
                                 عرض
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
