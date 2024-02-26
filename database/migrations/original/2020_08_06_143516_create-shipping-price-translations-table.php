<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingPriceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_prices_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_prices_id')->unsigned();
            $table->string('locale')->index();
            $table->string('delivery_time')->nullable();

            $table->unique(['shipping_prices_id', 'locale']);
            $table->foreign('shipping_prices_id')->references('id')->on('shipping_prices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_prices_translations');
    }
}
