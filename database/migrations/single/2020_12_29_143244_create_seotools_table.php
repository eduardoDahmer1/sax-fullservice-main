<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeotoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seotools', function (Blueprint $table) {
            $table->increments('id');
            $table->text('google_analytics')->nullable();
            $table->text('facebook_pixel')->nullable();
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
        Schema::dropIfExists('seotools');
    }
}
