<?php


namespace App\Classes;

use App\Classes;

require '../buycryptos/vendor/autoload.php';


use Predis;



class predisClient
{
    
    public function client()
    {
        $client = new Predis\Client([
            'scheme' => 'tcp',
            'host'   => 'tradecryptos.egaw55.clustercfg.euc1.cache.amazonaws.com',
            'port'   => 6379,
        ]);

        return $client;
    }

}
