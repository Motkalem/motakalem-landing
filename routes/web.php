<?php

use App\Actions\HyperPay\RecurringCheckoutAction;
use App\Actions\HyperPay\RecurringCheckoutResultAction;
use App\Actions\Paymob\callbackAction;
use App\Http\Controllers\MainController;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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

Route::get('/get-registration', function () {

    $url = "https://eu-prod.oppwa.com/v1/checkouts/" .'788650560E28A5F8369682E1DA110619.prod02-vm-tx07'. "/payment";

    $response = Http::withoutVerifying()->get($url);

    Log::info('response: '.now()->format('Y-m-d'), ['response' => $response]);


    if ($response->successful()) {

        $data = $response->json();

        #TODO GET THE FORMAT OF SUCCESS RESPONSE AND EXTRACT THE REGISTRATION ID
        $registrationId = data_get($data,'id')??'8acda4a0922e592f019238cc13433a95';

        return  Redirect::away('https://www.motkalem.com/one-step-closer'.'?'.'status=success');

    }
});

Route::get('checkout', 'App\Http\Controllers\PaymentController@getPayPage')->name('checkout.index');
Route::get('checkout/result/{paymentId}/{studentId}/', 'App\Http\Controllers\PaymentController@getStatus');


Route::get('checkout-recurring/{checkoutId}',
    RecurringCheckoutAction::class)->name('recurring.checkout');

Route::get(   'recurring/result/{paymentId}',RecurringCheckoutResultAction::class);

Route::get('/callback', callbackAction::class)->name('callback');
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/join', [MainController::class, 'join'])->name('join');
Route::post('/join', [MainController::class, 'sendEmail'])->name('sendEmail');
Route::get('/thankyou', [MainController::class, 'thankyouPage'])->name('thankyou');
Route::get('/terms_privacy', [MainController::class, 'terms'])->name('terms_privacy');

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
