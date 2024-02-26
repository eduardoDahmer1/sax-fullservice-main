<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePagesettingsMoveTranslatableFields extends Migration
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
        DB::statement("insert into pagesetting_translations 
         (pagesetting_id, locale, contact_success, contact_title, contact_text, side_title, side_text) 
         select id, '{$lang1->locale}', contact_success, contact_title, contact_text, side_title, side_text 
         from pagesettings");

        Schema::table('pagesettings', function (Blueprint $table) {
            $table->dropColumn('contact_success');
            $table->dropColumn('contact_title');
            $table->dropColumn('contact_text');
            $table->dropColumn('side_title');
            $table->dropColumn('side_text');
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
