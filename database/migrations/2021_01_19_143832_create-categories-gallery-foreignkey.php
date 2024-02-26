<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesGalleryForeignkey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories_gallery', function(Blueprint $table)
        {
            $table->integer('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories_gallery', function (Blueprint $table) {
            $table->dropForeign('categories_gallery_category_id_foreign');
            $table->dropColumn('category_id');
        });
    }
}
