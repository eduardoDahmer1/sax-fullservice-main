<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSlidersMoveTranslatableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // get language 1
        $lang1 = DB::table('languages')->where('id', 1)->first();

        // We insert the old attributes into the fresh translation table: 
        DB::statement("insert into slider_translations 
         (slider_id, locale, subtitle_text, title_text, details_text) 
         select id, '{$lang1->locale}', subtitle_text, title_text, details_text 
         from sliders");

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('subtitle_text');
            $table->dropColumn('title_text');
            $table->dropColumn('details_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //it is not possible to revert. Old attributes would be nullable
    }
}
