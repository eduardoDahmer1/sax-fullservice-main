<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCategoriesGalleryAddThumbnail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('categories_gallery', 'thumbnail')) {
            Schema::table('categories_gallery', function(Blueprint $table){
                $table->string('thumbnail')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('categories_gallery', 'thumbnail')){
            Schema::table('categories_gallery', function (Blueprint $table) {
                $table->dropColumn('thumbnail');
            });
        }
    }
}
