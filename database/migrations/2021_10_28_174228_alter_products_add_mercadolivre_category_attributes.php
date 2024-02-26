<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddMercadolivreCategoryAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'mercadolivre_category_attributes')) {
            Schema::table('products', function(Blueprint $table){
                $table->text('mercadolivre_category_attributes')->nullable();
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
        if(Schema::hasColumn('products', 'mercadolivre_category_attributes')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('mercadolivre_category_attributes');
            });
        }
    }
}
