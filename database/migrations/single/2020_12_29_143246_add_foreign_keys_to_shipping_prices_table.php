<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShippingPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->foreign('shipping_id', 'shippping_prices_shipping_id_foreign')->references('id')->on('shippings')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->dropForeign('shippping_prices_shipping_id_foreign');
        });
    }
}
