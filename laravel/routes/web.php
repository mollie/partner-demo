<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get(
        'settings/payment/oauth/confirm',
        'Settings\Payment\OAuthConfirmController'
    )->name('oauth_confirm');

    Route::middleware(['not_connected_to_mollie'])->group(function () {
        Route::get(
            'settings/payment/connect-to-mollie',
            'Settings\Payment\ConnectedToMollieController'
        )->name('connect_to_mollie');
    });

    Route::middleware(['connected_to_mollie'])->group(function () {
        Route::get(
            'settings/payment',
            'Settings\Payment\StatusController'
        )->name('payment_status');
    });
});
