<?php

use App\Actions\Api\General\GetPackages;
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\JoinController;
use App\Http\Controllers\Api\HyperPayWebHooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Paymob\CreditAction;
use App\Models\Package;

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

Route::post('/credit', CreditAction::class)->name('credit');

Route::get('/packages', GetPackages::class);

Route::post('/hyperpay/webhook', [HyperPayWebHooksController::class, 'store']);
