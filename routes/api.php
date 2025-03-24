<?php

use App\Actions\Api\General\GetPackages;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\JoinController;
use App\Http\Controllers\Api\HyperPayWebHooksController;
use App\Http\Controllers\Api\MedicalInquiresController;
use App\Http\Controllers\Api\ProgramInquiresController;
use App\Http\Controllers\Dashboard\ConsultantPatientsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\HyperPay\CreditAction;
use App\Actions\HyperPay\ExecuteRecurringPayment;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/join', [JoinController::class, 'store']);

Route::post('/send-contract', [JoinController::class, 'sendContract']);

Route::post('/contact-us', [ContactUsController::class, 'store']);

Route::get('/packages', GetPackages::class);

Route::post('/hyperpay/webhook', [HyperPayWebHooksController::class, 'store']);

Route::post('/credit', CreditAction::class)->name('credit');

Route::post('/excute-recurring',   ExecuteRecurringPayment::class);

Route::post('/register-patient',   [ConsultantPatientsController::class, 'store']);


Route::post('/get-consultation-data',   [ConsultationController::class, 'getConsultationData']);
Route::post('/register-hearing-consultation',   [ConsultationController::class, 'store']);


# MOTAKALEM PROGRAM INQUIRIES
Route::post('/program-inquiry', [ProgramInquiresController::class, 'store']);
Route::post('/medical-inquiry', [MedicalInquiresController::class, 'store']);


