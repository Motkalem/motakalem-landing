<?php

 use App\Actions\Paymob\callbackAction;
use App\Http\Controllers\MainController;
use App\Models\ParentContract;
use App\Notifications\SendContractNotification;
use App\Notifications\SuccessSubscriptionPaidNotification;
use Illuminate\Support\Facades\Notification;
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
Route::get('/test', function (){

      $rows = ParentContract::latest()->take(4)->get();


      foreach ($rows as $row) {
            Notification::route('mail', 'Pmo@squarement.sa')
                ->notify(new  SendContractNotification($row));
      }
});


Route::get('/callback', callbackAction::class)->name('callback');


Route::get('/', [MainController::class,'index'])->name('home');
Route::get('/join', [MainController::class,'join'])->name('join');
Route::post('/join', [MainController::class,'sendEmail'])->name('sendEmail');
Route::get('/thankyou', [MainController::class,'thankyouPage'])->name('thankyou');
Route::get('/terms_privacy', [MainController::class,'terms'])->name('terms_privacy');




Route::get('hash/{password}',function($password){

    return Hash::make($password);
});
