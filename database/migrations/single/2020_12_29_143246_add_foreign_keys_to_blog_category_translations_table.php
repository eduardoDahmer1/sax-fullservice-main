<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBlogCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_category_translations', function (Blueprint $table) {
            $table->dropForeign('blog_category_translations_blog_category_id_foreign');
        });
    }
}
