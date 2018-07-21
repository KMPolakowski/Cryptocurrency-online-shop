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
Route::get('/', 'PagesController@home');
Route::get('/home', 'PagesController@index');
Route::get('buy', 'PagesController@index');
Route::get('sell', 'PagesController@index');
Route::post('sell', 'PaymentController@sell');

Route::get('/wallet', 'PagesController@index');
Route::get('/data/wallet', 'WalletController@index');

Route::get('buy/crypto_names', 'PaymentController@getCryptoNames');
Route::post('buy/pay', 'PaymentController@pay');
Route::get('buy/pay/success', 'PaymentController@success');

Route::get('test', 'PaymentController@getCryptoNames');


Auth::routes();


