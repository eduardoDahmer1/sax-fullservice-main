<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsAddMercadolivreData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('products', 'mercadolivre_name')) {
            Schema::table('products', function(Blueprint $table){
                $table->string('mercadolivre_name')->nullable();
                $table->text('mercadolivre_description')->nullable();
                $table->string('mercadolivre_id')->nullable();
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
        if(Schema::hasColumn('products', 'mercadolivre_name')){
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('mercadolivre_name');
                $table->dropColumn('mercadolivre_description');
                $table->dropColumn('mercadolivre_id');
            });
        }
    }
}
