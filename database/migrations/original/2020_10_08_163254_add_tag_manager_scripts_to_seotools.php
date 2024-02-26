<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagManagerScriptsToSeotools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seotools', function (Blueprint $table) {
            $table->text('tag_manager_head')->nullable();
            $table->text('tag_manager_body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seotools', function (Blueprint $table) {
            $table->dropcolumn('tag_manager_head');
            $table->dropcolumn('tag_manager_body');
        });
    }
}
