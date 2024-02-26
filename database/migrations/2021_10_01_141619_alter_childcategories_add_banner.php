<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterChildcategoriesAddBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('childcategories', 'banner')) {
            Schema::table('childcategories', function(Blueprint $table){
                $table->string('banner')->nullable();
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
        if(Schema::hasColumn('childcategories', 'banner')){
            Schema::table('childcategories', function (Blueprint $table) {
                $table->dropColumn('banner');
            });
        }
    }
}
