<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_id')->index('shippping_prices_shipping_id_index');
            $table->integer('city_id')->nullable()->index('shippping_prices_city_id_index');
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->double('price')->nullable();
            $table->double('price_per_kilo')->nullable();
            $table->double('price_free_shipping')->nullable();
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_prices');
    }
}
