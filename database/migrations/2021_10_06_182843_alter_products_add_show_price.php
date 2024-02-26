<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddShowPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'show_price')) {
            Schema::table('products', function(Blueprint $table){
                $table->boolean('show_price')->default(true);
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
        if(Schema::hasColumn('products', 'show_price')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('show_price');
            });
        }
    }
}
