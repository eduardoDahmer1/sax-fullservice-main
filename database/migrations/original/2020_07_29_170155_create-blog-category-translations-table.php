<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_category_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blog_category_id');
            $table->string('locale')->index();
            $table->string('name');
            
            $table->unique(['blog_category_id', 'locale']);
            $table->foreign('blog_category_id')->references('id')->on('blog_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_category_translations');
    }
}
