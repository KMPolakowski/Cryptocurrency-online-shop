<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

use App\Crypto_prices;
use App\Balance;

class WalletController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        $crypto_p = new Crypto_prices;

        $prices = $crypto_p->pluck('priceEUR', 'name');
        

        foreach($prices as $key => $val)
        {
         $prices[$key] = [$val, $user->cryptos->$key];
        }

 
        return $prices;
    }


}
