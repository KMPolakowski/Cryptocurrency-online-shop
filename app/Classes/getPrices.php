<?php

namespace App\Classes;

use App\Coin_price;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use DB;

class getPrices
{

    public function fetchPrices($id)
    {
        $client = new Client(); //GuzzleHttp\Client

        $coin = json_decode ((string) ($client->get('https://api.coinmarketcap.com/v2/ticker/'.$id.'/?convert=EUR'))->getBody());
        

        $price = $coin->data->quotes->EUR->price;

        // CREATE NEW MIGRATION CALLED COINS THEN UPDATE THE REQUESTED COIN IF EXISTS OR CREATE NEW ONE

        // THEN ADD APPROPRIATELY ON /BUY/PAY/SUCCESS 

        $price_row = Coin_price::where('id', '=', $coin->data->id)->first();

        if($price_row !== null)
        {
            $coin_price = Coin_price::findOrFail($id);
            $coin_price->priceEUR = $price;
            $coin_price->save();
        }
        else
        {
            $coin_price = new Coin_price();
            $coin_price->id = $coin->data->id;
            $coin_price->symbol = $coin->data->symbol;
            $coin_price->priceEUR = $price;
            $coin_price->save();        
        }
        
        return $price;
    }

    }