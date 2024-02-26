<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShippingPricesAddStateCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->dropForeign('shippping_prices_city_id_foreign');
            $table->Integer('state_id')->after('city_id')->nullable();
            $table->Integer('country_id')->after('state_id')->nullable();
        });
        DB::statement('ALTER TABLE `shipping_prices` MODIFY `city_id` int NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_prices', function (Blueprint $table) {
            $table->dropColumn('state_id');
            $table->dropColumn('country_id');
        });
    }
}
