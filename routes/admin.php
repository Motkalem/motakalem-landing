<?php

use App\Actions\HyperPay\CancelRecurringPayment;
use Illuminate\Support\Facades\Route;

// --------------------------
// Admin Routes
// --------------------------

    Route::get('checkout', 'App\Http\Controllers\PaymentController@getPayPage')->name('checkout.index');
    Route::get('checkout/result/{paymentId}/{studentId}/', 'App\Http\Controllers\PaymentController@getStatus');

    Route::group([
        'prefix' => 'admin',
        'middleware' => ['web', 'admin'],
        'namespace' => 'App\Http\Controllers\Dashboard',
        'as' => 'dashboard.',
    ], function () {


        Route::get('panel', 'DashboardController@index')->name('index');

        Route::resource('packages', 'PackagesController');
        Route::resource('payments', 'PaymentsController');
        Route::resource('transactions', 'TransactionsController');
        Route::resource('students', 'StudentsController');
        Route::resource('installment-payments', 'InstallmentPaymentsController');
        Route::resource('contact-messages', 'ContactUsMessagesController');
});

Route::get('installment-payments/cancel/{id}', CancelRecurringPayment::class)
    ->name('dashboard.cancel-schedule');
