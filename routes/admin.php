<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Admin Routes
// --------------------------

Route::group([
    'prefix'     =>  'admin',
    'middleware' => ['web', 'admin'],
    'namespace'  => 'App\Http\Controllers\Dashboard',
    'as'  => 'dashboard.',
], function () {


    Route::get('panel', 'DashboardController@index')->name('index');


        Route::resource( 'packages', 'PackagesController');
        Route::resource( 'payments', 'PaymentsController');
        Route::resource('transactions', 'TransactionsController');
        Route::resource('students', 'StudentsController');
    });
