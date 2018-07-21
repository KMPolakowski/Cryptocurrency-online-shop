<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use App\User;
use App\Crypto;
use App\Balance;

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
               $crypto = new Crypto;
               $crypto->user_id = $user->id;
               $crypto->save();

               $balance = new Balance;
               $balance->user_id = $user->id;
               $balance->save();
        });
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
