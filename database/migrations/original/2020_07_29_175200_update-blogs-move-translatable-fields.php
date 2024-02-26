<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBlogsMoveTranslatableFields extends Migration
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
        DB::statement("insert into blog_translations 
         (blog_id, locale, title, details, meta_tag, meta_description, tags) 
         select id, '{$lang1->locale}', title, details, meta_tag, meta_description, tags 
         from blogs");

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('details');
            $table->dropColumn('meta_tag');
            $table->dropColumn('meta_description');
            $table->dropColumn('tags');
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
