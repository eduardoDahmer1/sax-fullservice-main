<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShippingsMoveTranslatableFields extends Migration
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
        DB::statement("insert into shipping_translations 
         (shipping_id, locale, title, subtitle) 
         select id, '{$lang1->locale}', title, subtitle 
         from shippings");

        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('subtitle');
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
