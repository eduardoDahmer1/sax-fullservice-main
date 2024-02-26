<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGeneralsettingsAddIsBackInStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_back_in_stock')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_back_in_stock')->default(false);
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
        if(Schema::hasColumn('generalsettings', 'is_back_in_stock')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_back_in_stock');
            });
        }
    }
}
