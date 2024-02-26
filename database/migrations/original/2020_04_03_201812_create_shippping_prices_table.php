<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipppingPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippping_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_id')->index();
            $table->integer('city_id')->unsigned()->index();
            $table->double('price', 8, 2);
            $table->double('price_per_kilo', 8, 2);
            $table->double('price_free_shipping', 8, 2);
            $table->string('delivery_time'); 
            $table->integer('status');	

        });

        Schema::table('shippping_prices', function (Blueprint $table) {
            $table->foreign('shipping_id')->references('id')->on('shippings');
            $table->foreign('city_id')->references('id')->on('cities');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippping_prices');
    }
}
