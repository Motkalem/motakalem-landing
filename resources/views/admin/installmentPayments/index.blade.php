@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />

    <style>
        .progress-circle {
            width: 50px;
            height: 50px;
            background: conic-gradient(
                #4caf50 calc(var(--progress) * 1%),
                #e0e0e0 calc(var(--progress) * 1%)
            );
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
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
                        <th> الأقساط  </th>
                        <th> تاريخ اول قسط  </th>
                        <th class="text-center"> مكتملة الأقساط  </th>
                        <th style="width: 30%" class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody id="paymentsTableBody">
                    @foreach($installmentPayments as $installmentPayment)
                    <tr>
                        <td>{{ $installmentPayment->student?->name }}</td>
                        <td>{{ $installmentPayment->package?->name }}</td>
                        <td>
                            @if($installmentPayment->installments?->count())

                                <div class="progress-circle text-white"  style="--progress:
                            {{( $installmentPayment->installments()->where('is_paid', 1)->count()
                            / $installmentPayment->installments?->count()) * 100}};">

                                    {{ $installmentPayment->installments()->where('is_paid', 1)->count()
                                .'/'. $installmentPayment->installments?->count() }}
                                </div>

                            @else
                                <div class="progress-circle text-white"  style="--progress:
                                 {{(0  / $installmentPayment->package?->number_of_months) * 100}};">

                                    {{ 0  .'/'. $installmentPayment->package?->number_of_months }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $installmentPayment->first_installment_date }}</td>

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
