<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGsAddZipValidation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('generalsettings', 'is_zip_validation')) {
            Schema::table('generalsettings', function(Blueprint $table){
                $table->boolean('is_zip_validation')->default(true);
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
        if(Schema::hasColumn('generalsettings', 'is_zip_validation')){
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn('is_zip_validation');
            });
        }
    }
}
