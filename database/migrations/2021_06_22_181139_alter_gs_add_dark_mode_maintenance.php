<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddDarkModeMaintenance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_dark_mode')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_dark_mode')->default(false);
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
        if(Schema::hasColumn('generalsettings', 'is_dark_mode')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_dark_mode');
            });
        }
    }
}
