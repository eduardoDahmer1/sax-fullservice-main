<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShippingPricesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_prices_translations', function (Blueprint $table) {
            $table->foreign('shipping_prices_id')->references('id')->on('shipping_prices')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_prices_translations', function (Blueprint $table) {
            $table->dropForeign('shipping_prices_translations_shipping_prices_id_foreign');
        });
    }
}
