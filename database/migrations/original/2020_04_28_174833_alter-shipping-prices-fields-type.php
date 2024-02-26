<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShippingPricesFieldsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `shipping_prices` MODIFY `price` double NULL;');
        DB::statement('ALTER TABLE `shipping_prices` MODIFY `price_per_kilo` double NULL;');
        DB::statement('ALTER TABLE `shipping_prices` MODIFY `price_free_shipping` double NULL;');
        DB::statement('ALTER TABLE `shipping_prices` MODIFY `delivery_time` varchar(255) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
