<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGeneralsettingsAddIsCartAndBuyAvailable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_cart_and_buy_available')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_cart_and_buy_available')->default(true);
            });
        }
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('generalsettings', 'is_cart_and_buy_available')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_cart_and_buy_available');
            });
        }
        //
    }
}
