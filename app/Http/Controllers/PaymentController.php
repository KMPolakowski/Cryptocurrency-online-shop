<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\PayPal;
use Illuminate\Support\Facades\Input;

use DB;

use App\Classes\getPrices;

use App\User;
use App\Coin_balance;
use App\Coin_price;
use App\Balance;


class PaymentController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    } 
    

    public function pay(Request $request)
    { 
            $paypal = new PayPal();

            return $paypal->pay($request->selectedCrypto, $request->quantity);
    }

    public function success()
    {
        $paymentId = Input::get('paymentId');
        $bearer = Input::get('token');
        $payerId = Input::get('PayerID');

        $paypal = new PayPal();

        $result = $paypal->execute($paymentId, $bearer, $payerId);
        $resultDecoded = json_decode($result, true);
        $buyingQuantity = $resultDecoded["transactions"][0]["description"];
        $coinId = $resultDecoded["transactions"][0]["item_list"]["items"][0]["sku"];

        

        $coin_balance = Coin_balance::where('user_id', '=', auth()->user()->id)->where('coin_id', $coinId)->first();

        if($coin_balance == null)
        {
            $coin_balance = new Coin_balance();
            $coin_balance->coin_id = $coinId;
            $coin_balance->quantity = $buyingQuantity;
            $coin_balance->user_id = auth()->user()->id;
            $coin_balance->save();

            return redirect('wallet');
        }

        else
        {
        $newQuantity = $coin_balance->quantity + $buyingQuantity;

        $coin_balance->quantity = $newQuantity;
        $coin_balance->save();

        return redirect('wallet');

        }

    }

    public function sell(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $coinId = $request->selectedCrypto["id"];
        $selectedCrypto = $request->selectedCrypto;

        $sellQuantity = $request->quantity;

        

        if($sellQuantity > 0)
        {
            
        $coin_balance = Coin_balance::where('user_id', '=', auth()->user()->id)->where('coin_id', $coinId)->first();

        if($coin_balance == null)
        {
            return 0;
        }
        $coinsOwned = $coin_balance->quantity;


        if($coinsOwned >= $sellQuantity)
        {
            $getPrices = new getPrices(); 

            $actualPrice = $getPrices->fetchPrices($coinId);
            $sellQuantity = round($sellQuantity, 8);
    
            $price = $selectedCrypto["quotes"]["EUR"]["price"];
    
            if(round($actualPrice, 2) !== round($price, 2))
            {
                    return $actualPrice;
            }



            $coin_balance->quantity = round($coinsOwned - $sellQuantity, 8);
            $coin_balance->save();

            $balance = Balance::find(auth()->user()->id);
            $balanceOwned = $balance->eur;
            $balance->eur = $balanceOwned + round(($actualPrice * $sellQuantity), 2);
            $balance->save();
            

            return "/wallet";
        }

        else
        {
            return 0;
        }

    }

    else
    {
        return 0;
    }

     
    
    }


}
