<?php

use App\Actions\HyperPay\RecurringCheckoutAction;
use App\Actions\HyperPay\RecurringCheckoutResultAction;
use App\Http\Controllers\Dashboard\Center\CenterPayController;
use App\Http\Controllers\Dashboard\ConsultantPatientsController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PaymentController;
use App\Notifications\Admin\HyperPayNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use App\Models\InstallmentPayment;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function () {
    $payment = InstallmentPayment::query()
        ->with(['hyperpayWebHooksNotifications' => function($query) {
            $query->where('type', 'init recurring payment');
        }])
        ->where('id', 95)
        ->firstOrFail();

    $notification = $payment->hyperpayWebHooksNotifications
        ->filter(function($notification) {
            $resultCode = data_get($notification->payload, 'result.code');
            $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';
            return $notification->type === 'init recurring payment' &&
                   preg_match($successPattern, $resultCode) === 1;
        }) ->first();

    return [
        'recurringPaymentAgreement' => data_get($notification, 'payload.customParameters.recurringPaymentAgreement'),
        'merchantTransactionId' => data_get($notification, 'payload.merchantTransactionId')
    ];
});


Route::get('checkout', 'App\Http\Controllers\PaymentController@getPayPage')->name('checkout.index');
Route::get('checkout/result/{paymentId}/{studentId}/',  [PaymentController::class,'getStatus']);


# CENTER RECURRING PAYMENTS
Route::get('center-pay/{payid}/{patid}', [CenterPayController::class, 'getPayPage'])->name('center.recurring.checkout');
Route::get('center/checkout-result/{payid}/{patid}/',  [CenterPayController::class,'getStatus']);
Route::get('center/thank-you/{payid}/',  [CenterPayController::class,'getThankYouPage'])->name('center.thank.you');

Route::get('invalid-url',  [CenterPayController::class,'invalidUrl'])->name('center.invalid.url');

//Route::get('center/checkout', [CenterPayController::class,'getPayPage'])->name('center.checkout.index');


# CONSULTATION PAYMENT CYCLE
Route::get('consultation/checkout',  [ConsultantPatientsController::class,'getPayPage'])->name('checkout.consultation.index');

Route::get('consultation/checkout/result/{pid}',  [ConsultantPatientsController::class,'getStatus'])
    ->name('checkout.consultation.status');
Route::get('consultation/invoice/{pid}',  [ConsultantPatientsController::class,'getInvoice'])
    ->name('checkout.send-sms-invoice-link');

Route::get('consultation/send-invoice/{pid}',  [ConsultantPatientsController::class,'sendInvoiceLink'])
    ->name('checkout.consultation.send-invoice');;

# Program RECURRING PAYMENTS
Route::get('checkout-recurring/{paymentId}/{stdId}',RecurringCheckoutAction::class)->name('recurring.checkout');
Route::get('recurring/result/{paymentId}',RecurringCheckoutResultAction::class);

// Route::get('/callback', callbackAction::class)->name('callback');

/*Route::get('/home', [MainController::class, 'index'])->name('home');

Route::get('/join', [MainController::class, 'join'])->name('join');
Route::post('/join', [MainController::class, 'sendEmail'])->name('sendEmail');
Route::get('/thankyou', [MainController::class, 'thankyouPage'])->name('thankyou');
Route::get('/terms_privacy', [MainController::class, 'terms'])->name('terms_privacy');*/

Route::get('hash/{password}', function ($password) {

    return Hash::make($password);
});



Route::get('encrypt-form', function () {

    return view('encrypt-form');
});

Route::post('encrypt-form-store', function (Request $request) {

    if ($request->has(['data', 'iv'])) {

        $encryptedData = base64_decode($request->input('data'));
        $iv = base64_decode($request->input('iv'));
        $key = 'secret key 123';

        $decryptedData = openssl_decrypt($encryptedData, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return response()->json([
            'decryptedData' => $decryptedData,
            'iv' => $iv
        ]);
    }
    return response()->json(['error' => 'Invalid data'], 400);
})->name('process');
