<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\InstallmentPayment;


class InstallmentPaymentsController extends AdminBaseController
{
    public function index()
    {
        $title = 'المدفوعات المجدولة';

        $installmentPayments = InstallmentPayment::with(['student', 'package'])->orderBy('id', 'desc')->paginate(12);

        return view(
         'admin.installmentPayments.index',
            compact(
                'installmentPayments',
                'title',
            )
        );
    }


    public function show($id)
    {
        $title = 'عرض الدفعه ';

        $installmentPayment = InstallmentPayment::findOrFail($id);

        return view('admin.installmentPayments.show',
         compact('installmentPayment','title'));
    }
}
