<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCategoriesAddBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('categories', 'banner')) {
            Schema::table('categories', function(Blueprint $table){
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
        if(Schema::hasColumn('categories', 'banner')){
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('banner');
            });
        }
    }
}
