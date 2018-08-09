<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('all_transactions', 'ApiController@getAllTransactions');

Route::get('my_transactions', 'ApiController@getMyTransactions');


Route::get('dashboard/{page}/{selectedCurrency}', 'ApiController@getDashboard');

Route::get('listings', 'ApiController@getListings');
Route::get('internal_listings', 'ApiController@getInternalListings');
Route::get('coin_price/{id}', 'ApiController@getCoinPrice');
Route::get('coin/{id}', 'ApiController@getCoin');



Route::get('update_prices', 'ApiController@updatePrices');
Route::get('update_listings', 'ApiController@updateListings');






