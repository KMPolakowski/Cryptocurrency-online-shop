<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\PayPal;
use Illuminate\Support\Facades\Input;

use DB;


use App\User;

use GuzzleHttp\Client;


class ApiController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('throttle:60,1', ['except' => ['getCryptoNames']]);
    }

    public function getCryptoNames()
    {
        $cryptonames = DB::getSchemaBuilder()->getColumnListing('cryptos');

        array_pop($cryptonames);
        // removes  last value (user_id)
        return $cryptonames; 
    }

    public function getDashboard($page, $selectedCurrency)
    {
        $client = new Client(); //GuzzleHttp\Client
        
        $pageIndex = 1;
        
        
        if($page != 1)
        {
            $pageIndex = ($page-1) * 100 +1;
        }

        $coins = ($client->get('https://api.coinmarketcap.com/v2/ticker/?start='.$pageIndex.'&limit=100&sort=rank&convert='.$selectedCurrency))->getBody();


        return $coins;
    }

    public function getListings()
    {
        $client = new Client(); //GuzzleHttp\Client

        $listings = ($client->get('https://api.coinmarketcap.com/v2/listings/'))->getBody();

        return $listings;
    }

    public function getCoin($id)
    {

        $client = new Client(); //GuzzleHttp\Client

        $coin = ($client->get('https://api.coinmarketcap.com/v2/ticker/'.$id.'/?convert=EUR'))->getBody();

        return $coin;
    }


}
