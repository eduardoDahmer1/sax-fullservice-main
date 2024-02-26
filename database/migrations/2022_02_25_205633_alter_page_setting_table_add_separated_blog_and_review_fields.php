<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPageSettingTableAddSeparatedBlogAndReviewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->tinyInteger('blog_posts')->default(true)->after('best');
            $table->tinyInteger('reviews_store')->default(true)->after('blog_posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->dropColumn("blog_posts");
            $table->dropColumn("reviews_store");
        });
    }
}
