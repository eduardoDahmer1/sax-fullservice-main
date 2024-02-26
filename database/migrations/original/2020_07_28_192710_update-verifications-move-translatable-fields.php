<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVerificationsMoveTranslatableFields extends Migration
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
         DB::statement("insert into verification_translations 
         (verification_id, locale, text, warning_reason) 
         select id, '{$lang1->locale}', text, warning_reason 
         from verifications");

        Schema::table('verifications', function (Blueprint $table) {
            $table->dropColumn('text');
            $table->dropColumn('warning_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verifications', function (Blueprint $table) {
            //it is not possible to revert. Old attributes would be nullable
        });
    }
}
