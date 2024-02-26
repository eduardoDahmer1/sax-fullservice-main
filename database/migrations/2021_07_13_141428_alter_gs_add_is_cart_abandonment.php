<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddIsCartAbandonment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_cart_abandonment')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->boolean('is_cart_abandonment')->default(false);
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
        if(Schema::hasColumn('generalsettings', 'is_cart_abandonment')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_cart_abandonment');
            });
        }
    }
}
