<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductsTableAddBrandForeignKeyIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            'UPDATE products
            LEFT JOIN brands
            ON products.brand_id = brands.id
            SET products.brand_id = NULL
            WHERE brands.id IS NULL;'
        );
        
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
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
        });
    }
}
