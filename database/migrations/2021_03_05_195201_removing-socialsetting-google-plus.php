<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovingSocialsettingGooglePlus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('socialsettings', function(Blueprint $table){
            $table->dropColumn('gplus'); 
            $table->dropColumn('g_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('socialsettings', function(Blueprint $table){
            $table->string('gplus'); 
            $table->tinyInteger('g_status'); 
        });
    }
}
