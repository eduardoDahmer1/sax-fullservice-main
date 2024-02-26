<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBlogCategoriesMoveTranslatableFields extends Migration
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
        DB::statement("insert into blog_category_translations 
         (blog_category_id, locale, name) 
         select id, '{$lang1->locale}', name 
         from blog_categories");

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropColumn('name');
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
