@extends('admin.layouts.master')

@push('styles')
    <link href="{{ asset('admin/table.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="p-20 bgc-white bd">
    <h6 class="c-grey-900">قائمة المعاملات</h6>

    <div class="mT-30">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>رقم المعاملة</th>
                    <th>رقم طلب الدفع</th>
                    <th>الحالة</th>
                    <th>المبلغ</th>
                     <th style="width: 20%" class="text-center">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_id }}</td>
                    <td>{{ $transaction->client_pay_order_id }}</td>
                  <td class="{{ $transaction->success =='true' ? 'text-success' : 'text-danger'}}">
                    {{ $transaction->success  =='true' ? 'نجاح' : 'فشل' }}
                </td>
                    <td>{{ $transaction->amount .' '. __('SAR')}}</td>
                    <td class="text-center">
                        <a class="btn btn-info btn-sm" href="{{ route('dashboard.transactions.show', $transaction->id) }}">
                            {{__('Show')}}
                        </a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $transactions->links() }}
    </div>
</div>
@endsection
