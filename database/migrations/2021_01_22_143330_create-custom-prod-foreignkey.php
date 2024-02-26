<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomProdForeignkey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_prod', function (Blueprint $table) {
            $table->integer('product_id')->index()->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('order_id')->index()->change();
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_prod', function (Blueprint $table) {
            $table->dropForeign('custom_prod_product_id_foreign');
            $table->dropIndex('product_id');
            $table->dropColumn('product_id');
            $table->dropForeign('custom_prod_order_id_foreign');
            $table->dropIndex('order_id');
            $table->dropColumn('order_id');
        });
    }
}
