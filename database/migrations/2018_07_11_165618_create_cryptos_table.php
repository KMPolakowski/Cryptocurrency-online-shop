<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCryptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptos', function (Blueprint $table) {
            $table->double('Bitcoin');
            $table->double('Ethereum');
            $table->double('XRP');
            $table->double('Bitcoin Cash');
            $table->double('EOS');
            $table->double('Litecoin');
            $table->double('Stellar');
            $table->double('Cardano');
            $table->double('IOTA');
            $table->double('Tether');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cryptos');
    }
}
