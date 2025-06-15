<?php

use App\Actions\HyperPay\CancelRecurringPayment;
use App\Http\Controllers\Dashboard\Center\CenterPaymentsController;
use App\Http\Controllers\Dashboard\Center\CenterPackagesController;
use App\Http\Controllers\Dashboard\Center\PatientsController;
use App\Http\Controllers\Dashboard\ConsultantPatientsController;
use App\Http\Controllers\Dashboard\ConsultantsController;
use App\Http\Controllers\Dashboard\ContactUsMessagesController;
use App\Http\Controllers\Dashboard\DashboardAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\InstallmentPaymentsController;
use App\Http\Controllers\Dashboard\MedicalInquiresController;
use App\Http\Controllers\Dashboard\PackagesController;
use App\Http\Controllers\Dashboard\PaymentsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProgramInquiresController;
use App\Http\Controllers\Dashboard\StudentsController;
use App\Http\Controllers\Dashboard\TransactionsController;
use App\Models\ParentContract;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// --------------------------
// Admin Routes
// --------------------------

Route::group(['middleware' => 'guest:dashboard'], function () {

    Route::get('dashboard/login', [DashboardAuthController::class, 'showLoginForm'])->name('dashboard.login');
    Route::post('dashboard/login', [DashboardAuthController::class, 'login'])->name('dashboard.login.submit');
    Route::get('dashboard/forgot-password', [DashboardAuthController::class, 'showForgotPasswordForm'])->name('dashboard.password.request');
    Route::post('dashboard/forgot-password', [DashboardAuthController::class, 'sendResetLink'])->name('dashboard.password.email');
    Route::get('dashboard/reset-password/{token}', [DashboardAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('dashboard/reset-password', [DashboardAuthController::class, 'resetPassword'])->name('dashboard.password.update');
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:dashboard', 'as' => 'dashboard.'], function () {

    Route::get('panel', [DashboardController::class, 'index'])->name('index');
    Route::resource('packages', PackagesController::class);
    Route::post('packages/update-status/{id}', [PackagesController::class, 'changeStatus'])->name('packages.status');
    Route::resource('payments', PaymentsController::class);
    Route::resource('transactions', TransactionsController::class);

    Route::resource('students', StudentsController::class);
    Route::post('students/{id}', [StudentsController::class, 'payManually'])->name('students.manual-pay');

    Route::get('download-contract/{id}', [StudentsController::class,'downloadContract'])->name('download-contract');

    Route::resource('installment-payments', InstallmentPaymentsController::class);
    Route::post('installment-payments/{id}/send-payment-url', [InstallmentPaymentsController::class, 'sendPaymentLink'])->name('installment-payments.send-payment-url');
    Route::post('installment-payments/{id}', [InstallmentPaymentsController::class, 'deductInstallment'])->name('deductInstallment');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('dashboard/logout', [DashboardAuthController::class, 'logout'])->name('logout');
    Route::post('dashboard/contracts/{id}', [StudentsController::class, 'sendContract'])->name('send-contract');

    Route::resource('consultant-types', ConsultantsController::class);
    Route::resource('consultant-patients', ConsultantPatientsController::class);

    Route::get('consultant-patients/send-link/{id}', [ConsultantPatientsController::class, 'sendPaymentLink'])->name('send-sms-payment-link');
    Route::get('consultation/invoice/{pid}',  [ConsultantPatientsController::class,'sendInvoice'])->name('re-send-sms-invoice-link');

    Route::resource('contact-messages', ContactUsMessagesController::class);
    Route::resource('program-inquires', ProgramInquiresController::class);
    Route::resource('medical-inquires', MedicalInquiresController::class);

    # MOTAKALEM CENTER
    Route::group(['prefix' => 'center', 'as' => 'center.'], function ()
    {
        Route::resource('center-packages', CenterPackagesController::class);
        Route::post('center-packages/update-status/{id}', [CenterPackagesController::class, 'changeStatus'])->name('center-packages.status');

        Route::resource('center-patients', PatientsController::class);
        Route::resource('center-payments', CenterPaymentsController::class);

        Route::post('installment-payments/{id}', [CenterPaymentsController::class, 'deductInstallment'])
            ->name('deductInstallment');

        Route::post('dashboard/send-pay-url/{id}', [CenterPaymentsController::class, 'sendPayUrl'])->name('send-pay-url');
    });
});

Route::get('installment-payments/cancel/{id}', CancelRecurringPayment::class)->name('dashboard.cancel-schedule');


Route::get('/', function (){return to_route('dashboard.login');});

