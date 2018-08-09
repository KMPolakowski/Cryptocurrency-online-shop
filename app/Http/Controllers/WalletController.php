<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

use App\Coin_price;
use App\Coin_balance;

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
        
        $coin_balances = $user->coin_balances;

        return $coin_balances;

    }

    public function getToken()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        $api_token = $user->api_token;;

        return $api_token;
    }


}
