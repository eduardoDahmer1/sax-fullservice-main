<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderTracksMoveTranslatableFields extends Migration
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
         DB::statement("insert into order_track_translations 
         (order_track_id, locale, title, text) 
         select id, '{$lang1->locale}', title, text 
         from order_tracks");

        Schema::table('order_tracks', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_tracks', function (Blueprint $table) {
            //it is not possible to revert. Old attributes would be nullable
        });
    }
}
