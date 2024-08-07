<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Transaction; // Ensure this model exists

class TransactionsController extends AdminBaseController
{
    public function index()
    {
        $transactions = Transaction::orderBy('id', 'desc')->paginate(12);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }
}
