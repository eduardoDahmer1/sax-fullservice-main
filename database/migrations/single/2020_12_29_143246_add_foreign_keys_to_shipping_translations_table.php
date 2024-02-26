<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToShippingTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_translations', function (Blueprint $table) {
            $table->foreign('shipping_id')->references('id')->on('shippings')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_translations', function (Blueprint $table) {
            $table->dropForeign('shipping_translations_shipping_id_foreign');
        });
    }
}
