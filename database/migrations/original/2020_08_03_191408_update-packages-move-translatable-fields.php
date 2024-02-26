<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePackagesMoveTranslatableFields extends Migration
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
        DB::statement("insert into package_translations 
         (package_id, locale, title, subtitle) 
         select id, '{$lang1->locale}', title, subtitle 
         from packages");

        Schema::table('packages', function (Blueprint $table) {
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
        Schema::table('packages', function (Blueprint $table) {
            //it is not possible to revert. Old attributes would be nullable
        });
    }
}
