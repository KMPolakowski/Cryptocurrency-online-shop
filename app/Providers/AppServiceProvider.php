<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use App\User;
use App\Balance;

use GuzzleHttp\Client;

use Predis;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Work around laravel bug
        Schema::defaultStringLength(191);

        User::created(function($user)
        {
               $balance = new Balance;
               $balance->user_id = $user->id;
               $balance->save();
        });

        User::creating(function($user)
        {
               $user->api_token = bin2hex(openssl_random_pseudo_bytes(30));
        });

        // $guzzleClient = new Client();

        // $listings = json_decode((string) ($guzzleClient->get('https://api.coinmarketcap.com/v2/listings/'))->getBody());

        // $client = new Predis\Client();
        

        // foreach($listings->data as $value)
        // {
        //     $client->sadd('listingsIndex', $value->id);
        //     $client->hmset('listing:'.$value->id, 'name', $value->name, 'id', $value->id);
        // }


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
