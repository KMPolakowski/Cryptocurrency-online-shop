<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\PayPal;
use Illuminate\Support\Facades\Input;

use DB;

use App\User;

use GuzzleHttp\Client;

use Predis;



class ApiController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('throttle:60,1', ['except' => ['getCryptoNames']]);
        $this->middleware('auth:api', ['except' => ['getCoinPrice', 'getDashboard', 'getInternalListings', 'getListings', 'getCoin']]);
    }

    public function updatePrices()
    {

        if(auth()->user()->id == 1)
        {
        $guzzClient = new Client(); //GuzzleHttp\Client

        $client = new Predis\Client();

        for($i = 1; $i < 1600; $i+=100)
        {
                $coins = json_decode(((string) ($guzzClient->get('https://api.coinmarketcap.com/v2/ticker/?start='.$i.'&limit=100&convert=EUR'))->getBody()));

            
        if($coins !== null)
            {
            foreach($coins->data as $value)
            {
            $client->hmset('coin:'.$value->id, 'symbol', $value->symbol, 'name', $value->name, 'priceEUR', $value->quotes->EUR->price);

            }
            }
        }
    }
    }

    public function updateListings()
    {
        if(auth()->user()->id == 1)
        {
        $guzzleClient = new Client();

        $listings = json_decode((string) ($guzzleClient->get('https://api.coinmarketcap.com/v2/listings/'))->getBody());

        $client = new Predis\Client();
        

        foreach($listings->data as $value)
        {
            $client->sadd('listingsIndex', $value->id);
            $client->hmset('listing:'.$value->id, 'name', $value->name, 'id', $value->id);
        }
    }
    }

    public function getCoinPrice($id)
    {
        $client = new Predis\Client();
        $price = $client->hmget('coin:'.$id, 'priceEUR');
        return $price;
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

    public function getAllTransactions()
    {
        if(auth()->user()->id == 1)
        {

        
        $client = new Predis\Client();
        
        $transactions = [];

        foreach($client->smembers('transactionIndices') as $value)
        {
            array_push($transactions, $client->hgetall('transaction:'.$value));
        }

        return $transactions; 

    }

    else
    {
        return redirect('/');
    }

        
    }

    public function getMyTransactions()
    {
        $client = new Predis\Client();
        
        $userId = auth()->user()->id;

        $transactions = [];

        foreach($client->smembers('transactionIndices') as $value)
        {
            if(explode(':', $value)[0] == $userId)
            {
            array_push($transactions, $client->hgetall('transaction:'.$value));
            
        }
        }

        return $transactions; 
    }


    public function getInternalListings()
    {
        $client = new Predis\Client();
        
        $listings = [];

        foreach($client->smembers('listingsIndex') as $value)
        {
            array_push($listings, $client->hmget('listing:'.$value, 'id', 'name'));
        }

        return $listings;           
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
