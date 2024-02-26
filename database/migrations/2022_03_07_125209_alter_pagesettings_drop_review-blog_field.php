<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPagesettingsDropReviewBlogField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            if(Schema::hasColumn('pagesettings', 'review_blog')){
                Schema::table('pagesettings', function (Blueprint $table) {
                    $table->dropColumn('review_blog');
                });
            }
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
            $table->tinyInteger('review_blog')->default(1);
        });
    }
}
