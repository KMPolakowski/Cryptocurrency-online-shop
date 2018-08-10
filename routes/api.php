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


//Transactions of certain user (api_token)
Route::get('my_transactions', 'ApiController@getMyTransactions');

// Data from CoinMarketCap.com, free access
Route::get('dashboard/{page}/{selectedCurrency}', 'ApiController@getDashboard');
Route::get('listings', 'ApiController@getListings');
Route::get('coin/{id}', 'ApiController@getCoin');

//Data that is cached in redis cluster, free access
Route::get('internal_listings', 'ApiController@getInternalListings');
Route::get('coin_price/{id}', 'ApiController@getCoinPrice');




// Access allowed only for "super user" with id 1 (api_token)
Route::get('update_prices', 'ApiController@updatePrices');
Route::get('update_listings', 'ApiController@updateListings');
Route::get('all_transactions', 'ApiController@getAllTransactions');





