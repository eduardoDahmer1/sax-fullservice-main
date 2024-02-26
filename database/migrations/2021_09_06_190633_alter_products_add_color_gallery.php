<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddColorGallery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'color_gallery')) {
            Schema::table('products', function(Blueprint $table){
                $table->mediumText('color_gallery')->default(null)->nullable();
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
        if(Schema::hasColumn('products', 'color_gallery')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('color_gallery');
            });
        }
    }
}
