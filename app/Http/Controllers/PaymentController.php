<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\PayPal;
use Illuminate\Support\Facades\Input;

use DB;

use App\Classes\getPrices;

use App\User;
use App\Crypto;
use App\Crypto_prices;
use App\Balance;


class PaymentController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth', ['except' => ['getCryptoNames']]);
    } 
    

    public function getCryptoNames()
    {
        // $cryptonames = DB::getSchemaBuilder()->getColumnListing('cryptos');

        // array_pop($cryptonames);

        $response = array();

        $crypto_prices = Crypto_prices::all();

        foreach($crypto_prices as $item)
        {
            array_push($response, array($item->name, $item->priceEUR));
        }
        // removes  last value (user_id)

        return json_encode($response);
        
    }


    public function pay(Request $request)
    { 
            $paypal = new PayPal();

            $formPrice = $request->selectedCrypto[1];

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
        $addQuantity = $resultDecoded["transactions"][0]["description"];
        $cryptoId = $resultDecoded["transactions"][0]["item_list"]["items"][0]["sku"];

        // $user = User::find(auth()->user()->id);
        // $user->cryptos->bitcoin = 2.5;
        // $user->save();

        $crypto = Crypto::find(auth()->user()->id);
        
        $quantity = $crypto->$cryptoId + $addQuantity;
        $crypto->$cryptoId = $quantity;
        $crypto->save();

        return redirect('wallet');
    }

    public function sell(Request $request)
    {
        $crypto = Crypto::find(auth()->user()->id);

        $selectedCrypto = $request->selectedCrypto[0];
        $selectedCryptoPrice = $request->selectedCrypto[1];


        $sellQuantity = $request->quantity;

        $cryptoOwned = $crypto->$selectedCrypto;


        if($crypto->$selectedCrypto >= $sellQuantity)
        {
            $cryptoOwned = $crypto->$selectedCrypto;

            $crypto->$selectedCrypto = round($cryptoOwned - $sellQuantity, 2);
            $crypto->save();


            $getPrices = new getPrices(); 
            $getPrices->fetchPrices();
            $price = Crypto_prices::where('name', $selectedCrypto)->first()->priceEUR;
    
            
            if(round($price, 2) !== round($selectedCryptoPrice, 2))
            {
                return $price;
            }

            $balance = Balance::find(auth()->user()->id);

            $balanceOnwed = $balance->eur;

            $balance->eur = $balanceOnwed + round(($price * $sellQuantity), 2);
            $balance->save();

            return "/wallet";
        }

        else
        {
            return 0;
        }

        
    }


}
