<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'material')) {
            Schema::table('products', function(Blueprint $table){
                $table->string('material')->default(null)->nullable();
            });
        }
        if(!Schema::hasColumn('products', 'material_gallery')) {
            Schema::table('products', function(Blueprint $table){
                $table->mediumText('material_gallery')->default(null)->nullable();
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
        if(Schema::hasColumn('products', 'material')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('material');
            });
        }if(Schema::hasColumn('products', 'material_gallery')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('material_gallery');
            });
        }
    }
}
