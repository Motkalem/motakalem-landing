<?php

use App\Actions\HyperPay\CancelRecurringPayment;
use App\Http\Controllers\Dashboard\ContactUsMessagesController;
use App\Http\Controllers\Dashboard\DashboardAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\InstallmentPaymentsController;
use App\Http\Controllers\Dashboard\PackagesController;
use App\Http\Controllers\Dashboard\PaymentsController;
use App\Http\Controllers\Dashboard\StudentsController;
use App\Http\Controllers\Dashboard\TransactionsController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Admin Routes
// --------------------------

    Route::get('checkout', 'App\Http\Controllers\PaymentController@getPayPage')->name('checkout.index');
    Route::get('checkout/result/{paymentId}/{studentId}/', 'App\Http\Controllers\PaymentController@getStatus');


Route::group(['middleware' => 'guest:dashboard'], function () {
    Route::get('dashboard/login', [DashboardAuthController::class, 'showLoginForm'])->name('dashboard.login');
    Route::post('dashboard/login', [DashboardAuthController::class, 'login'])->name('dashboard.login.submit');
    Route::get('dashboard/forgot-password', [DashboardAuthController::class, 'showForgotPasswordForm'])->name('dashboard.password.request');
    Route::post('dashboard/forgot-password', [DashboardAuthController::class, 'sendResetLink'])->name('dashboard.password.email');
    Route::get('dashboard/reset-password/{token}', [DashboardAuthController::class, 'showResetForm'])->name('dashboard.password.reset');
    Route::post('dashboard/reset-password', [DashboardAuthController::class, 'resetPassword'])->name('dashboard.password.update');
});

Route::group(['prefix'=> 'dashboard','middleware' => 'auth:dashboard','as'=>'dashboard.'], function () {

        Route::get('panel', [DashboardController::class,'index'])->name('index');

        Route::resource('packages', PackagesController::class);
        Route::resource('payments', PaymentsController::class);
        Route::resource('transactions', TransactionsController::class);
        Route::resource('students', StudentsController::class);
        Route::resource('installment-payments', InstallmentPaymentsController::class);
        Route::resource('contact-messages', ContactUsMessagesController::class);

    Route::post('dashboard/logout', [DashboardAuthController::class, 'logout'])->name('logout');

});

Route::get('installment-payments/cancel/{id}', CancelRecurringPayment::class)
    ->name('dashboard.cancel-schedule');
