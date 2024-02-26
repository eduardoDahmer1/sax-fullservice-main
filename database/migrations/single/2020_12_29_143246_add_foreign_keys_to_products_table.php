<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('childcategory_id')->references('id')->on('childcategories')->onUpdate('RESTRICT')->onDelete('SET NULL');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onUpdate('RESTRICT')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_brand_id_foreign');
            $table->dropForeign('products_category_id_foreign');
            $table->dropForeign('products_childcategory_id_foreign');
            $table->dropForeign('products_subcategory_id_foreign');
        });
    }
}
