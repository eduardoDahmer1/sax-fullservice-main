<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddShowProductsWithoutStockBaw extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'show_products_without_stock_baw')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('show_products_without_stock_baw')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('generalsettings', 'show_products_without_stock_baw')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('show_products_without_stock_baw');
            });
        }
    }
}
