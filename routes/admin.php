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
});
