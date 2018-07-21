<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Crypto_prices extends Model
{

    public $timestamps = false;


    protected $attributes = [
        'name' => '',
        'priceEUR' => 0,
        'volume_24h' => 0,
        'market_cap' => 0,
        'percent_change_1h' => 0,
        'percent_change_24h' => 0,
        'percent_change_7d' => 0
        ];

    public function __construct()
    { 

    }





    


}
