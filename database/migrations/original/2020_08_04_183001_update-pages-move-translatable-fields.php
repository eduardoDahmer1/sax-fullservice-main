<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePagesMoveTranslatableFields extends Migration
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
        DB::statement("insert into page_translations 
         (page_id, locale, title, details, meta_tag, meta_description) 
         select id, '{$lang1->locale}', title, details, meta_tag, meta_description 
         from pages");

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('details');
            $table->dropColumn('meta_tag');
            $table->dropColumn('meta_description');
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
