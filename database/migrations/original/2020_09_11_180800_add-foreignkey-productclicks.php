<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignkeyProductclicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement(
            'DELETE product_clicks from product_clicks
            LEFT JOIN products
            ON product_clicks.product_id = products.id
            WHERE products.id IS NULL;'
        );
        
        Schema::table('product_clicks', function(Blueprint $table){
            $table->integer('product_id')->unsigned()->index()->change();
            $table->foreign('product_id')->references('id')->on('products'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_clicks', function(Blueprint $table){
            $table->dropForeign('product_clicks_product_id_index');
            $table->dropIndex('product_id');
            $table->dropColumn('product_id');
        });
    }
}
