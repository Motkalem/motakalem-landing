<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Transaction; // Ensure this model exists

class TransactionsController extends AdminBaseController
{
    public function index()
    {
        $title = 'قائمة المعاملات';
        $transactions = Transaction::orderBy('id', 'desc')->paginate(12);

        return view('admin.transactions.index',
         compact('transactions', 'title'));
    }

    public function show($id)
    {
        $title = 'عرض المعاملة';

        $transaction = Transaction::findOrFail($id);

        return view('admin.transactions.show',
         compact('transaction','title'));
    }
}
