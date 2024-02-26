<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInstagramSocialsettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('socialsettings', function (Blueprint $table) {
            $table->tinyInteger('i_status')->default(0)->after('d_status');
            $table->string('instagram')->nullable()->after('dribble'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('socialsettings', function (Blueprint $table) {
            $table->dropColumn('i_status');
            $table->dropColumn('instagram'); 
        });
    }
}
