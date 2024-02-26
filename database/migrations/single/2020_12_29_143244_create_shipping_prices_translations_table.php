<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingPricesTranslationsTable extends Migration
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
            $table->unsignedInteger('shipping_prices_id');
            $table->string('locale')->index();
            $table->string('delivery_time')->nullable();
            $table->unique(['shipping_prices_id', 'locale']);
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
