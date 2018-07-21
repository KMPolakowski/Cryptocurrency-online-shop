<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Classes\getPrices;

class CreateCryptoPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_prices', function (Blueprint $table) {
            $table->integer('id')->default('0');
            $table->string('name')->default('');
            $table->double('priceEUR')->default(0);
            $table->double('volume_24h')->default(0);
            $table->double('market_cap')->default(0);
            $table->double('percent_change_1h')->default(0);
            $table->double('percent_change_24h')->default(0);
            $table->double('percent_change_7d')->default(0);
            
            $table->primary('id');
        });

        

        $prices = new getPrices();

        $prices->fetchPrices();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crypto_prices');
    }
}
