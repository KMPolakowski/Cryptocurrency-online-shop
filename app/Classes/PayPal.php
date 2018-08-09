<?php


namespace App\Classes;

use App\Classes;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;

require '../vendor/autoload.php';

use App\Crypto_prices;

use DB;

use Predis;



class PayPal
{
    
    public function pay($selectedCrypto, $quantity)
    {
        $client = new Predis\Client();

        $actualPrice = $client->hmget('coin:'.$selectedCrypto["id"], 'priceEUR')[0];

        $quantity = round($quantity, 8);

        $price = $selectedCrypto["priceEUR"];
        


        if(round($actualPrice, 2) !== round($price, 2))
        {
                return $actualPrice;
        }


        $total = round($price * $quantity, 2);


        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AYjTGkvOsWuPDmguZRI-KT1B0ysiUljNjXRulOrbpBZuRt1-CktUT5CzdvxQYaPMOuxJG-XbFcH6glJM',     // ClientID
                'EHZcrgVIyIt3TeBeFP-YPOCtfqL_o3NbAP_x1sSz8mdD3pjzQRP48T3L18KTDTAbDC8er1SBD4CsODN2'      // ClientSecret
            ));

    // Create new payment 
    
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $item1 = new Item();
    $item1->setName($selectedCrypto["id"])
    ->setCurrency('EUR')
    ->setQuantity(1)
    ->setSku($selectedCrypto["id"]) // Similar to `item_number` in Classic API
    ->setPrice($total);

    $itemList = new ItemList();
    $itemList->setItems(array($item1));

    $details = new Details();
    $details->setSubtotal($total);

    $amount = new Amount();
    $amount->setCurrency("EUR")
    ->setTotal($total)
    ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription($quantity)
    ->setInvoiceNumber(uniqid());

    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl("http://buycryptos.com/buy/pay/success")
    ->setCancelUrl("http://buycryptos.com");


    $payment = new Payment();
    $payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));
    

    try {
        $payment->create($apiContext);
        // echo $payment;
        
        return $payment->getApprovalLink();

        // return $payment;
    }
    catch (\PayPal\Exception\PayPalConnectionException $ex) {
        // This will print the detailed information on the exception.
        //REALLY HELPFUL FOR DEBUGGING
        echo $ex->getData();
    }


    }


    public function execute($paymentId, $bearer, $payerId)
    {

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'ATLin3cON9cBZc_srrZW7kv5kcVB4S37PLK7Dn8NMLDOlr-82M90R1xPZoBzf5uhqcsZk4-SHTewDmnf',     // ClientID
                'ELmITTYpQpPFwDVI-UNQPnhdjr7kb7zO553tlAu3Z3CIFOHFv4DGo0LZPGMabOTKmOvuxYgQ14PpGnSE'      // ClientSecret
            ));


        $payment = Payment::get($paymentId, $apiContext);
        

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

      

        try {
        $result = $payment->execute($execution, $apiContext);

        return $result;
        }

        catch(Exception $e)
        {
            echo "Executed Payment", "Payment", null, null, $ex;
            exit(1);
        }

    }

}
