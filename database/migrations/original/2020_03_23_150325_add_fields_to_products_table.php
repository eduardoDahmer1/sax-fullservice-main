<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
             $table->text('ref_code')->nullable();
             $table->text('mpn')->nullable();
             $table->text('free_shipping')->nullable();
             $table->text('max_quantity')->nullable();
             $table->text('weight')->nullable();

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
             $table->dropColumn('ref_code');
             $table->dropColumn('mpn');
             $table->dropColumn('free_shipping');
             $table->dropColumn('max_quantity');
             $table->dropColumn('weight');
        });
    }
}
