<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersMoveTranslatableFields extends Migration
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
         DB::statement("insert into user_translations 
         (user_id, locale, shop_details, shop_message) 
         select id, '{$lang1->locale}', shop_details, shop_message 
         from users");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('shop_details');
            $table->dropColumn('shop_message');
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
