<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAttributeOptionTranslationAddDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('attribute_option_translations', 'description')) {
            Schema::table('attribute_option_translations', function(Blueprint $table){
                $table->string('description')->nullable();
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
        if(Schema::hasColumn('attribute_option_translations', 'description')){
            Schema::table('attribute_option_translations', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
}
