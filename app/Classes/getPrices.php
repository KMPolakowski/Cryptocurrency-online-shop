<?php

namespace App\Classes;

use App\Crypto_prices;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use DB;

class getPrices
{

    public function fetchPrices()
    {
        $client = new Client(); //GuzzleHttp\Client

        $request = new Request('GET', 'https://api.coinmarketcap.com/v2/ticker/?convert=EUR&limit=30');
        $response = json_decode(($client->send($request))->getBody(), true);


        $cryptoIds = [
            1,
            1027,
            52,
            1831,
            1765,
            2,
            512,
            2010,
            1720,
            825
        ];



        Crypto_prices::truncate();

        // DB::table('crypto_prices')->insert([
        //     ['name' => $response->getBody()]
        // ])



        foreach ($cryptoIds as $cryptoId)
        {  
                $cryptoprice = new Crypto_prices();

                $cryptoprice->id = $cryptoId;
                $cryptoprice->name = $response['data'][$cryptoId]['name'];
                $cryptoprice->priceEUR = $response['data'][$cryptoId]['quotes']['EUR']['price'];
                $cryptoprice->volume_24h = $response['data'][$cryptoId]['quotes']['EUR']['volume_24h'];
                $cryptoprice->market_cap = $response['data'][$cryptoId]['quotes']['EUR']['market_cap'];
                $cryptoprice->percent_change_1h = $response['data'][$cryptoId]['quotes']['EUR']['percent_change_1h'];
                $cryptoprice->percent_change_24h = $response['data'][$cryptoId]['quotes']['EUR']['percent_change_24h'];
                $cryptoprice->percent_change_7d = $response['data'][$cryptoId]['quotes']['EUR']['percent_change_7d'];

                $cryptoprice->save();
         }


        return true;


    }

    }