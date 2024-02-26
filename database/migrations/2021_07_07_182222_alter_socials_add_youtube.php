<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSocialsAddYoutube extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('socialsettings', 'youtube')) {
            Schema::table('socialsettings', function(Blueprint $table){
                $table->string('youtube')->nullable();
            });
        }
        if(!Schema::hasColumn('socialsettings', 'y_status')) {
            Schema::table('socialsettings', function(Blueprint $table){
                $table->boolean('y_status')->default(false);
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
        if(Schema::hasColumn('socialsettings', 'youtube')){
            Schema::table('socialsettings', function (Blueprint $table) {
                $table->dropColumn('youtube');
            });
        }
        if(Schema::hasColumn('socialsettings', 'y_status')){
            Schema::table('socialsettings', function (Blueprint $table) {
                $table->dropColumn('y_status');
            });
        }
    }
}
