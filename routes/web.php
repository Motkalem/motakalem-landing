<?php

use App\Http\Controllers\MainController;
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

Route::get('/', [MainController::class,'index'])->name('home');
Route::get('/join', [MainController::class,'join'])->name('join');
Route::post('/join', [MainController::class,'sendEmail'])->name('sendEmail');
Route::get('/thankyou', [MainController::class,'thankyouPage'])->name('thankyou');



Route::get('hash/{password}',function($password){

    return Hash::make($password);
});
