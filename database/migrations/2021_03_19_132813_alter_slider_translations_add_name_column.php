<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSliderTranslationsAddNameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('slider_translations', 'name')) {
            Schema::table('slider_translations', function(Blueprint $table){
                $table->string('name');
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
        if(Schema::hasColumn('slider_translations', 'name')){
            Schema::table('slider_translations', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
}
