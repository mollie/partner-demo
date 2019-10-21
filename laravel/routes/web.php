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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Settings\Payment\ConnectToMollieController;
use App\Http\Controllers\Settings\Payment\OAuthConfirmController;
use App\Http\Controllers\Settings\Payment\OAuthErrorController;
use App\Http\Controllers\Settings\Payment\ReturnFromMollieController;
use App\Http\Controllers\Settings\Payment\StatusController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::namespace('App\Http\Controllers')->group(function () {
    Auth::routes();
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', HomeController::class)->name('home');

    Route::get('settings/payment/oauth/return', ReturnFromMollieController::class)->name('return_from_mollie');

    Route::get('settings/payment/oauth/confirm/{authCode}', OAuthConfirmController::class)->name('oauth_confirm');

    Route::get('settings/payment/oauth/error/{errorType}', OAuthErrorController::class)->name('oauth_error');

    Route::middleware(['not_connected_to_mollie'])->group(function () {
        Route::get('settings/payment/connect-to-mollie', ConnectToMollieController::class)->name('connect_to_mollie');
    });

    Route::middleware(['connected_to_mollie'])->group(function () {
        Route::get('settings/payment', StatusController::class)->name('payment_status');
    });
});
