<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubcategoriesAddBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('subcategories', 'banner')) {
            Schema::table('subcategories', function(Blueprint $table){
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
        if(Schema::hasColumn('subcategories', 'banner')){
            Schema::table('subcategories', function (Blueprint $table) {
                $table->dropColumn('banner');
            });
        }
    }
}
