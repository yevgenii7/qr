<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
    return redirect('/qr-codes');
});

Route::get('qr-codes/gateway', 'QrController@gateway')->name('qr-codes.gateway-qr');
Route::get('qr-codes/get-qr/{id}', 'QrController@getQr')->name('qr-codes.get-qr');
Route::get('qr-codes/download/{file_name}', 'QrController@downloadQr')->name('qr-codes.download-qr');
Route::resource('qr-codes', 'QrController');